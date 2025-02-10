<?php

namespace App\Models;

use App\Traits\HasMediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Invoice extends Model implements HasMedia
{
    use HasMediaCollection;
}
