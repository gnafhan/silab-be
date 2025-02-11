<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * 
 * @property int $id
 * @property int $id_content
 * @property string $tag
 * 
 * @property Content $content
 *
 * @package App\Models
 */
class Tag extends Model
{
	protected $table = 'tags';
	public $timestamps = false;

	protected $casts = [
		'id_content' => 'int'
	];

	protected $fillable = [
		'id_content',
		'tag'
	];

	public function content()
	{
		return $this->belongsTo(Content::class, 'id_content');
	}
}
