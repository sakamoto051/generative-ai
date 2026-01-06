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
            $table->string('mo_number')->unique();
            $table->foreignId('production_plan_detail_id')->nullable()->constrained('production_plan_details');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('quantity', 10, 2);
            $table->date('due_date');
            $table->string('status')->default('Planned');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
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
