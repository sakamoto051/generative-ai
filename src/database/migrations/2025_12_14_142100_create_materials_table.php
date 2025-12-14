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
      $table->string('code', 50)->unique()->comment('材料コード');
      $table->string('name')->comment('材料名');
      $table->string('category', 100)->nullable()->comment('カテゴリ');
      $table->text('description')->nullable()->comment('説明');
      $table->string('unit', 20)->default('個')->comment('単位');
      $table->decimal('unit_price', 12, 2)->default(0)->comment('単価');
      $table->string('supplier', 200)->nullable()->comment('仕入先');
      $table->integer('lead_time_days')->default(0)->comment('リードタイム（日）');
      $table->integer('current_stock')->default(0)->comment('現在庫数');
      $table->integer('safety_stock')->default(0)->comment('安全在庫数');
      $table->string('lot_management', 20)->default('none')->comment('ロット管理（none/fifo/lifo）');
      $table->boolean('is_active')->default(true)->comment('有効フラグ');
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('作成者');
      $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('更新者');
      $table->timestamps();
      $table->softDeletes();

      $table->index('code');
      $table->index('category');
      $table->index('supplier');
      $table->index('is_active');
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
