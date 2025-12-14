<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('production_results', function (Blueprint $table) {
      $table->id();
      $table->foreignId('production_plan_item_id')->constrained('production_plan_items')->onDelete('cascade');
      $table->date('result_date');
      $table->decimal('quantity', 10, 4);
      $table->decimal('defective_quantity', 10, 4)->default(0);
      $table->text('remarks')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('production_results');
  }
};
