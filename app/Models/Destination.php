<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'city',
        'country',
        'arrival_date',
        'departure_date',
        'notes',
        'order'
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'departure_date' => 'date',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Get duration in days for this destination
    public function getDurationAttribute()
    {
        return $this->arrival_date->diffInDays($this->departure_date);
    }
    public function votes()
{
    return $this->hasMany(Vote::class);
}

public function getVoteScoreAttribute()
{
    $up = $this->votes()->where('vote_type', 'up')->count();
    $down = $this->votes()->where('vote_type', 'down')->count();
    return $up - $down;
}
}