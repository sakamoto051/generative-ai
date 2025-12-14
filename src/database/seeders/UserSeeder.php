<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Admin User
    User::firstOrCreate(
      ['email' => 'admin@procost.com'],
      [
        'name' => 'System Admin',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
      ]
    );

    // Production Manager
    User::firstOrCreate(
      ['email' => 'manager@procost.com'],
      [
        'name' => 'Production Manager',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
      ]
    );
  }
}
