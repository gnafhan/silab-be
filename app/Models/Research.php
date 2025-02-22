<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    use HasFactory;
    protected $table = 'research';
    protected $fillable = ['title', 'description', 'laboran_id'];

    public function laboran()
    {
        return $this->belongsTo(Laboran::class);
    }
}
