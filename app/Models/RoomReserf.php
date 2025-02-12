<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomReserf
 * 
 * @property int $id
 * @property int $room_id
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string $identity
 * @property string $email
 * @property bool $is_approved
 * @property string $no_wa
 * @property string $needs
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Room $room
 *
 * @package App\Models
 */
class RoomReserf extends Model
{
	protected $table = 'room_reserves';

	protected $casts = [
		'room_id' => 'int',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		// 'is_approved' => 'bool'
	];

	protected $fillable = [
		'room_id',
		'start_time',
		'end_time',
		'identity',
		'email',
		'is_approved',
		'no_wa',
		'needs',
		 'name'
	];

	public function room()
	{
		return $this->belongsTo(Room::class, 'room_id');	}

	public function schedule(){
        return $this->hasMany(Schedule::class, 'room_id');
    }
}
