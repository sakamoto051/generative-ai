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
    Schema::create('workers', function (Blueprint $table) {
      $table->id();
      $table->string('employee_number', 50)->unique()->comment('社員番号');
      $table->string('name')->comment('氏名');
      $table->string('department', 100)->nullable()->comment('所属部門');
      $table->string('job_title', 100)->nullable()->comment('職種');
      $table->string('grade', 50)->nullable()->comment('等級');
      $table->decimal('hourly_rate', 10, 2)->default(0)->comment('時間単価（円/時間）');
      $table->text('skills')->nullable()->comment('保有スキル');
      $table->string('work_pattern', 50)->default('regular')->comment('勤務パターン');
      $table->boolean('is_active')->default(true)->comment('有効フラグ');
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('ユーザーID');
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('作成者');
      $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('更新者');
      $table->timestamps();
      $table->softDeletes();

      $table->index('employee_number');
      $table->index('department');
      $table->index('is_active');
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('workers');
  }
};
