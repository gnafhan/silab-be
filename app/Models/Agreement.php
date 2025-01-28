<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Agreement
 * 
 * @property int $id
 * @property int $id_student
 * @property int $id_project
 * @property string $agreement_status
 * @property int $progress
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Project $project
 * @property Student $student
 *
 * @package App\Models
 */
class Agreement extends Model
{
	protected $table = 'agreements';

	protected $casts = [
		'id_student' => 'int',
		'id_project' => 'int',
		'progress' => 'int'
	];

	protected $fillable = [
		'id_student',
		'id_project',
		'agreement_status',
		'progress'
	];

	public function project()
	{
		return $this->belongsTo(Project::class, 'id_project');
	}

	public function student()
	{
		return $this->belongsTo(Student::class, 'id_student');
	}
}
