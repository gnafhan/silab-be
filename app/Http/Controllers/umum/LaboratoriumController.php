<?php

namespace App\Http\Controllers\umum;

use App\Http\Controllers\Controller;
use App\Mail\LabReservationConfirmation;
use App\Models\Room;
use App\Models\RoomReserf;
use App\Models\RoomReserve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LaboratoriumController extends Controller
{
    public function index(){
        $rooms = Room::get();

        return response()->json([
            "message"=> "Berhasil mengambil data laboratorium",
            "data"=>$rooms
        ]);
    }

    public function detail($id){
        $room = Room::find($id);
        if(!$room){
            return response()->json(["message"=> "Laboratorium tidak ditemukan"]);
        }

        return response()->json([
            "message"=> "Berhasil mengambil data laboratorium",
            "data"=>$room
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

    public function reserveByRoom($id){
        $reserves = RoomReserf::where('room_id', $id)->get();

        return response()->json([
            "message"=> "Berhasil mengambil history reservasi",
            "data"=>$reserves
        ]);
    }

    public function labReserve(Request $request){
        $validatedData = $request->validate([
            'room_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'identity' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@mail.ugm.ac.id')) {
                        $fail('Email harus menggunakan domain @mail.ugm.ac.id.');
                    }
                },
            ],
            'no_wa' => 'required|string|max:50',
            'needs' => 'required|string',
        ]);
        // return $validatedData;

        $room = Room::find($validatedData['room_id']);
        if(!$room){ 
            return response()->json(["message"=> "Room tidak ditemukan"]);
        }

        $labReserve = RoomReserf::create($validatedData);
        $kalab = User::where('role', 'kaleb')->first();

        // Mail::to($kalab->email)->send(new LabReservationConfirmation($labReserve, Auth::user()));

        return response()->json([
            "message"=> "Berhasil membuat reservasi laboratorium",
            "data"=>$labReserve
        ]);
    }
}
