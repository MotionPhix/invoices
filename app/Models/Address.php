<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
  use HasFactory;

  protected $fillable = [
    'type',
    'street',
    'city',
    'state',
    'country',
  ];

  public function model(): MorphTo
  {
    return $this->morphTo();
  }
}
