<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripShare extends Model
{
    use HasFactory;

    protected $table = 'trip_shares';

    protected $fillable = [
        'trip_id',
        'shared_with_user_id',
        'permission'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function sharedWithUser()
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }
}