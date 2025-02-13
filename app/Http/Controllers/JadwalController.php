<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function getSchedule(){
        $jadwal = Room::with('schedules')->get();

        return response()->json([
            "message"=> "Berhasil mengambil data jadwal lab",
            "data"=>$jadwal
        ]);
    }

    public function getScheduleByRoom($id){
        $jadwal = Room::with('schedules')->find($id);
 
        if(!$jadwal){
            return response()->json(["message"=> "Jadwal tidak ditemukan"]);
        }

        return response()->json([
            "message"=> "Berhasil mengambil data jadwal lab detail",
            "data"=>$jadwal
        ]);
    }
}
