<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Announcement
 * 
 * @property int $id
 * @property string $tittle
 * @property string $detail
 * @property string $attachment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Announcement extends Model
{
	protected $table = 'announcements';

	protected $fillable = [
		'tittle',
		'detail',
		'attachment'
	];
}
