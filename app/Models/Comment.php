<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * 
 * @property int $id
 * @property int $id_content
 * @property int $id_user
 * @property string $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Content $content
 * @property User $user
 *
 * @package App\Models
 */
class Comment extends Model
{
	protected $table = 'comments';

	protected $casts = [
		'id_content' => 'int',
		'id_user' => 'int'
	];

	protected $fillable = [
		'id_content',
		'id_user',
		'comment'
	];

	public function content()
	{
		return $this->belongsTo(Content::class, 'id_content');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
