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
        Schema::create('mo_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturing_order_id')->constrained('manufacturing_orders')->onDelete('cascade');
            $table->morphs('item');
            $table->decimal('required_quantity', 10, 2);
            $table->string('unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mo_components');
    }
};
