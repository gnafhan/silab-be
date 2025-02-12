<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["data" => Subject::get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject_name' => 'required|string|max:255',
            'lecturer' => 'required|string|max:255',
        ]);
    
        $subject = Subject::create($validatedData);   

        return response()->json([
            "message"=> "Berhasil membuat subject",
            "data"=> $subject
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::find($id);
        if(!$subject){
            return response()->json(["message"=> "Subject tidak ditemukan"]);
        }
        return response()->json(["data"=>$subject]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'subject_name' => 'string|max:255',
            'lecturer' => 'string|max:255',
        ]);

        $subject = Subject::find($id);
        if(!$subject){
            return response()->json(["message"=> "Subject tidak ditemukan"]);
        }

        $subject->update($validatedData);

        return response()->json([
            "message"=> "Berhasil mengupdate subject",
            "data"=>$subject
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::find($id);
        if(!$subject){
            return response()->json(["message"=> "Subject tidak ditemukan"]);
        }

        $subject->delete();
        return response()->json([
            "message"=> "Berhasil menghapus subject",
            "data"=>$subject
        ]);
    }
}
