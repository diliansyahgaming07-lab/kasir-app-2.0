<x-filament::page>
    <style>
        /* Light mode styles */
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Dark mode styles */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #1f2937;
                --bg-secondary: #111827;
                --text-primary: #f9fafb;
                --text-secondary: #d1d5db;
                --text-muted: #9ca3af;
                --border-color: #374151;
            }
            
            .bg-white { background-color: var(--bg-primary) !important; }
            .bg-gray-50 { background-color: var(--bg-secondary) !important; }
            .bg-gray-100 { background-color: #374151 !important; }
            .text-gray-800 { color: var(--text-primary) !important; }
            .text-gray-700 { color: var(--text-secondary) !important; }
            .text-gray-600 { color: var(--text-muted) !important; }
            .text-gray-500 { color: #9ca3af !important; }
            .border-gray-100 { border-color: var(--border-color) !important; }
            .border-gray-200 { border-color: var(--border-color) !important; }
            
            input, select, textarea {
                background-color: #374151 !important;
                color: var(--text-primary) !important;
                border-color: var(--border-color) !important;
            }
            
            input::placeholder {
                color: var(--text-muted) !important;
            }
        }
        
        /* Animations */
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .cart-item { animation: slideIn 0.3s ease-out; }
        .product-card { animation: fadeInUp 0.4s ease-out; }
        
        .product-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: var(--bg-primary);
        }
        
        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--hover-shadow);
        }
        
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-bg-hover { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); }
        .cart-gradient { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .success-gradient { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
        .warning-gradient { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: scale(1.05);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
        }
        
        .quick-amount-btn {
            transition: all 0.2s ease;
            background: var(--bg-secondary);
            color: var(--text-secondary);
        }
        
        .quick-amount-btn:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; }
        
        .badge-stock {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            color: #1f2937;
        }
        
        @media (prefers-color-scheme: dark) {
            .badge-stock {
                background: rgba(31, 41, 55, 0.95);
                color: #f9fafb;
            }
        }
        
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 1.5s infinite;
        }
        
        .quantity-btn {
            transition: all 0.2s ease;
        }
        
        .quantity-btn:active {
            transform: scale(0.9);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .grid-cols-12 { grid-template-columns: repeat(1, 1fr) !important; }
            .col-span-8, .col-span-4 { grid-column: span 1 / span 1 !important; }
        }
    </style>

    <div class="grid grid-cols-12 gap-6">
        {{-- LEFT SIDE: PRODUCTS --}}
        <div class="col-span-8 space-y-6">
            {{-- HEADER --}}
            <div class="gradient-bg rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-40 h-40 bg-white/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-40 h-40 bg-white/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                <div class="relative">
                    <h1 class="text-3xl font-bold mb-2 flex items-center gap-2 text-white">
                        🛍️ POS Cashier Pro
                        <span class="text-xs bg-white/20 px-2 py-1 rounded-full text-white">v2.0</span>
                    </h1>
                    <p class="text-white/90 mt-2">Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Silakan pilih produk.</p>
                </div>
            </div>
            
            {{-- SEARCH BAR --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="🔍 Cari produk (nama atau barcode)..." 
                       class="w-full pl-12 pr-4 py-4 text-lg rounded-2xl border-0 shadow-lg focus:ring-2 focus:ring-purple-500 transition-all">
            </div>

            {{-- CATEGORIES --}}
            <div class="overflow-x-auto pb-2">
                <div class="flex gap-3 min-w-max">
                    <button wire:click="setCategory(null)" 
                            class="px-6 py-3 rounded-2xl font-semibold transition-all transform hover:scale-105 {{ !$selectedCategory ? 'gradient-bg text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-100 shadow-md' }}">
                        🎯 Semua
                    </button>
                    @foreach($categories as $category)
                        <button wire:click="setCategory({{ $category->id }})" 
                                class="px-6 py-3 rounded-2xl font-semibold transition-all transform hover:scale-105 {{ $selectedCategory == $category->id ? 'gradient-bg text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-100 shadow-md' }}">
                            @if($category->name == 'Makanan') 🍔
                            @elseif($category->name == 'Minuman') 🥤
                            @elseif($category->name == 'Snack') 🍿
                            @elseif($category->name == 'Rokok') 🚬
                            @else 📦
                            @endif {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- PRODUCTS GRID --}}
            <div class="grid grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="product-card bg-white rounded-2xl shadow-lg cursor-pointer overflow-hidden transition-all duration-300 hover:shadow-2xl">
                        <div class="relative h-40 gradient-bg flex items-center justify-center group-hover:gradient-bg-hover transition-all">
                            <div class="text-6xl transition-transform group-hover:scale-110 duration-300">
                                @if($product->category->name == 'Makanan') 🍔
                                @elseif($product->category->name == 'Minuman') 🥤
                                @elseif($product->category->name == 'Snack') 🍿
                                @elseif($product->category->name == 'Rokok') 🚬
                                @else 📦
                                @endif
                            </div>
                            <div class="absolute top-3 right-3 badge-stock rounded-full px-3 py-1 text-xs font-bold shadow-md">
                                📦 {{ $product->stock }}
                            </div>
                            @if($product->stock <= 10)
                                <div class="absolute bottom-3 left-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                                    ⚠️ Stok Terbatas
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ $product->category->name }}</p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-2xl font-bold text-purple-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    @if($product->stock > 0)
                                        <p class="text-xs text-green-600 mt-1">✓ Tersedia</p>
                                    @else
                                        <p class="text-xs text-red-500 mt-1">✗ Habis</p>
                                    @endif
                                </div>
                                @if($product->stock > 0)
                                    <button wire:click="addToCart({{ $product->id }})" 
                                            class="btn-gradient text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md hover:shadow-xl transition-all">
                                        + Tambah
                                    </button>
                                @else
                                    <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-xl text-sm font-semibold cursor-not-allowed">
                                        Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20">
                        <div class="text-6xl mb-4 animate-bounce">🔍</div>
                        <p class="text-gray-500 text-lg">Produk tidak ditemukan</p>
                        <p class="text-gray-400 text-sm mt-2">Coba kata kunci lain</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT SIDE: CART --}}
        <div class="col-span-4">
            <div class="bg-white rounded-2xl shadow-2xl sticky top-6 overflow-hidden">
                {{-- CART HEADER --}}
                <div class="cart-gradient p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold flex items-center gap-2 text-white">
                                    🛒 Keranjang
                                    @if(count($cart) > 0)
                                        <span class="text-sm bg-white/20 px-2 py-1 rounded-full text-white">{{ count($cart) }}</span>
                                    @endif
                                </h2>
                                <p class="text-sm text-white/90 mt-1">{{ count($cart) }} item(s)</p>
                            </div>
                            @if(!empty($cart))
                                <button wire:click="clearCart" 
                                        class="bg-white/20 hover:bg-white/30 rounded-full p-3 transition-all transform hover:scale-110 hover:rotate-90 text-white">
                                    🗑️
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CART ITEMS --}}
                <div class="p-6 max-h-[400px] overflow-y-auto bg-gray-50">
                    @if(empty($cart))
                        <div class="text-center py-16">
                            <div class="text-8xl mb-4 opacity-30">🛒</div>
                            <p class="text-gray-500 text-lg">Keranjang kosong</p>
                            <p class="text-gray-400 text-sm mt-2">Klik + Tambah pada produk</p>
                        </div>
                    @else
                        @foreach($cart as $item)
                            <div class="cart-item bg-white rounded-xl p-4 mb-3 shadow-md hover:shadow-lg transition-all">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 text-lg">{{ $item['name'] }}</h4>
                                        <p class="text-purple-600 font-semibold mt-1">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <button wire:click="removeFromCart({{ $item['id'] }})" 
                                            class="text-red-500 hover:text-red-700 transition-colors transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-3 bg-gray-100 rounded-full p-1">
                                        <button wire:click="updateCartQuantity({{ $item['id'] }}, 'decrease')" 
                                                class="quantity-btn w-10 h-10 rounded-full bg-red-500 text-white hover:bg-red-600 transition-all transform hover:scale-105 font-bold text-xl">
                                            -
                                        </button>
                                        <span class="w-12 text-center font-bold text-xl">{{ $item['quantity'] }}</span>
                                        <button wire:click="updateCartQuantity({{ $item['id'] }}, 'increase')" 
                                                class="quantity-btn w-10 h-10 rounded-full bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-105 font-bold text-xl">
                                            +
                                        </button>
                                    </div>
                                    <p class="font-bold text-gray-800 text-lg">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- CART SUMMARY --}}
                <div class="p-6 border-t-2 bg-white">
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- DISKON --}}
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600">🏷️ Diskon</span>
                                <input type="number" wire:model.live="discount" placeholder="0" 
                                       class="w-24 px-2 py-1 border rounded-lg text-right focus:ring-2 focus:ring-purple-500">
                                <select wire:model.live="discountType" 
                                        class="px-2 py-1 border rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                                    <option value="percent">%</option>
                                    <option value="nominal">Rp</option>
                                </select>
                            </div>
                            <span class="font-semibold text-red-500">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- Biaya Layanan --}}
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600">🛎️ Biaya Layanan</span>
                                <input type="number" wire:model.live="serviceFee" placeholder="0" 
                                       class="w-24 px-2 py-1 border rounded-lg text-right focus:ring-2 focus:ring-purple-500">
                            </div>
                            <span class="font-semibold text-orange-500">+ Rp {{ number_format($serviceFee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- PPN --}}
                        <div class="flex justify-between items-center text-gray-600">
                            <span>PPN (11%)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="border-t border-dashed my-3"></div>
                        
                        {{-- TOTAL --}}
                        <div class="flex justify-between items-center text-2xl font-bold">
                            <span class="text-purple-600">Total</span>
                            <span class="text-purple-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- PAYMENT FORM --}}
                    <div class="space-y-4">
                        {{-- Nama Pelanggan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">👤 Nama Pelanggan</label>
                            <input type="text" 
                                   wire:model="customerName" 
                                   class="w-full px-4 py-3 border-2 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-gray-800"
                                   placeholder="Masukkan nama pelanggan">
                        </div>
                        
                        {{-- FORM MEMBER --}}
                        <div class="p-3 bg-purple-50 rounded-xl border border-purple-200">
                            <label class="block text-sm font-semibold text-purple-700 mb-2">👥 Member</label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       wire:model="memberPhone" 
                                       placeholder="Nomor telepon member"
                                       class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-800">
                                <button wire:click="checkMember" 
                                        type="button"
                                        class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition">
                                    Cek
                                </button>
                                @if($memberId)
                                    <button wire:click="clearMember" 
                                            type="button"
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                            
                            @if($memberId)
                                <div class="mt-2 text-sm">
                                    <p class="text-green-600">✅ Member: {{ $memberName }}</p>
                                    <p class="text-gray-600">⭐ Poin tersedia: {{ number_format($memberPoints) }}</p>
                                    
                                    <div class="mt-2 flex items-center gap-2">
                                        <input type="checkbox" wire:model.live="usePoints" id="usePoints" class="rounded">
                                        <label for="usePoints" class="text-sm text-gray-700">Gunakan poin (1 poin = Rp 100)</label>
                                    </div>
                                    
                                    @if($usePoints)
                                        <div class="mt-2">
                                            <input type="number" wire:model.live="pointsToUse" 
                                                   placeholder="Jumlah poin" 
                                                   max="{{ $memberPoints }}"
                                                   class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800">
                                            <p class="text-xs text-gray-500 mt-1">Maksimal poin: {{ number_format($memberPoints) }} (Rp {{ number_format($memberPoints * 100, 0, ',', '.') }})</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">💳 Metode Pembayaran</label>
                            <select wire:model="paymentMethod" 
                                    class="w-full px-4 py-3 border-2 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-gray-800">
                                <option value="cash">💵 Tunai</option>
                                <option value="qris">📱 QRIS</option>
                                <option value="debit">💳 Kartu Debit</option>
                                <option value="credit">💎 Kartu Kredit</option>
                            </select>
                        </div>

                        {{-- Jumlah Bayar --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">💰 Jumlah Bayar</label>
                            <input type="text" 
                                   id="paidAmountInput"
                                   wire:model.live="paidAmount" 
                                   class="w-full px-4 py-3 border-2 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition text-right text-xl font-bold text-gray-800"
                                   placeholder="0"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            
                            {{-- Tombol nominal cepat --}}
                            <div class="grid grid-cols-4 gap-2 mt-2">
                                <button type="button" onclick="setQuickAmount(50000)" class="quick-amount-btn px-2 py-1 bg-gray-100 rounded-lg text-xs text-gray-700">Rp 50k</button>
                                <button type="button" onclick="setQuickAmount(100000)" class="quick-amount-btn px-2 py-1 bg-gray-100 rounded-lg text-xs text-gray-700">Rp 100k</button>
                                <button type="button" onclick="setQuickAmount(200000)" class="quick-amount-btn px-2 py-1 bg-gray-100 rounded-lg text-xs text-gray-700">Rp 200k</button>
                                <button type="button" onclick="setQuickAmount(500000)" class="quick-amount-btn px-2 py-1 bg-gray-100 rounded-lg text-xs text-gray-700">Rp 500k</button>
                            </div>
                        </div>

                        {{-- Kembalian / Kekurangan --}}
                        @if($changeAmount > 0)
                            <div class="success-gradient rounded-xl p-4">
                                <div class="text-white text-sm">💰 Kembalian</div>
                                <div class="text-white text-3xl font-bold">Rp {{ number_format($changeAmount, 0, ',', '.') }}</div>
                            </div>
                        @elseif($paidAmount > 0 && $paidAmount < $total)
                            <div class="warning-gradient rounded-xl p-4">
                                <div class="text-white text-sm">⚠️ Kekurangan</div>
                                <div class="text-white text-3xl font-bold">Rp {{ number_format($total - $paidAmount, 0, ',', '.') }}</div>
                            </div>
                        @endif

                        {{-- Tombol Proses --}}
                        <button wire:click="processPayment" 
                                wire:loading.attr="disabled"
                                @if($paidAmount < $total) disabled @endif
                                class="w-full py-4 rounded-xl font-bold text-white transition-all transform hover:scale-105 shadow-lg
                                       {{ $paidAmount >= $total ? 'btn-gradient' : 'bg-gray-400 cursor-not-allowed' }}">
                            <span wire:loading.remove>💳 Proses Pembayaran</span>
                            <span wire:loading>⏳ Memproses...</span>
                        </button>
                        
                        {{-- Catatan --}}
                        <div>
                            <textarea wire:model="notes" placeholder="📝 Catatan (opsional)..." 
                                      class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-gray-800"
                                      rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    @if($showReceipt && $lastTransaction)
    <div x-data="{ show: true }" 
         x-show="show" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="show = false"></div>
            <div class="relative bg-white rounded-2xl max-w-md w-full p-8 shadow-2xl">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-4 animate-bounce">
                        <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil! 🎉</h3>
                    <p class="text-gray-500">Invoice: <strong>{{ $lastTransaction->invoice_number }}</strong></p>
                    
                    <div class="border-t-2 border-b-2 py-4 my-4 text-left bg-gray-50 rounded-lg p-4">
                        <div class="text-center mb-4">
                            <h2 class="font-bold text-xl text-purple-600">TOKO KAMI</h2>
                            <p class="text-xs text-gray-500">Jl. Contoh No. 123, Kota</p>
                        </div>
                        <div class="text-sm space-y-1">
                            @foreach($lastTransaction->details as $detail)
                                <div class="flex justify-between">
                                    <span>{{ $detail->product->name }} <span class="text-gray-400">x{{ $detail->quantity }}</span></span>
                                    <span class="text-gray-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t my-3"></div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>TOTAL</span>
                            <span class="text-purple-600">Rp {{ number_format($lastTransaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="printStruk({{ $lastTransaction->id }})" 
                                class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                            🖨️ Print Struk
                        </button>
                        <button @click="show = false; location.reload()" 
                                class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        function setQuickAmount(amount) {
            @this.set('paidAmount', amount);
            document.getElementById('paidAmountInput').value = amount;
        }
        
        function printStruk(transactionId) {
            let printWindow = window.open('/admin/cashier/print/' + transactionId, '_blank', 'width=400,height=600');
            if (printWindow) {
                printWindow.focus();
            } else {
                alert('Popup diblokir! Izinkan popup untuk mencetak struk.');
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            let barcodeBuffer = '';
            let barcodeTimeout;
            
            document.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    if (barcodeBuffer.length > 0) {
                        @this.call('scanBarcode', barcodeBuffer);
                        barcodeBuffer = '';
                        clearTimeout(barcodeTimeout);
                    }
                } else if (e.key.match(/[0-9]/)) {
                    barcodeBuffer += e.key;
                    clearTimeout(barcodeTimeout);
                    barcodeTimeout = setTimeout(() => {
                        barcodeBuffer = '';
                    }, 100);
                }
            });
            
            const paidInput = document.getElementById('paidAmountInput');
            if (paidInput) {
                paidInput.addEventListener('input', function(e) {
                    let value = this.value.replace(/[^0-9]/g, '');
                    if (value) {
                        @this.set('paidAmount', parseInt(value));
                    }
                });
            }
        });
    </script>
</x-filament::page>