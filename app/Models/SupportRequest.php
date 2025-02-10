<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportRequest extends Model
{
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
