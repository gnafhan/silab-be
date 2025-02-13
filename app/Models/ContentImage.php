<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentImage
 * 
 * @property int $id
 * @property int $id_content
 * @property string $image_url
 * 
 * @property Content $content
 *
 * @package App\Models
 */
class ContentImage extends Model
{
	protected $table = 'content_images';
	public $timestamps = false;

	protected $casts = [
		'id_content' => 'int'
	];

	protected $fillable = [
		'id_content',
		'image_url'
	];

	public function content()
	{
		return $this->belongsTo(Content::class, 'id_content');
	}
}
