<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewsAndSalesCountToProductsTable extends Migration
{
   
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
           
            $table->integer('views')->default(0)->after('status');
            
            $table->integer('sales_count')->default(0)->after('views');
        });
    }

    
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('views');
            $table->dropColumn('sales_count');
        });
    }
}