<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
  use HasFactory;

  protected $fillable = [
    'invoice_prefix',
    'invoice_suffix',
    'invoice_start_number',
    'vat_rate',
    'company_name',
    'company_email',
    'company_phone'
  ];
}
