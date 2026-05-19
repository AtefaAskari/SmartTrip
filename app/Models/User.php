<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the trips that belong to the user.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get the trips shared with this user (via trip_shares table).
     */
    public function sharedTrips()
    {
        return $this->belongsToMany(Trip::class, 'trip_shares', 'shared_with_user_id', 'trip_id')
                    ->withPivot('permission')
                    ->withTimestamps();
    }
    public function notifications()
{
    return $this->hasMany(Notification::class)->latest();
}

public function recommendations()
{
    return $this->hasMany(Recommendation::class);
}

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}