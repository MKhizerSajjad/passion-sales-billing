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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('user_type')->default(2)->indexed(); // admin and vendor
            $table->string('first_name', 20);
            $table->string('last_name', 15);
            $table->string('picture')->nullable();
            $table->string('nic', 30)->unique()->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('dob')->nullable();
            $table->string('zipcode', 50)->nullable();
            $table->string('address')->nullable();
            $table->text('note')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
