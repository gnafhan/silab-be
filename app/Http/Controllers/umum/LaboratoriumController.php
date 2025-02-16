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
use Illuminate\Support\Facades\Log;
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

    public function allReserve(){
        $reserves = RoomReserf::with('room')->get();
    
        if ($reserves->isEmpty()) {
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

    public function reservebyId($id)
    {
        // Gunakan with() untuk memuat relasi 'room'
        $reserves = RoomReserf::with('room')->find($id);

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

    public function searchReservations($query = null)
{
    // If query is empty or null, fetch all reservations
    if (empty($query)) {
        $reservations = RoomReserf::with('room')->get();
    } else {
        // Existing search logic
        $room = Room::where('name', 'LIKE', "%{$query}%")->first();

        if (!$room) {
            return response()->json([
                'message' => 'Room not found',
                'data' => []
            ]);
        }

        $reservations = RoomReserf::with('room')->where('room_id', $room->id)->get();
    }

    return response()->json([
        'message' => $reservations->isEmpty() ? 'No reservations found' : 'Reservations found',
        'data' => $reservations
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
        Log::info($request->all());
        try {
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
            'name' => 'required|string'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error($e->errors());
        return response()->json([
            "message" => "Gagal membuat reservasi laboratorium",
            "errors" => $e->errors()
        ], 400);
    }
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

    public function approve($id)
    {
        $reserve = RoomReserf::find($id);
        
        if (!$reserve) {
            return response()->json([
                "message" => "Reservasi tidak ditemukan"
            ], 404);
        }

        $reserve->update([
            'is_approved' => 1
        ]);

        return response()->json([
            "message" => "Reservasi berhasil disetujui",
            "data" => $reserve
        ]);
    }

    public function reject($id)
    {
        $reserve = RoomReserf::find($id);
        
        if (!$reserve) {
            return response()->json([
                "message" => "Reservasi tidak ditemukan"
            ], 404);
        }

        $reserve->update([
            'is_approved' => -1
        ]);

        return response()->json([
            "message" => "Reservasi berhasil ditolak",
            "data" => $reserve
        ]);
    }
}


