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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('standard_cost', 15, 2)->default(0);
            $table->decimal('standard_manufacturing_time', 10, 2)->default(0);
            $table->decimal('lead_time', 10, 2)->default(0);
            $table->decimal('safety_stock', 15, 2)->default(0);
            $table->decimal('reorder_point', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
