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
    Schema::create('production_plans', function (Blueprint $table) {
      $table->id();
      $table->string('plan_number')->unique(); // e.g., PP-202512-001
      $table->date('period_start');
      $table->date('period_end');
      $table->string('status')->default('draft'); // draft, pending, approved, rejected
      $table->foreignId('creator_id')->constrained('users');
      $table->text('description')->nullable();
      $table->timestamps();
      $table->softDeletes();
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
