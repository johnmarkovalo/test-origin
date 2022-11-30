<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IsolationRoom extends Model
{
    protected $fillable = [
        'isolation_id', 'room_no', 'status',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $with = ['isolation'];

    public function isolation()
    {
        return $this->belongsTo(Isolation::class);
    }

    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }

    public function roomRequests()
    {
        return $this->hasMany(IsolationRoomRequest::class);
    }
}
