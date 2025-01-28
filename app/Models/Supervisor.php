<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Supervisor
 * 
 * @property int $id
 * @property int $id_student
 * @property int $id_lecturer
 * 
 * @property Lecturer $lecturer
 * @property Student $student
 *
 * @package App\Models
 */
class Supervisor extends Model
{
	protected $table = 'supervisors';
	public $timestamps = false;

	protected $casts = [
		'id_student' => 'int',
		'id_lecturer' => 'int'
	];

	protected $fillable = [
		'id_student',
		'id_lecturer'
	];

	public function lecturer()
	{
		return $this->belongsTo(Lecturer::class, 'id_lecturer');
	}

	public function student()
	{
		return $this->belongsTo(Student::class, 'id_student');
	}
}
