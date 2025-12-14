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
    Schema::create('work_results', function (Blueprint $table) {
      $table->id();
      $table->foreignId('manufacturing_order_id')->constrained('manufacturing_orders')->cascadeOnDelete()->comment('製造指示ID');
      $table->foreignId('worker_id')->nullable()->constrained('workers')->nullOnDelete()->comment('作業者ID');
      $table->string('process', 100)->nullable()->comment('工程');
      $table->integer('completed_quantity')->default(0)->comment('完了数量');
      $table->integer('defect_quantity')->default(0)->comment('不良数量');
      $table->timestamp('work_start_at')->nullable()->comment('作業開始日時');
      $table->timestamp('work_end_at')->nullable()->comment('作業終了日時');
      $table->integer('work_minutes')->default(0)->comment('作業時間（分）');
      $table->foreignId('equipment_id')->nullable()->constrained('equipment')->nullOnDelete()->comment('使用設備ID');
      $table->text('defect_details')->nullable()->comment('不良詳細');
      $table->text('notes')->nullable()->comment('備考');
      $table->timestamps();

      $table->index('manufacturing_order_id');
      $table->index('worker_id');
      $table->index(['work_start_at', 'work_end_at'], 'wr_work_dates_index');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('work_results');
  }
};
