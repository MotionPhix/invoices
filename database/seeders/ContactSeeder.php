<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $companyIds = Company::pluck('id')->toArray();

    Contact::factory(15)->make()->each(function ($contact) use ($companyIds) {
      $contact->company_id = $this->getRandomCompanyId($companyIds);
      $contact->save();
    });
  }

  /**
   * Get a random company ID from the list of company IDs.
   *
   * @param array $companyIds
   * @return int
   */
  private function getRandomCompanyId(array $companyIds): int
  {
    return $companyIds[array_rand($companyIds)];
  }
}
