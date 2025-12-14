<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('bom_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('parent_product_id')->constrained('products');
      $table->foreignId('child_product_id')->constrained('products'); // The material or part
      $table->decimal('quantity', 10, 4); // Quantity required
      $table->decimal('yield_rate', 5, 4)->default(1.0000); // 1.0 = 100%
      $table->timestamps();

      $table->unique(['parent_product_id', 'child_product_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bom_items');
  }
};
