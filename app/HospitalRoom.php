<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalRoom extends Model
{
    protected $fillable = [
        'hospital_id', 'room_no', 'status',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $with = ['hospital'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }

    public function roomRequests()
    {
        return $this->hasMany(RoomRequest::class);
    }
}
