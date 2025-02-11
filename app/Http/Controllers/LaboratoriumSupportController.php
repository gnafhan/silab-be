<?php

namespace App\Http\Controllers;

use App\Models\LaboratoriumSupport;
use Illuminate\Http\Request;

class LaboratoriumSupportController extends Controller
{
    // Menampilkan semua support
    public function index()
    {
        return response()->json(["data" => LaboratoriumSupport::get()]);
    }

    // Menyimpan support baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'room_id' => 'required',
            'support_type_1' => 'nullable|string',
            'support_type_2' => 'nullable|string',
            'support_type_3' => 'nullable|string',
            'support_type_4' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Menyimpan data support ke database
        $support = LaboratoriumSupport::create($validatedData);

        return response()->json([
            "message" => "Berhasil menambahkan support",
            "data" => $support
        ], 201);
    }

    // Menampilkan support berdasarkan ID
    public function show($id)
    {
        $support = LaboratoriumSupport::find($id);
        if (!$support) {
            return response()->json(["message" => "Support tidak ditemukan"]);
        }
        return response()->json(["data" => $support]);
    }

    // Mengupdate data support berdasarkan ID
    public function update(Request $request, $id)
    {
        // Mencari laboratorium berdasarkan room_id yang sesuai
        $laboratorium = LaboratoriumSupport::where('room_id', $id)->first();

        // Validasi input dari request
        $validatedData = $request->validate([
            'room_id' => 'required',
            'support_type_1' => 'nullable|string',
            'support_type_2' => 'nullable|string',
            'support_type_3' => 'nullable|string',
            'support_type_4' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Jika laboratorium tidak ditemukan
        if (!$laboratorium) {
            return response()->json(["message" => "Support tidak ditemukan"], 404);
        }

        // Mengupdate data laboratorium dengan data yang sudah tervalidasi
        $laboratorium->update($validatedData);

        // Mengembalikan response JSON
        return response()->json([
            "message" => "Berhasil mengupdate support",
            "data" => $laboratorium
        ]);
    }


    // Menghapus data support berdasarkan ID
    public function destroy($id)
    {
        $support = LaboratoriumSupport::find($id);
        if (!$support) {
            return response()->json(["message" => "Support tidak ditemukan"]);
        }

        // Menghapus data support
        $support->delete();

        return response()->json([
            "message" => "Berhasil menghapus support",
            "data" => $support
        ]);
    }
}
