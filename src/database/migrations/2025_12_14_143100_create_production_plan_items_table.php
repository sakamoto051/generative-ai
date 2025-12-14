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
    Schema::create('production_plan_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('production_plan_id')->constrained('production_plans')->cascadeOnDelete()->comment('生産計画ID');
      $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->comment('製品ID');
      $table->integer('quantity')->default(0)->comment('生産数量');
      $table->date('scheduled_date')->comment('予定日');
      $table->integer('priority')->default(0)->comment('優先度');
      $table->foreignId('equipment_id')->nullable()->constrained('equipment')->nullOnDelete()->comment('設備ID');
      $table->text('notes')->nullable()->comment('備考');
      $table->timestamps();

      $table->index('production_plan_id');
      $table->index('product_id');
      $table->index('scheduled_date');
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('production_plan_items');
  }
};
