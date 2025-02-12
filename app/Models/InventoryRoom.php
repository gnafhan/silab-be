<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryRoom
 * 
 * @property int $id
 * @property int $room_id
 * @property int $inventory_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Inventory $inventory
 * @property Room $room
 *
 * @package App\Models
 */
class InventoryRoom extends Model
{
	protected $table = 'inventory_rooms';

	protected $casts = [
		'room_id' => 'int',
		'inventory_id' => 'int'
	];

	protected $fillable = [
		'room_id',
		'inventory_id'
	];

	public function inventory()
	{
		return $this->belongsTo(Inventory::class);
	}

	public function room()
	{
		return $this->belongsTo(Room::class);
	}
}
