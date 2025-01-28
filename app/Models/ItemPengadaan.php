<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPengadaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengadaan_id',
        'inventory_id'
    ];

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
