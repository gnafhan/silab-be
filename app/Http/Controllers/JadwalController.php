<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;
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
        $jadwal = Room::with('schedules','schedules.subject')->find($id);
 
        if(!$jadwal){
            return response()->json(["message"=> "Jadwal tidak ditemukan"]);
        }

        return response()->json([
            "message"=> "Berhasil mengambil data jadwal lab detail",
            "data"=>$jadwal
        ]);
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'room_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'start_time' => 'required|date_format:Y-m-d H:i:s', // Ensure it's a datetime
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time', // Ensure end_time is after start_time
            'dosen' => 'required|string|max:255',  // Assuming 'dosen' is a string
            'information' => 'nullable|string|max:255',  // Optional field for information
        ]);
        
        // Create a new schedule record
        $schedule = Schedule::create($validatedData);   

        // Return a response indicating the schedule was created successfully
        return response()->json([
            "message" => "Berhasil membuat jadwal",
            "data" => $schedule
        ], 201);
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'room_id' => 'sometimes|integer',
            'subject_id' => 'sometimes|integer',
            'start_time' => 'sometimes|date_format:Y-m-d H:i:s',
            'end_time' => 'sometimes|date_format:Y-m-d H:i:s|after:start_time',
            'dosen' => 'sometimes|string|max:255',
            'information' => 'nullable|string|max:255',
        ]);

        // Find the schedule by ID
        $schedule = Schedule::find($id);
        
        // If schedule not found, return an error response
        if(!$schedule){
            return response()->json(["message" => "Jadwal tidak ditemukan"], 404);
        }

        // Update the schedule with validated data
        $schedule->update($validatedData);

        // Return a response indicating the schedule was updated successfully
        return response()->json([
            "message" => "Berhasil mengupdate jadwal",
            "data" => $schedule
        ]);
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(string $id)
    {
        // Find the schedule by ID
        $schedule = Schedule::find($id);

        // If schedule not found, return an error response
        if(!$schedule){
            return response()->json(["message" => "Jadwal tidak ditemukan"], 404);
        }

        // Delete the schedule record
        $schedule->delete();

        // Return a response indicating the schedule was deleted successfully
        return response()->json([
            "message" => "Berhasil menghapus jadwal",
            "data" => $schedule
        ]);
    }

    public function show(string $id)
    {   
        $schedule = Schedule::find($id);
        if(!$schedule){
            return response()->json(["message"=> "Jadwal tidak ditemukan"]);
        }
        return response()->json(["data"=>$schedule]);    }
}
