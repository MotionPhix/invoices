<?php

namespace Database\Seeders;

use App\Models\Contact;
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
    $users = User::get('id');

    Contact::inRandomOrder()->limit(5)->each(function ($contact) use($users) {

      Invoice::factory(rand(1, 2))
        ->has(InvoiceItem::factory()->count(rand(3, 6)), 'items')
        ->create([
          'contact_id' => $contact->id,
          'user_id' => $users->random()->id,
          'invoice_number' => Invoice::generateInvoiceNumber(),
        ]);

    });
  }
}
