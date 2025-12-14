<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $suppliers = [
      [
        'code' => 'SUP-001',
        'name' => 'Metal Works Co.',
        'contact_person' => 'John Steel',
        'email' => 'john@metalworks.com',
        'phone' => '123-456-7890',
        'address' => '123 Steel Ave, Industrial City',
      ],
      [
        'code' => 'SUP-002',
        'name' => 'Global Plastics Inc.',
        'contact_person' => 'Jane Resin',
        'email' => 'jane@globalplastics.com',
        'phone' => '987-654-3210',
        'address' => '456 Polymer Rd, Chemical Valley',
      ],
    ];

    foreach ($suppliers as $supplier) {
      Supplier::firstOrCreate(['code' => $supplier['code']], $supplier);
    }
  }
}
