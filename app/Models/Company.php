<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Company extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'slogan',
    'url',
  ];

  public function address(): MorphOne
  {
    return $this->morphOne(Address::class, 'model');
  }

  public function contacts()
  {
    return $this->hasMany(Contact::class);
  }

  protected static function boot()
  {

    parent::boot();

    static::creating(function ($company) {
      $company->cid = Str::orderedUuid();
    });

    static::deleting(function ($company) {

      $company->load('contacts.invoices.items');

      $company->contacts->each(function ($contact) {

        $contact->invoices->each(function ($invoice) {

          $invoice->items()->delete();

          $invoice->delete();

        });

        $contact->delete();

      });

    });

  }
}
