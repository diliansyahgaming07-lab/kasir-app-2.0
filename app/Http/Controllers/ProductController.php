<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }
    
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }
    
    // API endpoint for products (for frontend AJAX)
    public function apiIndex()
    {
        $products = Product::with('category')->get();
        return response()->json($products->map(function($product) {
            // Fix icon mapping
            $icon = $product->category->icon ?? '📦';
            // Map specific icons if needed
            $iconMap = [
                'Makanan' => '🍔',
                'Minuman' => '🥤',
                'Snack' => '🍿',
                'Rokok' => '🚬',
            ];
            
            $categoryName = $product->category->name ?? 'Lainnya';
            $displayIcon = $iconMap[$categoryName] ?? $icon;
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'category_id' => $product->category_id,
                'category_icon' => $displayIcon,
                'category_name' => $categoryName,
            ];
        }));
    }
}