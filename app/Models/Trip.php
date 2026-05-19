<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'start_date', 'end_date',
        'total_budget', 'visibility', 'cover_image'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_budget' => 'decimal:2',   // Ensures numeric value
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class)->orderBy('order');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function sharedWithUsers()
    {
        return $this->belongsToMany(User::class, 'trip_shares', 'trip_id', 'shared_with_user_id')
                    ->withPivot('permission')
                    ->withTimestamps();
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function weatherForecasts()
    {
        return $this->hasMany(WeatherForecast::class);
    }

    public function isPublic()
    {
        return $this->visibility === 'public';
    }

    public function canBeViewedBy($user)
    {
        if (!$user) return $this->isPublic();
        return $this->user_id === $user->id || $this->sharedWithUsers->contains($user);
    }
}