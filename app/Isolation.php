<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Isolation extends Model
{
    protected $fillable = [
        'user_id', 'name', 'number', 'longitude', 'latitude', 'address', 'status'
    ];

    protected $hidden = [
        'user_id', 'created_at', 'updated_at'
    ];

    // model relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isolationRooms()
    {
        return $this->hasMany(IsolationRoom::class);
    }
}
