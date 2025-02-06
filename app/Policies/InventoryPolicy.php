<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\InventoryGallery;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class InventoryPolicy
{
    public function update(User $user, Inventory $inventory)
    {
        return $user->role === 'admin' || 
               ($user->role === 'laboran' && $user->lab_id === $inventory->labolatory_id);
    }

    public function delete(User $user, Inventory $inventory)
    {
        return $user->role === 'admin' || 
               ($user->role === 'laboran' && $user->lab_id === $inventory->labolatory_id);
    }

    // public function deleteGallery(User $user, InventoryGallery $gallery)
    // {
    //     return $user->role === 'admin' || 
    //            ($user->role === 'laboran' && $user->lab_id === $gallery->inventory->labolatory_id) ? Response::allow() : Response::deny('You are not authorized to delete this gallery');
    // }
}