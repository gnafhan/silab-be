<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bimbingan
 * 
 * @property int $id
 * @property int $id_student
 * @property int $id_lecturer
 * @property string $ke
 * @property Carbon $tanggal
 * @property string $subjek
 * @property string $catatan_dosen
 * @property string $file
 * @property bool $status
 * @property string $aksi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Lecturer $lecturer
 * @property Student $student
 *
 * @package App\Models
 */
class Bimbingan extends Model
{
	protected $table = 'bimbingan';

	protected $casts = [
		'id_student' => 'int',
		'id_lecturer' => 'int',
		'tanggal' => 'datetime',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_student',
		'id_lecturer',
		'ke',
		'tanggal',
		'subjek',
		'catatan_dosen',
		'file',
		'status',
		'aksi'
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
