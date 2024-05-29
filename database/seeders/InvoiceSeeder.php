<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $users = User::inRandomOrder()->limit(4)->get();

    $users->each(function ($user) {

      Invoice::factory(10)
        ->has(InvoiceItem::factory()->count(rand(3, 7)), 'items')
        ->create([
          'user_id' => $user->id,
          'invoice_number' => Invoice::generateInvoiceNumber(),
        ]);

    });
  }
}
