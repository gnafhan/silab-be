<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryReserve;
use App\Models\Room;
use App\Models\RoomReserve;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function countLab(){
        $labs = count(Room::get());
        return response()->json([
            "message"=> "Berhasil mengambil data jumlah lab",
            "data"=>$labs
        ]);
    }

    public function countInvent(){
        $inventories = count(Inventory::get());
        return response()->json([
            "message"=> "Berhasil mengambil data jumlah inventaris",
            "data"=>$inventories
        ]);
    }

    public function getSchedule(){
        $jadwal = Schedule::with('room')->get();

        return response()->json([
            "message"=> "Berhasil mengambil data jadwal lab",
            "data"=>$jadwal
        ]);
    }

    public function getLabReserve(){
        $labReserve = RoomReserve::with('room')->limit(5)->get();

        return response()->json([
            "message"=> "Berhasil mengambil data lab",
            "data"=>$labReserve
        ]);
    }

    public function getInventoryReserve(){
        $inventoryReserve = InventoryReserve::with('inventory')->limit(5)->get();

        return response()->json([
            "message"=> "Berhasil mengambil data inventory reserve",
            "data"=>$inventoryReserve
        ]);
    }

}
