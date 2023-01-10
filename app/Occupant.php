<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type', 'status', 'address', 'number', 'gender', 'birthdate', 'vaccination'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $with = [
        'occupantHistory'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(HospitalRoom::class);
    }

    public function roomRequest()
    {
        return $this->belongsTo(RoomRequest::class);
    }

    public function occupantHistory()
    {
        return $this->hasMany(OccupantHistory::class);
    }
}
