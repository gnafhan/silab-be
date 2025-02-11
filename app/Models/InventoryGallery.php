<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryGallery extends Model
{
    protected $table = 'inventory_galleries';

    protected $fillable = [
        'inventory_id',
        'filepath',
        'filename'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}