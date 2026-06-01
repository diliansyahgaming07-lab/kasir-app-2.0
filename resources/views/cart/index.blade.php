<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - GearStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', monospace; }
        body { background: #0a0a0a; color: #e0e0e0; }
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .btn-primary { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); }
        .btn-primary:hover { transform: scale(1.05); }
        .navbar { background: rgba(10,10,10,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,180,216,0.2); }
        .cart-item { border-bottom: 1px solid #2d2d2d; }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar fixed top-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold">
            <span class="gradient-primary bg-clip-text text-transparent">GEAR</span><span class="text-white">STORE</span>
        </a>
        <a href="/produk" class="text-gray-300 hover:text-cyan-400">← Continue Shopping</a>
    </div>
</nav>

{{-- CART SECTION --}}
<section class="pt-32 pb-20">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-8">Shopping Cart</h1>
        
        @if(empty($cart))
            <div class="text-center py-20">
                <div class="text-8xl mb-4">🛒</div>
                <p class="text-gray-400 text-lg">Your cart is empty</p>
                <a href="/produk" class="btn-primary px-6 py-3 rounded-lg inline-block mt-4">Start Shopping</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    @foreach($cart as $id => $item)
                    <div class="cart-item py-6 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 gradient-primary rounded-xl flex items-center justify-center text-2xl">
                                ⚡
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ $item['name'] }}</h3>
                                <p class="text-cyan-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $item['quantity'] }}" min="1" 
                                   onchange="updateQuantity({{ $id }}, this.value)"
                                   class="w-16 px-2 py-1 bg-gray-800 border border-gray-700 rounded text-center">
                            <button onclick="removeItem({{ $id }})" class="text-red-500 hover:text-red-400">🗑️</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="gradient-card p-6 rounded-2xl h-fit border border-gray-800" style="background: linear-gradient(135deg, #1a1a1a 0%, #0f172a 100%);">
                    <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                    @php
                        $subtotal = 0;
                        foreach($cart as $item) {
                            $subtotal += $item['price'] * $item['quantity'];
                        }
                        $shipping = 0;
                        $total = $subtotal + $shipping;
                    @endphp
                    <div class="flex justify-between py-2">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span>Shipping</span>
                        <span class="text-green-500">Free</span>
                    </div>
                    <div class="border-t border-gray-700 my-4 pt-4">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-cyan-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <a href="/checkout" class="btn-primary w-full py-3 rounded-lg font-bold text-center block transition">
                        Proceed to Checkout →
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<script>
    function updateQuantity(productId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        }).then(() => location.reload());
    }
    
    function removeItem(productId) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        }).then(() => location.reload());
    }
</script>
</body>
</html>