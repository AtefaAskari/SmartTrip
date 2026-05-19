<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'day_date',
        'day_number',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'estimated_cost'
    ];

    protected $casts = [
        'day_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'estimated_cost' => 'decimal:2'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}