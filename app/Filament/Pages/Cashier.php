<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class Cashier extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.cashier';
    protected static ?string $navigationLabel = 'Kasir';
    protected static ?int $navigationSort = 1;
    
    // Public properties
    public $search = '';
    public $selectedCategory = null;
    public $cart = [];
    public $customerName = 'Umum';
    public $paymentMethod = 'cash';
    public $paidAmount = 0;
    public $showReceipt = false;
    public $lastTransaction = null;
    
    // Data untuk view
    public $categories = [];
    public $products = [];
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;
    public $changeAmount = 0;
    
    // FITUR DISKON
    public $discount = 0;
    public $discountType = 'percent';
    public $discountAmount = 0;
    
    // FITUR MEMBER
    public $memberPhone = '';
    public $memberId = null;
    public $memberName = '';
    public $memberPoints = 0;
    public $usePoints = false;
    public $pointsToUse = 0;
    public $pointsDiscount = 0;
    
    // FITUR LAINNYA
    public $serviceFee = 0;
    public $notes = '';
    
    public function mount()
    {
        $this->loadData();
    }
    
    public function loadData()
    {
        $this->categories = Category::all();
        $this->loadProducts();
        $this->calculateTotals();
    }
    
    public function loadProducts()
    {
        $query = Product::with('category')->where('stock', '>', 0);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }
        
        $this->products = $query->orderBy('name')->get();
    }
    
    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        
        // HITUNG DISKON
        if ($this->discountType == 'percent') {
            $this->discountAmount = $this->subtotal * ($this->discount / 100);
        } else {
            $this->discountAmount = min($this->discount, $this->subtotal);
        }
        
        // HITUNG DISKON POIN (1 poin = Rp 100)
        if ($this->usePoints && $this->memberId) {
            $this->pointsDiscount = min($this->pointsToUse * 100, $this->subtotal - $this->discountAmount);
        } else {
            $this->pointsDiscount = 0;
        }
        
        // Total setelah diskon
        $afterDiscount = $this->subtotal - $this->discountAmount - $this->pointsDiscount;
        
        // Tambah biaya layanan
        $afterService = $afterDiscount + $this->serviceFee;
        
        // Hitung PPN
        $this->tax = $afterService * 0.11;
        $this->total = $afterService + $this->tax;
        $this->changeAmount = max(0, $this->paidAmount - $this->total);
    }
    
    public function updatedSearch()
    {
        $this->loadProducts();
    }
    
    public function updatedPaidAmount()
    {
        $this->calculateTotals();
    }
    
    public function updatedDiscount()
    {
        $this->calculateTotals();
    }
    
    public function updatedDiscountType()
    {
        $this->discount = 0;
        $this->discountAmount = 0;
        $this->calculateTotals();
    }
    
    public function updatedServiceFee()
    {
        $this->calculateTotals();
    }
    
    public function updatedPointsToUse()
    {
        $this->calculateTotals();
    }
    
    public function updatedUsePoints()
    {
        if (!$this->usePoints) {
            $this->pointsToUse = 0;
            $this->pointsDiscount = 0;
        }
        $this->calculateTotals();
    }
    
    public function checkMember()
    {
        if (empty($this->memberPhone)) {
            $this->dispatch('notify', message: 'Masukkan nomor telepon member!', type: 'error');
            return;
        }
        
        $member = Member::where('phone', $this->memberPhone)->first();
        
        if ($member) {
            $this->memberId = $member->id;
            $this->memberName = $member->name;
            $this->memberPoints = $member->points;
            $this->customerName = $member->name;
            
            $this->dispatch('notify', message: "✅ Member ditemukan! Nama: {$member->name} | Poin: {$member->points}", type: 'success');
        } else {
            $this->memberId = null;
            $this->memberName = '';
            $this->memberPoints = 0;
            $this->usePoints = false;
            $this->dispatch('notify', message: '❌ Member tidak ditemukan!', type: 'error');
        }
    }
    
    public function clearMember()
    {
        $this->memberPhone = '';
        $this->memberId = null;
        $this->memberName = '';
        $this->memberPoints = 0;
        $this->usePoints = false;
        $this->pointsToUse = 0;
        $this->pointsDiscount = 0;
        $this->customerName = 'Umum';
        $this->calculateTotals();
        $this->dispatch('notify', message: 'Member dihapus', type: 'info');
    }
    
    public function setCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->loadProducts();
    }
    
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock <= 0) {
            $this->dispatch('notify', message: 'Stok habis!', type: 'error');
            return;
        }
        
        $existingKey = collect($this->cart)->search(fn($item) => $item['id'] == $productId);
        
        if ($existingKey !== false) {
            if ($this->cart[$existingKey]['quantity'] + 1 <= $product->stock) {
                $this->cart[$existingKey]['quantity']++;
            } else {
                $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
            }
        } else {
            $this->cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'stock' => $product->stock,
            ];
        }
        
        $this->calculateTotals();
        $this->dispatch('notify', message: "✓ {$product->name} ditambahkan", type: 'success');
    }
    
    public function updateCartQuantity($productId, $action)
    {
        $key = collect($this->cart)->search(fn($item) => $item['id'] == $productId);
        
        if ($key !== false) {
            if ($action === 'increase') {
                if ($this->cart[$key]['quantity'] < $this->cart[$key]['stock']) {
                    $this->cart[$key]['quantity']++;
                } else {
                    $this->dispatch('notify', message: 'Stok maksimal!', type: 'error');
                }
            } else {
                if ($this->cart[$key]['quantity'] > 1) {
                    $this->cart[$key]['quantity']--;
                } else {
                    unset($this->cart[$key]);
                    $this->cart = array_values($this->cart);
                }
            }
        }
        
        $this->calculateTotals();
    }
    
    public function removeFromCart($productId)
    {
        $key = collect($this->cart)->search(fn($item) => $item['id'] == $productId);
        if ($key !== false) {
            unset($this->cart[$key]);
            $this->cart = array_values($this->cart);
            $this->dispatch('notify', message: 'Produk dihapus dari keranjang', type: 'info');
        }
        
        $this->calculateTotals();
    }
    
    public function clearCart()
    {
        $this->cart = [];
        $this->discount = 0;
        $this->discountAmount = 0;
        $this->serviceFee = 0;
        $this->calculateTotals();
        $this->dispatch('notify', message: 'Keranjang dikosongkan', type: 'info');
    }
    
    public function scanBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->first();
        
        if ($product) {
            if ($product->stock > 0) {
                $this->addToCart($product->id);
                $this->dispatch('notify', message: "🔍 Barcode: {$product->name} ditambahkan", type: 'success');
            } else {
                $this->dispatch('notify', message: "❌ Stok {$product->name} habis!", type: 'error');
            }
        } else {
            $this->dispatch('notify', message: '❌ Barcode tidak ditemukan!', type: 'error');
        }
    }
    
    public function processPayment()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Keranjang kosong!', type: 'error');
            return;
        }
        
        if ($this->paidAmount < $this->total) {
            $this->dispatch('notify', message: 'Pembayaran kurang!', type: 'error');
            return;
        }
        
        DB::beginTransaction();
        try {
            $today = date('Ymd');
            $count = Transaction::whereDate('created_at', today())->count() + 1;
            $invoiceNumber = 'INV/' . $today . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);
            
            // Update poin member jika menggunakan poin
            if ($this->usePoints && $this->memberId && $this->pointsToUse > 0) {
                $member = Member::find($this->memberId);
                $member->decrement('points', $this->pointsToUse);
            }
            
            // Tambah poin untuk member (setiap Rp 10.000 = 1 poin)
            $earnedPoints = 0;
            if ($this->memberId) {
                $earnedPoints = floor($this->total / 10000);
                Member::where('id', $this->memberId)->increment('points', $earnedPoints);
                Member::where('id', $this->memberId)->increment('total_spent', $this->total);
            }
            
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $this->customerName,
                'member_id' => $this->memberId,
                'subtotal' => $this->subtotal,
                'discount' => $this->discountAmount,
                'discount_percent' => $this->discountType == 'percent' ? $this->discount : 0,
                'points_used' => $this->usePoints ? $this->pointsToUse : 0,
                'points_earned' => $earnedPoints,
                'service_fee' => $this->serviceFee,
                'tax' => $this->tax,
                'total_amount' => $this->total,
                'paid_amount' => $this->paidAmount,
                'change_amount' => $this->changeAmount,
                'payment_method' => $this->paymentMethod,
                'status' => 'completed',
                'user_id' => auth()->id(),
                'notes' => $this->notes,
            ]);
            
            foreach ($this->cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }
            
            DB::commit();
            
            $this->lastTransaction = $transaction->load('details.product', 'member');
            $this->cart = [];
            $this->paidAmount = 0;
            $this->customerName = 'Umum';
            $this->discount = 0;
            $this->discountAmount = 0;
            $this->serviceFee = 0;
            $this->notes = '';
            $this->showReceipt = true;
            $this->calculateTotals();
            $this->loadProducts();
            
            $successMessage = '✅ Pembayaran berhasil!';
            if ($earnedPoints > 0) {
                $successMessage .= " 🎉 Mendapat {$earnedPoints} poin!";
            }
            $this->dispatch('notify', message: $successMessage, type: 'success');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', message: '❌ Pembayaran gagal: ' . $e->getMessage(), type: 'error');
        }
    }
}