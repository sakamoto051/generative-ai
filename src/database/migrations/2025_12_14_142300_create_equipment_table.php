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
    Schema::create('equipment', function (Blueprint $table) {
      $table->id();
      $table->string('code', 50)->unique()->comment('設備コード');
      $table->string('name')->comment('設備名');
      $table->string('category', 100)->nullable()->comment('設備カテゴリ');
      $table->string('process', 100)->nullable()->comment('工程');
      $table->integer('capacity_per_hour')->default(0)->comment('生産能力（個/時間）');
      $table->integer('setup_time_minutes')->default(0)->comment('段取り時間（分）');
      $table->decimal('hourly_rate', 10, 2)->default(0)->comment('時間チャージ（円/時間）');
      $table->integer('maintenance_interval_days')->default(0)->comment('メンテナンス周期（日）');
      $table->string('location', 200)->nullable()->comment('設置場所');
      $table->text('notes')->nullable()->comment('備考');
      $table->boolean('is_active')->default(true)->comment('有効フラグ');
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('作成者');
      $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('更新者');
      $table->timestamps();
      $table->softDeletes();

      $table->index('code');
      $table->index('category');
      $table->index('process');
      $table->index('is_active');
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('equipment');
  }
};
