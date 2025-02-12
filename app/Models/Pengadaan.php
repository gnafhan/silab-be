<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'spesifikasi',
        'jumlah',
        'harga_item',
        'bulan_pengadaan',
        'labolatory_id'
    ];

    protected $casts = [
        'bulan_pengadaan' => 'date'
    ];

    public function laboratory()
    {
        return $this->belongsTo(Labolatory::class, 'labolatory_id');
    }

    public function itemPengadaans()
    {
        return $this->hasMany(ItemPengadaan::class);
    }

    // Add accessor for separate month and year
    public function getBulanPengadaanMonthAttribute()
    {
        return $this->bulan_pengadaan->format('n');
    }

    public function getBulanPengadaanYearAttribute()
    {
        return $this->bulan_pengadaan->format('Y');
    }
}
