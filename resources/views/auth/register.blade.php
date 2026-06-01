<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0a0a0a; color: #e0e0e0; }
        .gradient-primary { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0077b6 100%); }
        .btn-primary { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); }
        .btn-primary:hover { transform: scale(1.05); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-gray-900 rounded-2xl p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold">📝 Register</h1>
            <p class="text-gray-400 mt-2">Buat akun customer baru</p>
        </div>
        
        <form method="POST" action="/register-customer">
            @csrf
            <div class="mb-4">
                <label class="block text-sm mb-2">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2">No. Telepon</label>
                <input type="text" name="phone" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2">Alamat</label>
                <textarea name="address" required rows="2" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg">
            </div>
            <div class="mb-6">
                <label class="block text-sm mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg">
            </div>
            <button type="submit" class="btn-primary w-full py-3 rounded-lg font-bold transition">Register</button>
        </form>
        
        <div class="text-center mt-4">
            <a href="/login-customer" class="text-cyan-400 hover:underline">Sudah punya akun? Login</a>
        </div>
    </div>
</body>
</html>