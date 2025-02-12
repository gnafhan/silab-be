<?php

namespace App\Http\Controllers;

use App\Models\Laboran;
use Illuminate\Http\Request;

class LaboranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Laboran::all();
        return response()->json(["data" => $students]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Laboran::find($id);
        if (!$student) {
            return response()->json(["message" => "Laboran tidak ditemukan"], 404);
        }
        return response()->json(["data" => $student]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
