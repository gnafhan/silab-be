<?php

namespace App\Http\Controllers\umum;

use App\Http\Controllers\Controller;
use App\Mail\InventoryReservationConfirmation;
use App\Models\Inventory;
use App\Models\InventoryReserf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InventoryReserfController extends Controller
{
    public function index(){
        $inventories = Inventory::select('item_name', 'condition', DB::raw('COUNT(*) as count'))
        ->groupBy('item_name', 'condition')
        ->get()
        ->groupBy('item_name')
        ->map(function ($group) {
            return $group->mapWithKeys(function ($item) {
                return [$item->condition => $item->count];
            });
        });       

        return response()->json([
            "message"=> "Berhasil mengambil data inventaris",
            "data"=>$inventories
        ]);
    }

    public function getReserve(){
        $reserves = InventoryReserf::get();
 
        return response()->json([
            "message"=> "Berhasil mengambil data reservasi inventaris",
            "data"=>$reserves
        ]);
    }

    public function inventoryReserve(Request $request){
        $validatedData = $request->validate([
            'inventory_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'identity' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'no_wa' => 'required|string|max:50',
            'needs' => 'required|string',
        ]);

        $inventory = Inventory::find($validatedData['inventory_id']);
        if(!$inventory){
            return response()->json(["message"=> "Inventory tidak ditemukan"]);
        }

        $inventoryReserve = InventoryReserf::create($validatedData);
        $kalab = User::where('role', 'kaleb')->first();

        Mail::to($kalab->email)->send(new InventoryReservationConfirmation($inventoryReserve, Auth::user()));

        return response()->json([
            "message"=> "Berhasil membuat reservasi inventaris",
            "data"=>$inventoryReserve
        ]);
    }
}
