<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - FoodHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', monospace; }
        body { background: #0a0a0a; color: #e0e0e0; }
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .navbar { background: rgba(10,10,10,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,180,216,0.2); }
        .contact-card { background: linear-gradient(135deg, #1a1a1a 0%, #0f172a 100%); transition: all 0.3s ease; }
        .contact-card:hover { transform: translateY(-5px); border-color: #00b4d8; }
    </style>
</head>
<body>

<nav class="navbar fixed top-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="flex items-center">
            <span class="text-3xl mr-2">🍔</span>
            <span class="text-2xl font-bold">FOOD<span class="text-cyan-400">HUB</span></span>
        </a>
        <div class="hidden md:flex items-center gap-8">
            <a href="/" class="text-gray-300 hover:text-cyan-400 transition">HOME</a>
            <a href="/produk" class="text-gray-300 hover:text-cyan-400 transition">MENU</a>
            @auth <a href="/orders" class="text-gray-300 hover:text-cyan-400 transition">📦 PESANAN</a> @endauth
            <a href="/about" class="text-gray-300 hover:text-cyan-400 transition">TENTANG</a>
            <a href="/contact" class="text-cyan-400 transition font-semibold">KONTAK</a>
        </div>
        <div class="flex items-center gap-4">
            <a href="/cart" class="relative">
                <svg class="w-6 h-6 text-gray-300 hover:text-cyan-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 18v3"></path>
                </svg>
                <span id="cartCount" class="absolute -top-2 -right-3 bg-cyan-500 text-black text-xs font-bold rounded-full px-1.5 py-0.5">0</span>
            </a>
            @auth
                <div class="relative group">
                    <button class="flex items-center gap-2 text-gray-300 hover:text-cyan-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <div class="absolute right-0 w-48 bg-gray-900 rounded-xl shadow-xl hidden group-hover:block border border-gray-800">
                        <a href="/dashboard" class="block px-4 py-2 hover:bg-cyan-500/20">Dashboard</a>
                        <a href="/orders" class="block px-4 py-2 hover:bg-cyan-500/20">Pesanan Saya</a>
                        <div class="border-t border-gray-700 my-1"></div>
                        <form method="POST" action="{{ Auth::user()->role == 'admin' ? '/logout' : '/logout-customer' }}">
                            @csrf
                            <button class="block w-full text-left px-4 py-2 hover:bg-cyan-500/20">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/login-customer" class="text-gray-300 hover:text-cyan-400">Login</a>
                <a href="/register-customer" class="btn-primary px-4 py-2 rounded-lg text-white">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<section class="pt-32 pb-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold">Hubungi <span class="text-cyan-400">Kami</span></h1>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Ada pertanyaan atau ingin bermitra? Silakan hubungi kami melalui kontak di bawah.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="contact-card p-6 rounded-2xl border border-gray-800 text-center">
                <div class="text-4xl mb-3">📍</div>
                <h3 class="text-xl font-bold mb-2">Alamat</h3>
                <p class="text-gray-400">Jl. Kuliner No. 88,<br>Kota Foodie, 12345</p>
            </div>
            <div class="contact-card p-6 rounded-2xl border border-gray-800 text-center">
                <div class="text-4xl mb-3">📞</div>
                <h3 class="text-xl font-bold mb-2">Telepon</h3>
                <p class="text-gray-400">+62 812 3456 7890</p>
            </div>
            <div class="contact-card p-6 rounded-2xl border border-gray-800 text-center">
                <div class="text-4xl mb-3">✉️</div>
                <h3 class="text-xl font-bold mb-2">Email</h3>
                <p class="text-gray-400">hello@foodhub.com</p>
            </div>
            <div class="contact-card p-6 rounded-2xl border border-gray-800 text-center">
                <div class="text-4xl mb-3">🕒</div>
                <h3 class="text-xl font-bold mb-2">Jam Operasional</h3>
                <p class="text-gray-400">Setiap hari<br>09.00 - 21.00 WIB</p>
            </div>
        </div>
    </div>
</section>

<footer class="gradient-dark border-t border-gray-800 py-12 mt-12">
    <div class="container mx-auto px-6 text-center text-gray-500 text-sm">
        <p>&copy; 2026 FoodHub. All rights reserved.</p>
    </div>
</footer>

<script>
    fetch('/api/cart/count').then(res => res.json()).then(data => {
        document.getElementById('cartCount').innerText = data.count;
    });
</script>
</body>
</html>