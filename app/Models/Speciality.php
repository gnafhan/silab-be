<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Speciality
 * 
 * @property int $id
 * @property int $id_lecturer
 * @property string $tag
 * 
 * @property Lecturer $lecturer
 *
 * @package App\Models
 */
class Speciality extends Model
{
	protected $table = 'specialities';
	public $timestamps = false;

	protected $casts = [
		'id_lecturer' => 'int'
	];

	protected $fillable = [
		'id_lecturer',
		'tag'
	];

	public function lecturer()
	{
		return $this->belongsTo(Lecturer::class, 'id_lecturer');
	}
}
