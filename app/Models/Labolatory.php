<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labolatory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function pengadaans()
    {
        return $this->hasMany(Pengadaan::class);
    }
}
