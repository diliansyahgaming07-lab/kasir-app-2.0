<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - FoodHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', monospace; }
        body { background: #0a0a0a; color: #e0e0e0; }
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .navbar { background: rgba(10,10,10,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,180,216,0.2); }
        .order-card { background: linear-gradient(135deg, #1a1a1a 0%, #0f172a 100%); transition: all 0.3s ease; }
        .order-card:hover { transform: translateY(-5px); border-color: #00b4d8; }
        .status-proses { background: #f59e0b; }
        .status-dikirim { background: #3b82f6; }
        .status-diterima { background: #10b981; }
        .btn-primary { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); transition: all 0.3s ease; }
        .btn-primary:hover { transform: scale(1.05); }
    </style>
</head>
<body>

<nav class="navbar fixed top-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold">
            <span class="gradient-primary bg-clip-text text-transparent">FOOD</span><span class="text-white">HUB</span>
        </a>
        <div class="flex items-center gap-6">
            <a href="/cart" class="text-gray-300 hover:text-cyan-400">🛒</a>
            <a href="/admin" class="text-gray-300 hover:text-cyan-400">👑 Admin Panel</a>
            <a href="/dashboard" class="text-gray-300 hover:text-cyan-400">📊 Dashboard</a>
            <form method="POST" action="/logout">
                @csrf
                <button class="text-gray-300 hover:text-cyan-400">Logout</button>
            </form>
        </div>
    </div>
</nav>

<section class="pt-32 pb-20">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-8">📦 My Orders</h1>
        
        <div class="grid grid-cols-1 gap-6" id="ordersGrid">
            <div class="text-center py-20">
                <div class="inline-block w-8 h-8 border-4 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-gray-400 mt-4">Loading orders...</p>
            </div>
        </div>
    </div>
</section>

<script>
    fetch('/api/orders')
        .then(res => res.json())
        .then(orders => {
            const grid = document.getElementById('ordersGrid');
            if (orders.length === 0) {
                grid.innerHTML = `
                    <div class="text-center py-20">
                        <div class="text-8xl mb-4">📭</div>
                        <p class="text-gray-400 text-lg">Belum ada pesanan</p>
                        <a href="/produk" class="btn-primary px-6 py-3 rounded-lg inline-block mt-4">Belanja Sekarang</a>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = orders.map(order => {
                let statusText = '';
                let statusColor = '';
                let statusProgress = '';
                
                if (order.status === 'proses') {
                    statusText = '🕐 Diproses';
                    statusColor = 'status-proses';
                    statusProgress = '30%';
                } else if (order.status === 'dikirim') {
                    statusText = '🚚 Dikirim';
                    statusColor = 'status-dikirim';
                    statusProgress = '70%';
                } else {
                    statusText = '✅ Diterima';
                    statusColor = 'status-diterima';
                    statusProgress = '100%';
                }
                
                return `
                    <div class="order-card border border-gray-800 rounded-2xl p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-gray-400 text-sm">Invoice</span>
                                <p class="font-bold text-lg">${order.invoice_number}</p>
                            </div>
                            <div>
                                <span class="text-gray-400 text-sm">Tanggal</span>
                                <p class="font-bold">${order.date}</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-800 my-4"></div>
                        
                        <div class="space-y-2 mb-4">
                            ${order.items.map(item => `
                                <div class="flex justify-between">
                                    <span>${item.name} <span class="text-gray-500">x${item.quantity}</span></span>
                                    <span>Rp ${item.subtotal.toLocaleString('id-ID')}</span>
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="border-t border-gray-800 my-4"></div>
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-400">Total</span>
                            <span class="text-2xl font-bold text-cyan-400">Rp ${order.total.toLocaleString('id-ID')}</span>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl p-4 mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm">Status Pesanan</span>
                                <span class="text-sm font-bold ${statusColor} px-3 py-1 rounded-full">${statusText}</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="${statusColor} h-2 rounded-full" style="width: ${statusProgress}"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-gray-500">
                                <span>Pesanan Dibuat</span>
                                <span>Dikirim</span>
                                <span>Selesai</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button onclick="trackOrder(${order.id})" class="flex-1 bg-gray-800 hover:bg-cyan-500/20 py-2 rounded-lg transition">📍 Track</button>
                            <button onclick="reorder(${order.id})" class="flex-1 btn-primary py-2 rounded-lg transition">🔄 Order Again</button>
                        </div>
                    </div>
                `;
            }).join('');
        });
    
    function trackOrder(orderId) {
        alert('Tracking untuk pesanan #' + orderId + '\nStatus: Sedang dalam pengiriman');
    }
    
    function reorder(orderId) {
        window.location.href = '/produk';
    }
</script>
</body>
</html>