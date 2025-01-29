<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;

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
}