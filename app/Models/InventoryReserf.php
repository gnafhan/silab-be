<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryReserf
 * 
 * @property int $id
 * @property int $inventory_id
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
 * @property Inventory $inventory
 *
 * @package App\Models
 */
class InventoryReserf extends Model
{
	protected $table = 'inventory_reserves';

	protected $casts = [
		'inventory_id' => 'int',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		// 'is_approved' => 'bool'
	];

	protected $fillable = [
		'inventory_id',
		'start_time',
		'end_time',
		'identity',
		'email',
		'is_approved',
		'no_wa',
		'needs',
		'name'
	];

	public function inventory()
	{
		return $this->belongsTo(Inventory::class);
	}
}
