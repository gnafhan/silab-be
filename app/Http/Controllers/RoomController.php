<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["data"=>Room::get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
            'type' => 'required|in:gudang,laboratorium',
            'foto_laboratorium' => 'image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
        ]);


        if ($request->hasFile('foto_laboratorium')) {
            $filePath = $request->file('foto_laboratorium')->store('foto-laboratorium', 'public');
            $validatedData['foto_laboratorium'] = $filePath;
        }

        $room = Room::create($validatedData);

        return response()->json([
            "message" => "Berhasil membuat ruangan",
            "data" => $room
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::find($id);
        if(!$room){
            return response()->json(["message"=> "Ruangan tidak ditemukan"]);
        }
        return response()->json(["data"=>$room]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'capacity' => 'integer|nullable',
            'foto_laboratorium' => 'string',
            'type' => 'in:gudang,laboratorium',
            'description' => 'string|nullable',
        ]);

        // Cari ruangan berdasarkan ID
        $room = Room::find($id);
        if (!$room) {
            return response()->json(["message" => "Ruangan tidak ditemukan"], 404);
        }

        $room->update($validatedData);

        return response()->json([
            "message" => "Berhasil mengupdate ruangan",
            "data" => $room,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);
        if(!$room){
            return response()->json(["message"=> "Ruangan tidak ditemukan"]);
        }

        $room->delete();
        return response()->json([
            "message"=> "Berhasil menghapus ruangan",
            "data"=>$room
        ]);
    }
}
