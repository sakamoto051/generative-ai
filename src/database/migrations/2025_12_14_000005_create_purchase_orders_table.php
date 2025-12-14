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
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->id();
      $table->string('po_number')->unique(); // PO-202512-001
      $table->foreignId('supplier_id')->constrained('suppliers');
      $table->string('status')->default('draft'); // draft, ordered, received, cancelled
      $table->date('order_date');
      $table->date('delivery_due_date')->nullable();
      $table->decimal('total_amount', 12, 2)->default(0);
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('purchase_order_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
      $table->foreignId('product_id')->constrained('products'); // The material
      $table->decimal('quantity', 10, 4);
      $table->decimal('unit_price', 10, 2);
      $table->decimal('subtotal', 12, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('purchase_order_items');
    Schema::dropIfExists('purchase_orders');
  }
};
