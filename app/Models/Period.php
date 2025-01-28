<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Period
 * 
 * @property int $id
 * @property string $semester
 * @property string $year
 * @property string $description
 * @property string $status
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $tanggal_sidang
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Project[] $projects
 *
 * @package App\Models
 */
class Period extends Model
{
	protected $table = 'periods';

	protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'tanggal_sidang' => 'datetime'
	];

	protected $fillable = [
		'semester',
		'year',
		'description',
		'status',
		'start_date',
		'end_date',
		'tanggal_sidang'
	];

	public function projects()
	{
		return $this->hasMany(Project::class, 'id_period');
	}
}
