<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function uploadFoto(Request $request)
    {
        // Validasi file harus gambar
        $request->validate([
            'foto_laboratorium' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan file ke storage/public/uploads
        if ($request->hasFile('foto_laboratorium')) {
            $file = $request->file('foto_laboratorium');
            $filePath = $file->store('foto-laboratorium', 'public'); // Simpan ke storage/app/public/uploads

            return response()->json([
                'message' => 'Upload berhasil',
                'file_path' => asset('storage/' . $filePath), // URL lengkap untuk akses file
            ], 201);
        }

        return response()->json(['message' => 'Gagal mengupload file'], 400);
    }
}
