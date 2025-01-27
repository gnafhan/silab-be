<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property int $id_user
 * @property int $id_content
 * @property string $notification_message
 * @property Carbon $notification_date
 * 
 * @property Content $content
 * @property User $user
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notifications';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_content' => 'int',
		'notification_date' => 'datetime'
	];

	protected $fillable = [
		'id_user',
		'id_content',
		'notification_message',
		'notification_date'
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
