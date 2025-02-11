<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pendadaran
 * 
 * @property int $id
 * @property int $id_student
 * @property int $id_lecturer
 * @property Carbon $tanggal_sidang
 * @property Carbon $jam
 * @property string $ruang
 * @property int $nilai
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Lecturer $lecturer
 * @property Student $student
 *
 * @package App\Models
 */
class Pendadaran extends Model
{
	protected $table = 'pendadaran';

	protected $casts = [
		'id_student' => 'int',
		'id_lecturer' => 'int',
		'tanggal_sidang' => 'datetime',
		'jam' => 'datetime',
		'nilai' => 'int'
	];

	protected $fillable = [
		'id_student',
		'id_lecturer',
		'tanggal_sidang',
		'jam',
		'ruang',
		'nilai'
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
