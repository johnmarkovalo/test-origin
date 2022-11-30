<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomRequest extends Model
{
    protected $fillable = [
        'hospital_room_id', 'occupant_id', 'type', 'status'
    ];

    //    protected $hidden = [
    //   'created_at', 'updated_at'
    //    ];

    protected $with = ['occupant', 'hospitalRoom'];


    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }
    public function hospitalRoom()
    {
        return $this->belongsTo(HospitalRoom::class);
    }
}
