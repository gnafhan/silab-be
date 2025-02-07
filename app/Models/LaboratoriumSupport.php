<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumSupport extends Model
{
    use HasFactory;

    protected $table = 'laboratorium_support';

    protected $fillable = [
        'room_id',
        'support_type_1',
        'support_type_2',
        'support_type_3',
        'support_type_4',
        'description',
    ];

    // Relasi ke model Room
    public function room()
    {
        return $this->hasMany(Room::class);
    }
}
