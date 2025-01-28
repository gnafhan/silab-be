<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 * 
 * @property int $id
 * @property int $room_id
 * @property int $subject_id
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string $dosen
 * @property string $information
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Room $room
 * @property Subject $subject
 *
 * @package App\Models
 */
class Schedule extends Model
{
	protected $table = 'schedules';

	protected $casts = [
		'room_id' => 'int',
		'subject_id' => 'int',
		'start_time' => 'datetime',
		'end_time' => 'datetime'
	];

	protected $fillable = [
		'room_id',
		'subject_id',
		'start_time',
		'end_time',
		'dosen',
		'information'
	];

	public function room()
	{
		return $this->belongsTo(Room::class);
	}

	public function subject()
	{
		return $this->belongsTo(Subject::class);
	}
}
