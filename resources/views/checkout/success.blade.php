<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - GearStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0a0a0a; color: #e0e0e0; }
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .btn-primary { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); }
        .btn-primary:hover { transform: scale(1.05); }
        .btn-outline { border: 2px solid #00b4d8; transition: all 0.3s ease; }
        .btn-outline:hover { background: #00b4d8; color: #0a0a0a; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce { animation: bounce 0.5s ease-in-out; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="text-center px-6">
        <div class="text-8xl mb-6 animate-bounce">🎉</div>
        <h1 class="text-4xl font-bold text-green-500 mb-4">Pesanan Berhasil!</h1>
        <p class="text-gray-400 mb-2">Terima kasih telah berbelanja di GearStore.</p>
        <p class="text-gray-400 mb-8">Pesanan Anda sedang menunggu konfirmasi admin.</p>
        
        <div class="bg-gray-800 rounded-2xl p-6 max-w-md mx-auto mb-8">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400">Status Pesanan:</span>
                <span class="text-yellow-500 font-bold">🕐 Menunggu Konfirmasi</span>
            </div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400">Metode Pembayaran:</span>
                <span class="text-cyan-400 font-bold">COD (Bayar di Tempat)</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2 mb-4">
                <div class="bg-yellow-500 h-2 rounded-full" style="width: 20%"></div>
            </div>
            <p class="text-sm text-gray-500">Admin akan segera mengkonfirmasi pesanan Anda</p>
        </div>
        
        <div class="flex gap-4 justify-center">
            <a href="/" class="btn-primary px-6 py-3 rounded-lg font-bold">🏠 Back to Home</a>
            <a href="/orders" class="btn-outline px-6 py-3 rounded-lg font-bold border-2 border-cyan-400 text-cyan-400">📦 My Orders</a>
        </div>
    </div>
</body>
</html>