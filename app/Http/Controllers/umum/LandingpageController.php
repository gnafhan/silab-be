<?php

namespace App\Http\Controllers\umum;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index(){
        $room = count(Room::get());
        $user = count(User::where('role', 'umum')->get());
        $kalab = count(User::where('role', 'kaleb')->get());
        $laboran = count(User::where('role', 'admin')->get());
        $dosen = count(User::where('role', 'dosen')->get());

        return response()->json([
            "message"=> "Berhasil mengambil data informasi",
            "data"=>[
                "laboratorium" => $room,
                "user" => $user,
                "kalab" => $kalab,
                "laboran" => $laboran,
                "dosen" => $dosen,
            ]
        ]);
    }
}
