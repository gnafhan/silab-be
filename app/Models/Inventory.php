<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class Inventory
 * 
 * @property int $id
 * @property string $item_name
 * @property string $no_item
 * @property string $condition
 * @property string $information
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * 
 * @property Collection|InventoryReserf[] $inventory_reserves
 * @property Collection|Room[] $rooms
 *
 * @package App\Models
 */
class Inventory extends Model
{
    use SoftDeletes;
	protected $table = 'inventories';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	protected $fillable = [
		'item_name',
		'no_item',
		'condition',
		 'alat/bhp',
        'no_inv_ugm',
		'information',
		'room_id',
        'labolatory_id',
        'created_by',
        'updated_by'
	];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // When restoring a soft-deleted inventory item, check if there's another active
        // record with the same no_item value to avoid unique constraint violations
        static::restoring(function ($inventory) {
            $exists = static::where('no_item', $inventory->no_item)
                        ->whereNull('deleted_at')
                        ->exists();
            
            return !$exists;
        });
    }

	public function inventory_reserves()
	{
		return $this->hasMany(InventoryReserf::class);
	}

	public function rooms()
	{
		return $this->belongsToMany(Room::class, 'inventory_rooms')
					->withPivot('id')
					->withTimestamps();
	}

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Labolatory::class, 'labolatory_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function itemPengadaans()
    {
        return $this->hasMany(ItemPengadaan::class);
    }

    public function galleries()
    {
        return $this->hasMany(InventoryGallery::class);
    }

    /**
     * Force delete the inventory and all its related records to avoid foreign key constraints.
     *
     * @return bool|null
     * @throws \Exception
     */
    public function forceDeleteWithRelated()
    {
        try {
            DB::transaction(function () {
                // Delete related inventory_reserves records
                DB::table('inventory_reserves')
                    ->where('inventory_id', $this->id)
                    ->delete();
                
                // Delete related inventory_rooms records
                DB::table('inventory_rooms')
                    ->where('inventory_id', $this->id)
                    ->delete();
                
                // Delete related item_pengadaans records
                DB::table('item_pengadaans')
                    ->where('inventory_id', $this->id)
                    ->delete();
                
                // Delete related inventory_galleries records
                foreach ($this->galleries as $gallery) {
                    // Delete file from storage if it exists
                    if (Storage::disk('public')->exists($gallery->filepath)) {
                        Storage::disk('public')->delete($gallery->filepath);
                    }
                    $gallery->delete();
                }
                
                // Now it's safe to force delete the inventory
                $this->forceDelete();
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error("Error force deleting inventory {$this->id}: " . $e->getMessage());
            throw $e;
        }
    }
}
