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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_id')->unsigned();
            $table->string('userfield_agent');
            $table->string('agent')->nullable();
            $table->string('status');
            $table->string('payment_type');
            $table->string('contract_type')->nullable();
            $table->string('product_type')->nullable();
            $table->string('bill', 30);
            $table->string('b2c_b2b', 20)->nullable();
            $table->timestamp('inscription_date')->default(now());
            $table->decimal('consumption', 10, 2)->nullable();
            $table->integer('commission')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
