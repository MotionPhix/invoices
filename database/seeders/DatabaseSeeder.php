<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory()->create([
      'name' => 'Rose Cross',
      'email' => 'ultra@shots.io',
    ]);

    $this->call([
      UserSeeder::class,
      SettingsSeeder::class,
      CompanySeeder::class,
      ContactSeeder::class,
      InvoiceSeeder::class,
      InvoicePaymentSeeder::class,
    ]);
  }
}
