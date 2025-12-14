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
    Schema::create('manufacturing_orders', function (Blueprint $table) {
      $table->id();
      $table->string('order_number', 50)->unique()->comment('製造番号');
      $table->foreignId('production_plan_item_id')->nullable()->constrained('production_plan_items')->nullOnDelete()->comment('生産計画明細ID');
      $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->comment('製品ID');
      $table->integer('order_quantity')->default(0)->comment('製造指示数量');
      $table->integer('completed_quantity')->default(0)->comment('完了数量');
      $table->integer('defect_quantity')->default(0)->comment('不良数量');
      $table->string('status', 20)->default('pending')->comment('ステータス (pending/in_progress/completed/cancelled)');
      $table->date('scheduled_start_date')->comment('予定開始日');
      $table->date('scheduled_end_date')->comment('予定完了日');
      $table->timestamp('actual_start_at')->nullable()->comment('実績開始日時');
      $table->timestamp('actual_end_at')->nullable()->comment('実績完了日時');
      $table->foreignId('equipment_id')->nullable()->constrained('equipment')->nullOnDelete()->comment('設備ID');
      $table->text('notes')->nullable()->comment('備考');
      $table->string('qr_code', 500)->nullable()->comment('QRコード');
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('作成者');
      $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('更新者');
      $table->timestamps();
      $table->softDeletes();

      $table->index('order_number');
      $table->index('product_id');
      $table->index('status');
      $table->index(['scheduled_start_date', 'scheduled_end_date'], 'mo_scheduled_dates_index');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('manufacturing_orders');
  }
};
