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
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Collection|Inventory[] $inventories
 * @property Collection|RoomReserf[] $room_reserves
 * @property Collection|Schedule[] $schedules
 * @property User|null $creator
 * @property User|null $updater
 *
 * @package App\Models
 */
class Room extends Model
{
	protected $table = 'rooms';

	protected $casts = [
		'capacity' => 'int',
        'created_by' => 'int',
        'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
		'capacity',
		'type',
        'foto_laboratorium',
		'description',
        'created_by',
        'updated_by'
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
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
