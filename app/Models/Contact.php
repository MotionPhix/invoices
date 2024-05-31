<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Contact extends Model
{
  use HasFactory;

  protected $fillable = [
    'first_name',
    'last_name',
    'bio',
    'job_title',
    'middle_name',
    'company_id',
    'nickname'
  ];

  protected function casts(): array
  {
    return [
      'created_at' => 'date:d m, Y',
      'deleted_at' => 'date:d M, Y',
      'bio' => PurifyHtmlOnGet::class,
    ];
  }

  public function phones(): MorphMany
  {
    return $this->morphMany(Phone::class, 'model');
  }

  public function emails(): MorphMany
  {
    return $this->morphMany(Email::class, 'model');
  }

  public function company(): BelongsTo
  {
    return $this->belongsTo(Company::class);
  }

  protected function fullName(): Attribute
  {
    return Attribute::make(
      get: fn() => "{$this->first_name} {$this->last_name}"
    );
  }

  public function primaryEmail(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->emails->firstWhere('is_primary_email', true)?->email ?? null
    );
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($contact) {
      $contact->cid = Str::orderedUuid();
    });

    static::updating(function ($contact) {
      if (!isset($contact->cid)) {
        $contact->cid = Str::orderedUuid();
      }
    });
  }
}
