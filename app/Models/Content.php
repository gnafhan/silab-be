<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Content
 * 
 * @property int $id
 * @property int $id_proyek
 * @property string $thumbnail_image_url
 * @property string|null $content_url
 * @property string|null $video_url
 * @property string|null $video_tittle
 * @property string|null $github_url
 * @property string $tipe_konten
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Comment[] $comments
 * @property Collection|ContentImage[] $content_images
 * @property Collection|Notification[] $notifications
 * @property Collection|Tag[] $tags
 *
 * @package App\Models
 */
class Content extends Model
{
	protected $table = 'contents';

	protected $casts = [
		'id_proyek' => 'int'
	];

	protected $fillable = [
		'id_proyek',
		'thumbnail_image_url',
		'content_url',
		'video_url',
		'video_tittle',
		'github_url',
		'tipe_konten'
	];

	public function comments()
	{
		return $this->hasMany(Comment::class, 'id_content');
	}

	public function content_images()
	{
		return $this->hasMany(ContentImage::class, 'id_content');
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class, 'id_content');
	}

	public function tags()
	{
		return $this->hasMany(Tag::class, 'id_content');
	}
}
