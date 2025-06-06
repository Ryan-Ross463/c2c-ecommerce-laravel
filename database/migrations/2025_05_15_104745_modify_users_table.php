<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'user_id');
            $table->string('phone', 20)->nullable();
            $table->string('business_name', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->unsignedBigInteger('role_id')->after('email')->default(1);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->boolean('email_verified')->default(false);
            $table->string('verification_token')->nullable();
            $table->string('reset_token')->nullable();
            $table->dateTime('reset_token_expiry')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('profile_picture')->nullable();
            $table->dateTime('last_login')->nullable();
            
            $table->foreign('role_id')->references('role_id')->on('roles');
        });
    }

   
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'phone', 'business_name', 'city', 'role_id', 'status', 
                'email_verified', 'verification_token', 'reset_token', 
                'reset_token_expiry', 'dob', 'gender', 'profile_picture', 'last_login'
            ]);
            $table->renameColumn('user_id', 'id');
        });
    }
};