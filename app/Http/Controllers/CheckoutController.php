<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Keranjang kosong!');
        }
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping = 0;
        $total = $subtotal + $shipping;
        
        return view('checkout.index', compact('cart', 'subtotal', 'shipping', 'total'));
    }
    
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Keranjang kosong!');
        }
        
        $request->validate([
            'payment_method' => 'required',
        ]);
        
        DB::beginTransaction();
        try {
            $invoiceNumber = 'INV/' . date('Ymd') . '/' . str_pad(Transaction::count() + 1, 4, '0', STR_PAD_LEFT);
            
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $tax = $subtotal * 0.11;
            $total = $subtotal + $tax;
            
            $user = auth()->user();
            // Prioritaskan request, jika kosong pakai data user
            $phone = $request->phone ?: ($user->phone ?? '');
            $shippingAddress = $request->shipping_address ?: ($user->address ?? '');
            
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $user->name,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total,
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'order_status' => 'pending',
                'shipping_address' => $shippingAddress,
                'phone' => $phone,
                'notes' => $request->notes,
            ]);
            
            foreach ($cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }
            
            session()->forget('cart');
            DB::commit();
            
            return redirect('/checkout/success')->with('success', 'Pesanan berhasil dibuat! Invoice: ' . $invoiceNumber);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/cart')->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}