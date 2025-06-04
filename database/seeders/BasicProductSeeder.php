<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class BasicProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, check if the products table is empty
        if (DB::table('products')->count() > 0) {
            echo "Products table already has data. Skipping seed.\n";
            return;
        }
        
        // Create some basic products
        $products = [
            [
                'product_id' => 1,
                'name' => 'Smartphone XS Plus',
                'description' => 'Latest model smartphone with high-resolution camera and fast processor.',
                'price' => 699.99,
                'stock' => 25,
                'image' => 'smartphone.jpg',
                'category_id' => 1,
                'condition_type' => 'New',
                'seller_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'name' => 'Wireless Earbuds',
                'description' => 'Bluetooth earbuds with noise cancellation and long battery life.',
                'price' => 89.99,
                'stock' => 50,
                'image' => 'earbuds.jpg',
                'category_id' => 1,
                'condition_type' => 'New',
                'seller_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'name' => 'Men\'s Cotton T-Shirt',
                'description' => 'Comfortable, casual cotton t-shirt. Available in multiple sizes.',
                'price' => 19.99,
                'stock' => 100,
                'image' => 'tshirt.jpg',
                'category_id' => 2,
                'condition_type' => 'New',
                'seller_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 4,
                'name' => 'Women\'s Running Shoes',
                'description' => 'Lightweight running shoes with cushioned soles for maximum comfort.',
                'price' => 79.99,
                'stock' => 30,
                'image' => 'running_shoes.jpg',
                'category_id' => 2,
                'condition_type' => 'New',
                'seller_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 5,
                'name' => 'Coffee Table',
                'description' => 'Modern wooden coffee table for your living room.',
                'price' => 149.99,
                'stock' => 10,
                'image' => 'coffee_table.jpg',
                'category_id' => 3,
                'condition_type' => 'Like New',
                'seller_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 6,
                'name' => 'Novel - "The Mystery of the Lost Key"',
                'description' => 'Thrilling mystery novel about a detective solving the case of a missing antique key.',
                'price' => 12.99,
                'stock' => 75,
                'image' => 'mystery_book.jpg',
                'category_id' => 4,
                'condition_type' => 'Good',
                'seller_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        // Insert products
        DB::table('products')->insert($products);
        
        echo "Basic products seeded successfully!\n";
    }
}
