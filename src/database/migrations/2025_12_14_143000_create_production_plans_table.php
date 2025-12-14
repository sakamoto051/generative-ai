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
    Schema::create('production_plans', function (Blueprint $table) {
      $table->id();
      $table->string('plan_number', 50)->unique()->comment('計画番号');
      $table->string('name')->comment('計画名');
      $table->date('plan_start_date')->comment('計画開始日');
      $table->date('plan_end_date')->comment('計画終了日');
      $table->string('status', 20)->default('draft')->comment('ステータス (draft/submitted/approved/rejected/completed)');
      $table->text('description')->nullable()->comment('説明');
      $table->text('notes')->nullable()->comment('備考');
      $table->timestamp('submitted_at')->nullable()->comment('申請日時');
      $table->timestamp('approved_at')->nullable()->comment('承認日時');
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->comment('承認者');
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('作成者');
      $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('更新者');
      $table->timestamps();
      $table->softDeletes();

      $table->index('plan_number');
      $table->index('status');
      $table->index(['plan_start_date', 'plan_end_date'], 'pp_plan_dates_index');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('production_plans');
  }
};
