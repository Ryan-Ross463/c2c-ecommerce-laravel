<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->string('address')->nullable()->after('city');
            $table->text('bio')->nullable()->after('address');
            $table->text('business_description')->nullable()->after('business_name');
            
            $table->renameColumn('profile_picture', 'profile_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->dropColumn(['address', 'bio', 'business_description']);
            
            $table->renameColumn('profile_image', 'profile_picture');
        });
    }
};