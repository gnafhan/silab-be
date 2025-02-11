<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subject
 * 
 * @property int $id
 * @property string $subject_name
 * @property string $lecturer
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Schedule[] $schedules
 *
 * @package App\Models
 */
class Subject extends Model
{
	protected $table = 'subjects';

	protected $fillable = [
		'subject_name',
		'lecturer'
	];

	public function schedules()
	{
		return $this->hasMany(Schedule::class);
	}
}
