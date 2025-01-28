<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Student
 * 
 * @property int $id
 * @property int $id_user
 * @property string $NIM
 * @property string $semester
 * @property int $IPK
 * @property int $SKS
 * @property string $phone_number
 * @property string $skill
 * @property string $experience
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Agreement[] $agreements
 * @property Collection|Bimbingan[] $bimbingans
 * @property Collection|Pendadaran[] $pendadarans
 * @property Collection|Supervisor[] $supervisors
 *
 * @package App\Models
 */
class Student extends Model
{
	protected $table = 'students';

	protected $casts = [
		'id_user' => 'int',
		'IPK' => 'int',
		'SKS' => 'int'
	];

	protected $fillable = [
		'id_user',
		'NIM',
		'semester',
		'IPK',
		'SKS',
		'phone_number',
		'skill',
		'experience'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function agreements()
	{
		return $this->hasMany(Agreement::class, 'id_student');
	}

	public function bimbingans()
	{
		return $this->hasMany(Bimbingan::class, 'id_student');
	}

	public function pendadarans()
	{
		return $this->hasMany(Pendadaran::class, 'id_student');
	}

	public function supervisors()
	{
		return $this->hasMany(Supervisor::class, 'id_student');
	}
}
