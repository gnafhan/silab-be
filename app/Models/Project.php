<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 * 
 * @property int $id
 * @property int $id_lecturer
 * @property int $id_period
 * @property string $tittle
 * @property string $agency
 * @property string $description
 * @property string $tools
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Lecturer $lecturer
 * @property Period $period
 * @property Collection|Agreement[] $agreements
 *
 * @package App\Models
 */
class Project extends Model
{
	protected $table = 'projects';

	protected $casts = [
		'id_lecturer' => 'int',
		'id_period' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_lecturer',
		'id_period',
		'tittle',
		'agency',
		'description',
		'tools',
		'status'
	];

	public function lecturer()
	{
		return $this->belongsTo(Lecturer::class, 'id_lecturer');
	}

	public function period()
	{
		return $this->belongsTo(Period::class, 'id_period');
	}

	public function agreements()
	{
		return $this->hasMany(Agreement::class, 'id_project');
	}
}
