<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - GearStore</title>
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
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,180,216,0.3); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: #00b4d8; border-radius: 10px; }
    </style>
</head>
<body>

<nav class="navbar fixed top-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold">
            <span class="gradient-primary bg-clip-text text-transparent">GEAR</span><span class="text-white">STORE</span>
        </a>
        <div class="flex items-center gap-6">
            <a href="/" class="text-gray-300 hover:text-cyan-400">HOME</a>
            <a href="/cart" class="relative text-gray-300 hover:text-cyan-400">
                🛒
                <span id="cartCount" class="absolute -top-2 -right-3 bg-cyan-500 text-black text-xs rounded-full px-1.5">0</span>
            </a>
            @auth
                <div class="relative group">
                    <button class="text-gray-300">{{ Auth::user()->name }}</button>
                    <div class="absolute right-0 w-48 bg-gray-900 rounded-xl shadow-xl hidden group-hover:block border border-gray-800">
                        <a href="/dashboard" class="block px-4 py-2 hover:bg-cyan-500/20">Dashboard</a>
                        <a href="/orders" class="block px-4 py-2 hover:bg-cyan-500/20">My Orders</a>
                        <div class="border-t border-gray-700 my-1"></div>
                        <form method="POST" action="{{ Auth::user()->role == 'admin' ? '/logout' : '/logout-customer' }}">
                            @csrf
                            <button class="block w-full text-left px-4 py-2 hover:bg-cyan-500/20">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/login-customer" class="text-gray-300 hover:text-cyan-400">Login</a>
                <a href="/register-customer" class="btn-primary px-4 py-2 rounded-lg text-white">Register</a>
            @endauth
        </div>
    </div>
</nav>

<section class="pt-32 pb-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold">All Products</h1>
            <p class="text-gray-400 mt-2">Temukan produk favorit Anda</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="productsGrid">
            <div class="text-center py-20 col-span-full">
                <div class="inline-block w-8 h-8 border-4 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-gray-400 mt-4">Loading products...</p>
            </div>
        </div>
    </div>
</section>

<script>
    fetch('/api/products')
        .then(res => res.json())
        .then(data => {
            const grid = document.getElementById('productsGrid');
            if (data.length === 0) {
                grid.innerHTML = '<div class="text-center py-20 col-span-full">Belum ada produk</div>';
                return;
            }
            grid.innerHTML = data.map(product => `
                <div class="product-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800">
                    <div class="h-40 gradient-primary flex items-center justify-center">
                        <div class="text-5xl">${product.category_icon || '⚡'}</div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg">${product.name}</h3>
                        <p class="text-cyan-400 font-bold text-xl mt-1">Rp ${product.price.toLocaleString('id-ID')}</p>
                        <div class="flex gap-2 mt-4">
                            <button onclick="addToCart(${product.id})" class="flex-1 btn-primary py-2 rounded-lg text-sm font-semibold">+ Cart</button>
                            <a href="/produk/${product.id}" class="flex-1 btn-outline py-2 rounded-lg text-sm font-semibold text-cyan-400 text-center">Detail</a>
                        </div>
                    </div>
                </div>
            `).join('');
        });
    
    function addToCart(productId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                alert('✓ Product added to cart!');
            }
        });
    }
    
    function updateCartCount() {
        fetch('/api/cart/count')
            .then(res => res.json())
            .then(data => {
                document.getElementById('cartCount').innerText = data.count;
            });
    }
    
    updateCartCount();
</script>
</body>
</html>