<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories', 'status' => 'active'],
            ['name' => 'Clothing', 'description' => 'Men\'s, women\'s, and children\'s clothing', 'status' => 'active'],
            ['name' => 'Home & Garden', 'description' => 'Items for home and garden', 'status' => 'active'],
            ['name' => 'Books', 'description' => 'Books, magazines, and publications', 'status' => 'active'],
            ['name' => 'Toys & Games', 'description' => 'Toys, games, and hobbies', 'status' => 'active'],
            ['name' => 'Sports', 'description' => 'Sports equipment and accessories', 'status' => 'active'],
            ['name' => 'Beauty', 'description' => 'Beauty products and cosmetics', 'status' => 'active'],
            ['name' => 'Automotive', 'description' => 'Car parts and accessories', 'status' => 'active']
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
