<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;

// Customer Auth (Register, Login, Logout)
Route::get('/register-customer', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/register-customer', [CustomerAuthController::class, 'register']);
Route::get('/login-customer', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/login-customer', [CustomerAuthController::class, 'login']);
Route::post('/logout-customer', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// Halaman Frontend (Toko Online)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Halaman yang butuh login customer
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', function () {
        return view('checkout.success');
    })->name('checkout.success');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/orders', function () {
        return view('orders.index');
    })->name('orders.index');
});

// Profile Customer (dari Breeze) - hanya untuk customer yang login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API endpoints for frontend
Route::get('/api/products', [ProductController::class, 'apiIndex']);
Route::get('/api/cart/count', function () {
    $cart = session()->get('cart', []);
    $count = array_sum(array_column($cart, 'quantity'));
    return response()->json(['count' => $count]);
});

// API Orders (butuh login)
Route::get('/api/orders', function () {
    $user = auth()->user();
    if (!$user) {
        return response()->json([]);
    }
    $orders = App\Models\Transaction::where('user_id', $user->id)
        ->with('details.product')
        ->orderBy('created_at', 'desc')
        ->get();
    
    return response()->json($orders->map(function($order) {
        return [
            'id' => $order->id,
            'invoice_number' => $order->invoice_number,
            'date' => $order->created_at->format('d/m/Y H:i'),
            'total' => $order->total_amount,
            'status' => $order->order_status ?? 'pending',
            'items' => $order->details->map(function($detail) {
                return [
                    'name' => $detail->product->name,
                    'quantity' => $detail->quantity,
                    'subtotal' => $detail->subtotal,
                ];
            }),
        ];
    }));
})->middleware('auth');

// Auth (login, register, logout) dari Breeze (untuk admin)
require __DIR__.'/auth.php';