<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Room
 *
 * @property int $id
 * @property string $laboratorium_name
 * @property int|null $capacity
 * @property string $type
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Inventory[] $inventories
 * @property Collection|RoomReserf[] $room_reserves
 * @property Collection|Schedule[] $schedules
 *
 * @package App\Models
 */
class Room extends Model
{
	protected $table = 'rooms';

	protected $casts = [
		'capacity' => 'int'
	];

	protected $fillable = [
		'name',
		'capacity',
		'type',
        'foto_laboratorium',
		'description'
	];

	public function inventories()
	{
		return $this->belongsToMany(Inventory::class, 'inventory_rooms')
					->withPivot('id')
					->withTimestamps();
	}

	public function room_reserves()
	{
		return $this->hasMany(RoomReserf::class);
	}

	public function schedules()
	{
		return $this->hasMany(Schedule::class);
	}
}
