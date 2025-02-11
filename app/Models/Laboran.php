<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboran extends Model
{
    use HasFactory;
    protected $table = 'laboran';

    protected $fillable = ['name', 'email', 'phone'];

    public function researches()
    {
        return $this->hasMany(Research::class);
    }
}
