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
        Schema::create('telco', function (Blueprint $table) {
            $table->id();
            $table->string('contract_id')->nullable();
            $table->string('payment_mode');
            $table->string('contract_type');
            $table->string('order_id');
            $table->string('status');
            $table->date('registration_date')->nullable();
            $table->date('activation_date')->nullable();
            $table->string('scenario')->nullable();
            $table->string('base_product_name');
            $table->string('supervisor_firstname')->nullable();
            $table->integer('commission')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telco');
    }
};
