<?php

namespace App\Models;

use App\Traits\HasMediaCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class SupportRequest extends Model implements HasMedia
{
  use HasMediaCollection;

  protected $fillable = [
    'client_id',
    'subject',
    'message',
    'priority',
    'status',
    'resolution'
  ];

  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class);
  }
}
