<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Statistic
 * 
 * @property int $id
 * @property int $id_user
 * @property string $page_visited
 * @property Carbon $visit_date
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Statistic extends Model
{
	protected $table = 'statistics';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'visit_date' => 'datetime'
	];

	protected $fillable = [
		'id_user',
		'page_visited',
		'visit_date'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
