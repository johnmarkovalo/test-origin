<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IsolationRoomRequest extends Model
{
    protected $fillable = [
        'isolation_room_id', 'occupant_id', 'type', 'status'
    ];

    //    protected $hidden = [
    //   'created_at', 'updated_at'
    //    ];

    protected $with = ['occupant', 'isolationRoom'];


    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }
    public function isolationRoom()
    {
        return $this->belongsTo(IsolationRoom::class);
    }
}
