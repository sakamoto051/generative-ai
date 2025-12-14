<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Finished Products
    $products = [
      ['code' => 'FP-001', 'name' => 'High-Performance widget', 'type' => 'product', 'standard_cost' => 5000, 'lead_time_days' => 5, 'minimum_stock_level' => 50],
      ['code' => 'FP-002', 'name' => 'Budget Widget', 'type' => 'product', 'standard_cost' => 3000, 'lead_time_days' => 4, 'minimum_stock_level' => 100],
    ];

    foreach ($products as $product) {
      Product::firstOrCreate(['code' => $product['code']], $product);
    }

    // Materials
    $materials = [
      ['code' => 'RM-001', 'name' => 'Steel Sheet', 'type' => 'material', 'standard_cost' => 500, 'lead_time_days' => 10, 'minimum_stock_level' => 1000],
      ['code' => 'RM-002', 'name' => 'Plastic Resin', 'type' => 'material', 'standard_cost' => 200, 'lead_time_days' => 7, 'minimum_stock_level' => 2000],
      ['code' => 'RM-003', 'name' => 'Bolts & Nuts Pack', 'type' => 'part', 'standard_cost' => 100, 'lead_time_days' => 3, 'minimum_stock_level' => 5000],
    ];

    foreach ($materials as $material) {
      Product::firstOrCreate(['code' => $material['code']], $material);
    }
  }
}
