<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add missing profile fields
            $table->string('address')->nullable()->after('city');
            $table->text('bio')->nullable()->after('address');
            $table->text('business_description')->nullable()->after('business_name');
            
            // Rename profile_picture to profile_image for consistency
            $table->renameColumn('profile_picture', 'profile_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn(['address', 'bio', 'business_description']);
            
            // Rename back to original name
            $table->renameColumn('profile_image', 'profile_picture');
        });
    }
};
