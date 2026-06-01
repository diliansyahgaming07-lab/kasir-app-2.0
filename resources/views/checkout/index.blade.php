<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - GearStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', monospace; }
        body { background: #0a0a0a; color: #e0e0e0; }
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .btn-primary { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); }
        .btn-primary:hover { transform: scale(1.05); }
        .navbar { background: rgba(10,10,10,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,180,216,0.2); }
        .gradient-card { background: linear-gradient(135deg, #1a1a1a 0%, #0f172a 100%); }
    </style>
</head>
<body>

<nav class="navbar fixed top-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold">
            <span class="gradient-primary bg-clip-text text-transparent">GEAR</span><span class="text-white">STORE</span>
        </a>
        <a href="/cart" class="text-gray-300 hover:text-cyan-400">← Back to Cart</a>
    </div>
</nav>

<section class="pt-32 pb-20">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-8">Checkout</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="gradient-card rounded-2xl p-6 border border-gray-800">
                    <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                    @foreach($cart as $item)
                    <div class="flex justify-between py-3 border-b border-gray-800">
                        <div>
                            <span class="font-bold">{{ $item['name'] }}</span>
                            <span class="text-gray-400 text-sm ml-2">x{{ $item['quantity'] }}</span>
                        </div>
                        <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="gradient-card rounded-2xl p-6 border border-gray-800 h-fit">
                <h3 class="text-xl font-bold mb-4">Payment Details</h3>
                <div class="flex justify-between py-2">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span>PPN (11%)</span>
                    <span>Rp {{ number_format($subtotal * 0.11, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span>Shipping</span>
                    <span class="text-green-500">Free</span>
                </div>
                <div class="border-t border-gray-700 my-4 pt-4">
                    <div class="flex justify-between font-bold text-xl">
                        <span>Total</span>
                        <span class="text-cyan-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <form method="POST" action="/checkout/process">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm mb-2">📱 No. Telepon</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone ?? '' }}" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg" placeholder="08123456789">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm mb-2">📍 Alamat Lengkap</label>
                        <textarea name="shipping_address" required rows="3" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg" placeholder="Jl. Contoh No. 123, Jakarta">{{ Auth::user()->address ?? '' }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm mb-2">💳 Metode Pembayaran</label>
                        <select name="payment_method" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg">
                            <option value="cash">💵 Cash on Delivery (COD)</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="debit">💳 Kartu Debit</option>
                            <option value="credit">💎 Kartu Kredit</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm mb-2">📝 Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg" placeholder="Catatan untuk penjual..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary w-full py-3 rounded-lg font-bold transition">Confirm Order →</button>
                </form>
            </div>
        </div>
    </div>
</section>
</body>
</html>