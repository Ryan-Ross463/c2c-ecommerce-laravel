<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['category_id' => 1, 'name' => 'Electronics', 'description' => 'Electronic devices and accessories'],
            ['category_id' => 2, 'name' => 'Clothing', 'description' => 'Men\'s and women\'s apparel'],
            ['category_id' => 3, 'name' => 'Home & Garden', 'description' => 'Items for home and garden'],
            ['category_id' => 4, 'name' => 'Books', 'description' => 'Books and publications'],
            ['category_id' => 5, 'name' => 'Sports', 'description' => 'Sports equipment and accessories'],
            ['category_id' => 6, 'name' => 'Toys & Games', 'description' => 'Toys, games, and entertainment items'],
        ];
        
        DB::table('categories')->insert($categories);
        
        // Create users (both buyers and sellers)
        $users = [
            [
                'user_id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'John Seller',
                'email' => 'seller1@example.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'store_name' => 'John\'s Electronics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => 'Sarah Seller',
                'email' => 'seller2@example.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'store_name' => 'Sarah\'s Boutique',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'name' => 'Mike Buyer',
                'email' => 'buyer1@example.com',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'name' => 'Lisa Buyer',
                'email' => 'buyer2@example.com',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('users')->insert($users);
        
        // Create products
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
                'seller_id' => 2,
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
                'seller_id' => 2,
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
                'seller_id' => 3,
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
                'seller_id' => 3,
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
                'seller_id' => 2,
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
                'seller_id' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 7,
                'name' => 'Basketball',
                'description' => 'Official size and weight basketball for indoor or outdoor play.',
                'price' => 29.99,
                'stock' => 40,
                'image' => 'basketball.jpg',
                'category_id' => 5,
                'condition_type' => 'New',
                'seller_id' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 8,
                'name' => 'Board Game - "Strategy Masters"',
                'description' => 'Strategic board game for 2-4 players. Challenge your friends to a battle of wits!',
                'price' => 34.99,
                'stock' => 15,
                'image' => 'board_game.jpg',
                'category_id' => 6,
                'condition_type' => 'New',
                'seller_id' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 9,
                'name' => 'Laptop Pro 15"',
                'description' => 'High-performance laptop with 16GB RAM, 512GB SSD, and dedicated graphics card.',
                'price' => 1299.99,
                'stock' => 8,
                'image' => 'laptop.jpg',
                'category_id' => 1,
                'condition_type' => 'New',
                'seller_id' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 10,
                'name' => 'Garden Tool Set',
                'description' => 'Complete set of gardening tools including shovel, rake, and pruning shears.',
                'price' => 49.99,
                'stock' => 20,
                'image' => 'garden_tools.jpg',
                'category_id' => 3,
                'condition_type' => 'New',
                'seller_id' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('products')->insert($products);
        
        // Create product_categories (for many-to-many relationship)
        $product_categories = [];
        foreach ($products as $product) {
            $product_categories[] = [
                'product_id' => $product['product_id'],
                'category_id' => $product['category_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Add a few additional categories for some products
        $additional_categories = [
            ['product_id' => 1, 'category_id' => 6], // Smartphone also in Toys & Games
            ['product_id' => 5, 'category_id' => 2], // Coffee table also in Clothing (just for testing)
            ['product_id' => 7, 'category_id' => 6], // Basketball also in Toys & Games
        ];
        
        foreach ($additional_categories as $category) {
            $product_categories[] = [
                'product_id' => $category['product_id'],
                'category_id' => $category['category_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('product_categories')->insert($product_categories);
    }
}
