<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - FoodHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', monospace; }
        body { background: #0a0a0a; color: #e0e0e0; }
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .btn-primary { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); }
        .btn-primary:hover { transform: scale(1.05); }
        .btn-outline { border: 2px solid #00b4d8; }
        .btn-outline:hover { background: #00b4d8; color: #0a0a0a; }
        .navbar { background: rgba(10,10,10,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,180,216,0.2); }
        .quantity-btn:active { transform: scale(0.95); }
    </style>
</head>
<body>

<nav class="navbar fixed top-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold">
            <span class="gradient-primary bg-clip-text text-transparent">FOOD</span><span class="text-white">HUB</span>
        </a>
        <div class="flex items-center gap-6">
            <a href="/produk" class="text-gray-300 hover:text-cyan-400">← Back</a>
            <a href="/cart" class="relative">
                🛒
                <span id="cartCount" class="absolute -top-2 -right-3 bg-cyan-500 text-black text-xs rounded-full px-1.5">0</span>
            </a>
        </div>
    </div>
</nav>

<section class="pt-32 pb-20">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="gradient-primary rounded-2xl h-96 flex items-center justify-center">
                <div class="text-8xl">{{ $product->category->icon ?? '🍽️' }}</div>
            </div>
            
            <div>
                <span class="text-cyan-400 text-sm">{{ $product->category->name }}</span>
                <h1 class="text-4xl font-bold mt-2">{{ $product->name }}</h1>
                <p class="text-3xl font-bold text-cyan-400 mt-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-gray-400 mt-4">{{ $product->description ?? 'Produk berkualitas tinggi dengan harga terbaik.' }}</p>
                <div class="mt-6">
                    <span class="text-gray-400">Stok:</span>
                    <span class="text-green-500 ml-2">{{ $product->stock }} tersedia</span>
                </div>
                <div class="flex gap-4 mt-8">
                    <div class="flex items-center gap-3 bg-gray-800 rounded-full p-1">
                        <button onclick="decrement()" class="w-10 h-10 rounded-full bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white">-</button>
                        <span id="qty" class="w-12 text-center font-bold text-xl">1</span>
                        <button onclick="increment()" class="w-10 h-10 rounded-full bg-green-500/20 text-green-400 hover:bg-green-500 hover:text-white">+</button>
                    </div>
                    <button onclick="addToCart()" class="btn-primary px-8 py-3 rounded-lg font-bold transition">+ Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let quantity = 1;
    function increment() { quantity++; document.getElementById('qty').innerText = quantity; }
    function decrement() { if(quantity>1){ quantity--; document.getElementById('qty').innerText = quantity; } }
    
    function addToCart() {
        fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ product_id: {{ $product->id }}, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => { if(data.success) { updateCartCount(); alert('✓ Added to cart!'); } });
    }
    
    function updateCartCount() {
        fetch('/api/cart/count').then(res => res.json()).then(data => {
            document.getElementById('cartCount').innerText = data.count;
        });
    }
    updateCartCount();
</script>
</body>
</html>