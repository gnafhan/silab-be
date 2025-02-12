<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryReserf;
use App\Models\InventoryReserve;
use App\Models\RoomReserf;
use App\Models\RoomReserve;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function getLabReserve(){
        $labReserve = RoomReserve::with('room')->get();

        return response()->json([
            "message"=> "Berhasil mengambil data peminjaman lab",
            "data"=>$labReserve
        ]);
    }

    public function changeStatusRoom(Request $request, $id){
        $validatedData = $request->validate([
            // 'status' => 'required|in:published,waiting,rejected'
            "is_approved" => 'required|boolean'
        ]);

        $labReserve = RoomReserf::find($id);
        if(!$labReserve){
            return response()->json(["message"=> "Lab Reserve tidak ditemukan"]);
        }

        $labReserve->update($validatedData);

        return response()->json([
            "message" => "Berhasil mengupdate status room reserve ",
            "data" => $labReserve
        ]);
    }

    public function getInventoryReserve(){
        $inventoryReserve = InventoryReserve::with('inventory')->get();

        return response()->json([
            "message"=> "Berhasil mengambil data inventory reserve",
            "data"=>$inventoryReserve
        ]);
    }

    public function changeStatusInventory(Request $request, $id){
        $validatedData = $request->validate([
            // 'status' => 'required|in:published,waiting,rejected'
            "is_approved" => 'required|boolean'
        ]);

        $labReserve = InventoryReserf::find($id);
        if(!$labReserve){
            return response()->json(["message"=> "Inventory Reserve tidak ditemukan"]);
        }

        $labReserve->update($validatedData);

        return response()->json([
            "message" => "Berhasil mengupdate status inventory reserve",
            "data" => $labReserve
        ]);
    }
}
