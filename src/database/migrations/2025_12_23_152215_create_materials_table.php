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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_code')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('standard_price', 15, 2)->default(0);
            $table->decimal('lead_time', 10, 2)->default(0);
            $table->decimal('minimum_order_quantity', 15, 2)->default(0);
            $table->decimal('safety_stock', 15, 2)->default(0);
            $table->boolean('is_lot_managed')->default(false);
            $table->boolean('has_expiry_management')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
