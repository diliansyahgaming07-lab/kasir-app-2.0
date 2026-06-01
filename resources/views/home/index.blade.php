<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GearStore - Premium Equipment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', 'Inter', sans-serif; }
        body { background: #0a0a0a; color: #e0e0e0; }
        
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .gradient-dark { background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); }
        .gradient-card { background: linear-gradient(135deg, #1a1a1a 0%, #0f172a 100%); }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes glow { 0%,100% { box-shadow: 0 0 20px rgba(0,180,216,0.3); } 50% { box-shadow: 0 0 40px rgba(0,180,216,0.6); } }
        @keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(100px); } to { opacity: 1; transform: translateX(0); } }
        
        .animate-fadeUp { animation: fadeInUp 0.6s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        
        .product-card { transition: all 0.4s cubic-bezier(0.175,0.885,0.32,1.275); animation: fadeInUp 0.5s ease-out; }
        .product-card:hover { transform: translateY(-10px) scale(1.02); box-shadow: 0 25px 40px -12px rgba(0,180,216,0.3); border-color: #00b4d8; }
        
        .navbar { background: rgba(10,10,10,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,180,216,0.2); }
        
        .btn-primary { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); transition: all 0.3s ease; }
        .btn-primary:hover { transform: scale(1.05); box-shadow: 0 10px 25px -5px rgba(0,180,216,0.5); }
        
        .btn-outline { border: 2px solid #00b4d8; transition: all 0.3s ease; }
        .btn-outline:hover { background: #00b4d8; color: #0a0a0a; transform: scale(1.05); }
        
        .filter-btn.active { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); color: white !important; }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #00b4d8, #0077b6); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #00b4d8; }
        
        ::selection { background: #00b4d8; color: #0a0a0a; }
        
        .notification { position: fixed; top: 80px; right: 20px; z-index: 1000; padding: 12px 24px; border-radius: 12px; font-weight: 600; animation: slideInRight 0.3s ease-out; }
        .notification-success { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .notification-error { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
        
        .fixed-notification { position: fixed; top: 90px; right: 20px; z-index: 9999; padding: 10px 20px; border-radius: 12px; font-weight: 600; animation: slideInRight 0.3s ease-out; font-size: 14px; }
        .fixed-success { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .fixed-error { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
    </style>
</head>
<body class="bg-black">

{{-- SESSION NOTIFICATION --}}
@if(session('success'))
    <div class="fixed-notification fixed-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="fixed-notification fixed-error">✗ {{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="fixed-notification fixed-error">✗ {{ $errors->first() }}</div>
@endif

{{-- NAVBAR --}}
<nav class="navbar fixed top-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold flex items-center gap-2">
            <span class="text-3xl">⚡</span>
            <span class="gradient-primary bg-clip-text text-transparent">GEAR<span class="text-white">STORE</span></span>
        </a>
        
        <div class="hidden md:flex items-center gap-8">
            <a href="/" class="text-gray-300 hover:text-cyan-400 transition">HOME</a>
            <a href="/produk" class="text-gray-300 hover:text-cyan-400 transition">PRODUCTS</a>
            @auth <a href="/orders" class="text-gray-300 hover:text-cyan-400 transition">📦 ORDERS</a> @endauth
            <a href="/about" class="text-gray-300 hover:text-cyan-400 transition">ABOUT</a>
            <a href="/contact" class="text-gray-300 hover:text-cyan-400 transition">CONTACT</a>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="/cart" class="relative">
                <svg class="w-6 h-6 text-gray-300 hover:text-cyan-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 18v3"></path>
                </svg>
                <span id="cartCount" class="absolute -top-2 -right-3 bg-cyan-500 text-black text-xs font-bold rounded-full px-1.5 py-0.5 min-w-[20px] text-center">0</span>
            </a>
            @auth
                <div class="relative group">
                    <button class="flex items-center gap-2 text-gray-300 hover:text-cyan-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <div class="absolute right-0 w-56 bg-gradient-dark rounded-xl shadow-xl hidden group-hover:block border border-gray-800">
                        @if(Auth::user()->role == 'admin')
                            <a href="/admin" class="block px-4 py-2 hover:bg-cyan-500/20 rounded-t-xl transition">👑 Admin Panel</a>
                        @endif
                        <a href="/orders" class="block px-4 py-2 hover:bg-cyan-500/20 transition">📦 My Orders</a>
                        <a href="/profile" class="block px-4 py-2 hover:bg-cyan-500/20 transition">⚙️ Profile</a>
                        <div class="border-t border-gray-700 my-1"></div>
                        <form method="POST" action="{{ Auth::user()->role == 'admin' ? '/logout' : '/logout-customer' }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-cyan-500/20 rounded-b-xl transition">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/login-customer" class="text-gray-300 hover:text-cyan-400 transition px-3 py-1">Login</a>
                <a href="/register-customer" class="btn-primary px-4 py-2 rounded-lg font-semibold text-white transition">Register</a>
            @endauth
        </div>
    </div>
</nav>

{{-- HERO SECTION --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-dark"></div>
    <div class="absolute top-20 left-10 w-72 h-72 bg-cyan-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    
    <div class="relative container mx-auto px-6 text-center z-10">
        <div class="animate-float"><span class="text-cyan-400 font-semibold tracking-wider text-sm md:text-base">⚡ PREMIUM GEAR ⚡</span></div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mt-4 leading-tight">
            <span class="gradient-primary bg-clip-text text-transparent">Level Up</span>
            <span class="text-white"> Your</span><br>
            <span class="text-white">Performance</span>
        </h1>
        <p class="text-base md:text-xl text-gray-400 mt-6 max-w-2xl mx-auto">Equipment premium untuk mendukung aktivitas Anda. Kualitas terbaik, harga terjangkau.</p>
        <div class="flex flex-wrap gap-4 justify-center mt-8">
            <a href="/produk" class="btn-primary px-6 md:px-8 py-2 md:py-3 rounded-lg font-semibold text-white transition text-sm md:text-base">🚀 Shop Now</a>
            <a href="#products" class="btn-outline px-6 md:px-8 py-2 md:py-3 rounded-lg font-semibold text-cyan-400 transition text-sm md:text-base">View Collection</a>
        </div>
        
        <div class="grid grid-cols-3 gap-4 md:gap-8 max-w-2xl mx-auto mt-12 md:mt-16">
            <div><div class="text-2xl md:text-3xl font-bold text-cyan-400">500+</div><div class="text-xs md:text-sm text-gray-500">Products</div></div>
            <div><div class="text-2xl md:text-3xl font-bold text-cyan-400">10k+</div><div class="text-xs md:text-sm text-gray-500">Happy Customers</div></div>
            <div><div class="text-2xl md:text-3xl font-bold text-cyan-400">24/7</div><div class="text-xs md:text-sm text-gray-500">Support</div></div>
        </div>
    </div>
    
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

{{-- FEATURED PRODUCTS --}}
<section id="products" class="py-20 gradient-dark">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-cyan-400 font-semibold tracking-wider">🔥 HOT DEALS 🔥</span>
            <h2 class="text-4xl font-bold mt-2">Featured Products</h2>
            <p class="text-gray-400 mt-4">Produk pilihan dengan kualitas terbaik</p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <button onclick="filterProducts('all', this)" class="filter-btn active px-5 py-2 rounded-full bg-cyan-500 text-white font-semibold transition">All</button>
            @foreach($categories as $category)
                <button onclick="filterProducts('{{ $category->id }}', this)" class="filter-btn px-5 py-2 rounded-full bg-gray-800 text-gray-300 hover:bg-cyan-500 hover:text-white transition">
                    {{ $category->icon ?? '📦' }} {{ $category->name }}
                </button>
            @endforeach
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="productsGrid">
            <div class="text-center py-20 col-span-full"><div class="inline-block w-8 h-8 border-4 border-cyan-400 border-t-transparent rounded-full animate-spin"></div><p class="text-gray-400 mt-4">Loading products...</p></div>
        </div>
        
        <div class="text-center mt-12">
            <a href="/produk" class="btn-outline px-8 py-3 rounded-lg font-semibold text-cyan-400 transition inline-block">View All Products →</a>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="gradient-card p-6 rounded-2xl text-center border border-gray-800 hover:border-cyan-400 transition"><div class="text-5xl mb-4">🚚</div><h3 class="text-xl font-bold mb-2">Free Shipping</h3><p class="text-gray-400">Gratis ongkir untuk pembelian di atas Rp 500.000</p></div>
            <div class="gradient-card p-6 rounded-2xl text-center border border-gray-800 hover:border-cyan-400 transition"><div class="text-5xl mb-4">🛡️</div><h3 class="text-xl font-bold mb-2">Secure Payment</h3><p class="text-gray-400">100% payment aman dan terjamin</p></div>
            <div class="gradient-card p-6 rounded-2xl text-center border border-gray-800 hover:border-cyan-400 transition"><div class="text-5xl mb-4">🔄</div><h3 class="text-xl font-bold mb-2">14 Days Return</h3><p class="text-gray-400">Garansi uang kembali jika produk rusak</p></div>
        </div>
    </div>
</section>

{{-- CTA SECTION --}}
<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 gradient-primary opacity-10"></div>
    <div class="container mx-auto px-6 text-center relative">
        <h2 class="text-4xl font-bold mb-4">Ready to Level Up?</h2>
        <p class="text-gray-400 mb-8 max-w-2xl mx-auto">Dapatkan produk premium dengan harga terbaik. Limited stock!</p>
        <div class="flex gap-4 justify-center">
            <a href="/produk" class="btn-primary px-8 py-4 rounded-lg font-bold text-lg inline-block">🔥 Shop Now 🔥</a>
            @auth <a href="/orders" class="btn-outline px-8 py-4 rounded-lg font-bold text-lg inline-block text-cyan-400">📦 My Orders</a> @endauth
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="gradient-dark border-t border-gray-800 py-12">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div><h3 class="text-xl font-bold mb-4"><span class="gradient-primary bg-clip-text text-transparent">GEAR</span><span class="text-white">STORE</span></h3><p class="text-gray-500 text-sm">Premium equipment for modern warriors.</p></div>
            <div><h4 class="font-semibold mb-3">Quick Links</h4><ul class="space-y-2 text-gray-500 text-sm"><li><a href="/" class="hover:text-cyan-400 transition">Home</a></li><li><a href="/produk" class="hover:text-cyan-400 transition">Products</a></li><li><a href="/orders" class="hover:text-cyan-400 transition">My Orders</a></li></ul></div>
            <div><h4 class="font-semibold mb-3">Support</h4><ul class="space-y-2 text-gray-500 text-sm"><li><a href="/faq" class="hover:text-cyan-400 transition">FAQ</a></li><li><a href="/shipping" class="hover:text-cyan-400 transition">Shipping</a></li><li><a href="/returns" class="hover:text-cyan-400 transition">Returns</a></li></ul></div>
            <div><h4 class="font-semibold mb-3">Follow Us</h4><div class="flex gap-4"><a href="#" class="text-gray-500 hover:text-cyan-400 transition text-2xl">📷</a><a href="#" class="text-gray-500 hover:text-cyan-400 transition text-2xl">🐦</a><a href="#" class="text-gray-500 hover:text-cyan-400 transition text-2xl">📘</a><a href="#" class="text-gray-500 hover:text-cyan-400 transition text-2xl">🎵</a></div></div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-500 text-sm"><p>&copy; 2026 GearStore. All rights reserved.</p></div>
    </div>
</footer>

<script>
    let currentCategory = 'all';
    let allProducts = [];

    fetch('/api/products').then(res => res.json()).then(data => { allProducts = data; displayProducts(allProducts.slice(0, 4)); });

    function displayProducts(products) {
        const grid = document.getElementById('productsGrid');
        if (products.length === 0) { grid.innerHTML = '<div class="text-center py-20 col-span-full">Tidak ada produk di kategori ini</div>'; return; }
        grid.innerHTML = products.map(product => `
            <div class="product-card gradient-card rounded-2xl overflow-hidden cursor-pointer border border-gray-800">
                <div class="h-48 gradient-primary flex items-center justify-center relative">
                    <div class="text-6xl">${product.category_icon || '⚡'}</div>
                    <div class="absolute top-2 right-2 bg-black/50 rounded-full px-2 py-1 text-xs">${product.category_name}</div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg text-white">${product.name}</h3>
                    <p class="text-cyan-400 font-bold text-xl mt-2">Rp ${product.price.toLocaleString('id-ID')}</p>
                    <div class="flex gap-2 mt-4">
                        <button onclick="addToCart(${product.id})" class="flex-1 btn-primary py-2 rounded-lg text-sm font-semibold transition">+ Cart</button>
                        <a href="/produk/${product.id}" class="flex-1 btn-outline py-2 rounded-lg text-sm font-semibold text-cyan-400 text-center transition">Detail</a>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function filterProducts(categoryId, btn) {
        currentCategory = categoryId;
        document.querySelectorAll('.filter-btn').forEach(button => { button.classList.remove('active', 'bg-cyan-500', 'text-white'); button.classList.add('bg-gray-800', 'text-gray-300'); });
        btn.classList.add('active', 'bg-cyan-500', 'text-white'); btn.classList.remove('bg-gray-800', 'text-gray-300');
        let filtered = categoryId !== 'all' ? allProducts.filter(p => p.category_id == categoryId) : allProducts;
        displayProducts(filtered.slice(0, 4));
    }
    
    function addToCart(productId) {
        fetch('/cart/add', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ product_id: productId, quantity: 1 }) })
        .then(res => res.json()).then(data => { if (data.success) { updateCartCount(); showNotification('✓ Product added to cart!', 'success'); } });
    }
    
    function updateCartCount() { fetch('/api/cart/count').then(res => res.json()).then(data => { document.getElementById('cartCount').innerText = data.count; }); }
    function showNotification(message, type) { const notification = document.createElement('div'); notification.className = `notification notification-${type}`; notification.innerHTML = message; document.body.appendChild(notification); setTimeout(() => notification.remove(), 3000); }
    updateCartCount();
    
    setTimeout(() => { const notif = document.querySelector('.fixed-notification'); if (notif) notif.style.display = 'none'; }, 4000);
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => { anchor.addEventListener('click', function (e) { e.preventDefault(); const target = document.querySelector(this.getAttribute('href')); if (target) target.scrollIntoView({ behavior: 'smooth' }); }); });
</script>
</body>
</html>