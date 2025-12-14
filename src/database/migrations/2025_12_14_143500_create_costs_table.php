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
    Schema::create('costs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('manufacturing_order_id')->constrained('manufacturing_orders')->cascadeOnDelete()->comment('製造指示ID');
      $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->comment('製品ID');
      $table->decimal('material_cost', 12, 2)->default(0)->comment('材料費');
      $table->decimal('labor_cost', 12, 2)->default(0)->comment('労務費');
      $table->decimal('overhead_cost', 12, 2)->default(0)->comment('製造経費');
      $table->decimal('total_cost', 12, 2)->default(0)->comment('合計原価');
      $table->decimal('unit_cost', 12, 2)->default(0)->comment('単位原価');
      $table->integer('quantity')->default(0)->comment('数量');
      $table->date('cost_calculation_date')->comment('原価計算日');
      $table->string('calculation_method', 50)->default('actual')->comment('計算方法（actual/standard）');
      $table->decimal('standard_cost_variance', 12, 2)->default(0)->comment('標準原価差異');
      $table->text('notes')->nullable()->comment('備考');
      $table->foreignId('calculated_by')->nullable()->constrained('users')->nullOnDelete()->comment('計算担当者');
      $table->timestamps();

      $table->index('manufacturing_order_id');
      $table->index('product_id');
      $table->index('cost_calculation_date');
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('costs');
  }
};
