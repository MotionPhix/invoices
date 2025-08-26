<?php

namespace App\Models;

use App\Traits\HasMediaCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Invoice extends Model implements HasMedia
{
    use HasFactory, HasMediaCollection;

    protected $fillable = [
        'number',
        'client_id',
        'date',
        'status',
        'total',
        'currency',
        'notes',
        // Recurring fields
        'is_recurring',
        'recurring_frequency',
        'recurring_interval',
        'recurring_days',
        'recurring_start_date',
        'recurring_end_date',
        'next_recurring_date',
        'last_recurring_date',
        'recurring_total_cycles',
        'recurring_completed_cycles',
        'recurring_paused_at',
        'recurring_cancelled_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
