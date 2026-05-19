<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'forecast_date',
        'city',
        'condition',
        'temp_high',
        'temp_low',
        'icon'
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'temp_high' => 'decimal:2',
        'temp_low' => 'decimal:2'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}