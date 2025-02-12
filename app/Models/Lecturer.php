<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Lecturer
 * 
 * @property int $id
 * @property int $id_user
 * @property string $image_profile
 * @property string $full_name
 * @property string $front_title
 * @property string $back_title
 * @property string $NID
 * @property string $phone_number
 * @property int $max_quota
 * @property bool $isKaprodi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Bimbingan[] $bimbingans
 * @property Collection|Pendadaran[] $pendadarans
 * @property Collection|Project[] $projects
 * @property Collection|Speciality[] $specialities
 * @property Collection|Supervisor[] $supervisors
 *
 * @package App\Models
 */
class Lecturer extends Model
{
	protected $table = 'lecturers';

	protected $casts = [
		'id_user' => 'int',
		'max_quota' => 'int',
		'isKaprodi' => 'bool'
	];

	protected $fillable = [
		'id_user',
		'image_profile',
		'full_name',
		'front_title',
		'back_title',
		'NID',
		'phone_number',
		'max_quota',
		'isKaprodi'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function bimbingans()
	{
		return $this->hasMany(Bimbingan::class, 'id_lecturer');
	}

	public function pendadarans()
	{
		return $this->hasMany(Pendadaran::class, 'id_lecturer');
	}

	public function projects()
	{
		return $this->hasMany(Project::class, 'id_lecturer');
	}

	public function specialities()
	{
		return $this->hasMany(Speciality::class, 'id_lecturer');
	}

	public function supervisors()
	{
		return $this->hasMany(Supervisor::class, 'id_lecturer');
	}
}
