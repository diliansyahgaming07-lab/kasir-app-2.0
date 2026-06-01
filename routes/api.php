<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;

Route::get('/products', function () {
    $products = Product::with('category')->get();
    return response()->json($products->map(function($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'category_icon' => $product->category->icon ?? '📦',
            'category_name' => $product->category->name ?? 'Unknown',
        ];
    }));
});

Route::get('/cart/count', function () {
    $cart = session()->get('cart', []);
    $count = array_sum(array_column($cart, 'quantity'));
    return response()->json(['count' => $count]);
});