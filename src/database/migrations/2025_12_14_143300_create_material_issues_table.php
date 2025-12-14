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
    Schema::create('material_issues', function (Blueprint $table) {
      $table->id();
      $table->foreignId('manufacturing_order_id')->constrained('manufacturing_orders')->cascadeOnDelete()->comment('製造指示ID');
      $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete()->comment('材料ID');
      $table->decimal('quantity', 12, 4)->default(0)->comment('払出数量');
      $table->string('lot_number', 100)->nullable()->comment('ロット番号');
      $table->timestamp('issued_at')->nullable()->comment('払出日時');
      $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete()->comment('払出担当者');
      $table->text('notes')->nullable()->comment('備考');
      $table->timestamps();

      $table->index('manufacturing_order_id');
      $table->index('material_id');
      $table->index('lot_number');
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('material_issues');
  }
};
