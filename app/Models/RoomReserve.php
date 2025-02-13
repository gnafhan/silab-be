<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReserve extends Model
{
    use HasFactory;

    public function room()
	{
		return $this->belongsTo(Room::class, 'room_id');
	}

    public function schedule(){
        return $this->hasMany(Schedule::class, 'room_id');
    }
}
