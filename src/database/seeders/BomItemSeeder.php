<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\BomItem;
use Illuminate\Database\Seeder;

class BomItemSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $fp1 = Product::where('code', 'FP-001')->first();
    $fp2 = Product::where('code', 'FP-002')->first();

    $rm1 = Product::where('code', 'RM-001')->first();
    $rm2 = Product::where('code', 'RM-002')->first();
    $rm3 = Product::where('code', 'RM-003')->first();

    // BOM for FP-001
    if ($fp1 && $rm1 && $rm2 && $rm3) {
      BomItem::firstOrCreate([
        'parent_product_id' => $fp1->id,
        'child_product_id' => $rm1->id,
      ], ['quantity' => 2, 'yield_rate' => 0.95]);

      BomItem::firstOrCreate([
        'parent_product_id' => $fp1->id,
        'child_product_id' => $rm2->id,
      ], ['quantity' => 0.5, 'yield_rate' => 0.98]);

      BomItem::firstOrCreate([
        'parent_product_id' => $fp1->id,
        'child_product_id' => $rm3->id,
      ], ['quantity' => 10, 'yield_rate' => 1.00]);
    }

    // BOM for FP-002
    if ($fp2 && $rm2 && $rm3) {
      BomItem::firstOrCreate([
        'parent_product_id' => $fp2->id,
        'child_product_id' => $rm2->id,
      ], ['quantity' => 1.2, 'yield_rate' => 0.98]);

      BomItem::firstOrCreate([
        'parent_product_id' => $fp2->id,
        'child_product_id' => $rm3->id,
      ], ['quantity' => 5, 'yield_rate' => 1.00]);
    }
  }
}
