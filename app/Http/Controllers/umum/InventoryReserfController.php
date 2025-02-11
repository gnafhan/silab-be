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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InventoryReserfController extends Controller
{

    public function index(){
        $inventories = Inventory::get();

        return response()->json([
            "message"=> "Berhasil mengambil data inventory",
            "data"=>$inventories
        ]);
    }
    

    public function getReserve(){
        $reserves = InventoryReserf::with('inventory')->get();
 
        return response()->json([
            "message"=> "Berhasil mengambil data reservasi inventaris",
            "data"=>$reserves
        ]);
    }

    public function reservebyId($id)
    {
        $reserves = InventoryReserf::with('inventory')->find($id);

        if (!$reserves) {
            return response()->json([
                "message" => "Tidak ada jadwal reservasi saat ini",
                "data" => []
            ]);
        }

        return response()->json([
            "message" => "Berhasil mengambil history reservasi",
            "data" => $reserves
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
            'name' => 'required|string'
        ]);

        $inventory = Inventory::find($validatedData['inventory_id']);
        if(!$inventory){
            return response()->json(["message"=> "Inventory tidak ditemukan"]);
        }

        $inventoryReserve = InventoryReserf::create($validatedData);
        $kalab = User::where('role', 'kaleb')->first();

        // Mail::to($kalab->email)->send(new InventoryReservationConfirmation($inventoryReserve, Auth::user()));
        Log::info($inventoryReserve);
        return response()->json([
            "message"=> "Berhasil membuat reservasi inventaris",
            "data"=>$inventoryReserve
        ]);
    }

    public function approve($id)
    {
        $reserve = InventoryReserf::find($id);
        
        if (!$reserve) {
            return response()->json([
                "message" => "Reservasi tidak ditemukan"
            ], 404);
        }

        $reserve->is_approved = 1;
        $reserve->save();

        return response()->json([
            "message" => "Berhasil menyetujui reservasi",
            "data" => $reserve
        ]);
    }

    public function reject($id)
    {
        $reserve = InventoryReserf::find($id);
        
        if (!$reserve) {
            return response()->json([
                "message" => "Reservasi tidak ditemukan"
            ], 404);
        }

        $reserve->is_approved = -1;
        $reserve->save();

        return response()->json([
            "message" => "Berhasil menolak reservasi",
            "data" => $reserve
        ]);
    }
}
