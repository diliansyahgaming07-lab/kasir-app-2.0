<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil category yang sudah ada
        $makanan = Category::where('name', 'Makanan')->first();
        $minuman = Category::where('name', 'Minuman')->first();
        $snack = Category::where('name', 'Snack')->first();
        
        // Jika category belum ada, buat dulu
        if (!$makanan) {
            $makanan = Category::create(['name' => 'Makanan', 'icon' => '🍔']);
            $minuman = Category::create(['name' => 'Minuman', 'icon' => '🥤']);
            $snack = Category::create(['name' => 'Snack', 'icon' => '🍿']);
        }
        
        $products = [
            // Makanan
            ['name' => 'Nasi Goreng', 'barcode' => '899001', 'category_id' => $makanan->id, 'price' => 15000, 'stock' => 50],
            ['name' => 'Mie Goreng', 'barcode' => '899002', 'category_id' => $makanan->id, 'price' => 12000, 'stock' => 50],
            ['name' => 'Ayam Goreng', 'barcode' => '899003', 'category_id' => $makanan->id, 'price' => 18000, 'stock' => 40],
            ['name' => 'Nasi Uduk', 'barcode' => '899004', 'category_id' => $makanan->id, 'price' => 13000, 'stock' => 45],
            ['name' => 'Sate Ayam', 'barcode' => '899005', 'category_id' => $makanan->id, 'price' => 25000, 'stock' => 30],
            ['name' => 'Bakso', 'barcode' => '899006', 'category_id' => $makanan->id, 'price' => 15000, 'stock' => 60],
            
            // Minuman
            ['name' => 'Es Teh Manis', 'barcode' => '899007', 'category_id' => $minuman->id, 'price' => 5000, 'stock' => 100],
            ['name' => 'Es Jeruk', 'barcode' => '899008', 'category_id' => $minuman->id, 'price' => 7000, 'stock' => 100],
            ['name' => 'Kopi Hitam', 'barcode' => '899009', 'category_id' => $minuman->id, 'price' => 8000, 'stock' => 80],
            ['name' => 'Kopi Susu', 'barcode' => '899010', 'category_id' => $minuman->id, 'price' => 12000, 'stock' => 70],
            ['name' => 'Jus Alpukat', 'barcode' => '899011', 'category_id' => $minuman->id, 'price' => 15000, 'stock' => 40],
            ['name' => 'Air Mineral', 'barcode' => '899012', 'category_id' => $minuman->id, 'price' => 4000, 'stock' => 200],
            
            // Snack
            ['name' => 'Keripik Kentang', 'barcode' => '899013', 'category_id' => $snack->id, 'price' => 10000, 'stock' => 60],
            ['name' => 'Donat', 'barcode' => '899014', 'category_id' => $snack->id, 'price' => 8000, 'stock' => 45],
            ['name' => 'Roti Bakar', 'barcode' => '899015', 'category_id' => $snack->id, 'price' => 12000, 'stock' => 30],
            ['name' => 'Pisang Goreng', 'barcode' => '899016', 'category_id' => $snack->id, 'price' => 10000, 'stock' => 50],
            ['name' => 'Kentang Goreng', 'barcode' => '899017', 'category_id' => $snack->id, 'price' => 12000, 'stock' => 60],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
        
        $this->command->info('✅ ' . count($products) . ' produk berhasil ditambahkan!');
    }
}