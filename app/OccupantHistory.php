<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OccupantHistory extends Model
{
    protected $fillable = [
        'occupant_id', 'details', 'activity_by', 'date', 'time'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }
}
