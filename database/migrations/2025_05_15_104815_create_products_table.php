<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name', 255);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('image', 255)->default('default.jpg');
            $table->unsignedBigInteger('category_id');
            $table->enum('condition_type', ['New', 'Like New', 'Good', 'Fair', 'Poor']);
            $table->unsignedBigInteger('seller_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->foreign('seller_id')->references('user_id')->on('users');
            
            $table->index('category_id');
            $table->index('seller_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};