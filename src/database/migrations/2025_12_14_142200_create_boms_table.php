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
    Schema::create('boms', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->comment('製品ID');
      $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete()->comment('材料ID');
      $table->decimal('quantity', 12, 4)->default(1)->comment('使用数量');
      $table->decimal('yield_rate', 5, 2)->default(100)->comment('歩留まり率（%）');
      $table->integer('sequence')->default(0)->comment('工程順序');
      $table->date('valid_from')->nullable()->comment('有効開始日');
      $table->date('valid_to')->nullable()->comment('有効終了日');
      $table->text('notes')->nullable()->comment('備考');
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('作成者');
      $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('更新者');
      $table->timestamps();
      $table->softDeletes();

      $table->unique(['product_id', 'material_id'], 'bom_product_material_unique');
      $table->index('product_id');
      $table->index('material_id');
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('boms');
  }
};
