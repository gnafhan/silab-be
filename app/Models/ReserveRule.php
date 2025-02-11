<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReserveRule
 * 
 * @property int $id
 * @property string $rules
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ReserveRule extends Model
{
	protected $table = 'reserve_rules';

	protected $fillable = [
		'rule'
	];
}
