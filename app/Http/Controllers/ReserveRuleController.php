<?php

namespace App\Http\Controllers;

use App\Models\ReserveRule;
use Illuminate\Http\Request;

class ReserveRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["data" => ReserveRule::get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rule' => 'required|string|max:255',
        ]);
    
        $rule = ReserveRule::create($validatedData);   

        return response()->json([
            "message"=> "Berhasil membuat rule",
            "data"=> $rule
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rule = ReserveRule::find($id);
        if(!$rule){
            return response()->json(["message"=> "Rule tidak ditemukan"]);
        }
        return response()->json(["data"=>$rule]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'rule' => 'required|string|max:255',
        ]);

        $rule = ReserveRule::find($id);
        if(!$rule){
            return response()->json(["message"=> "Rule tidak ditemukan"]);
        }

        $rule->update($validatedData);

        return response()->json([
            "message"=> "Berhasil mengupdate rule",
            "data"=>$rule
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rule = ReserveRule::find($id);
        if(!$rule){
            return response()->json(["message"=> "Rule tidak ditemukan"]);
        }

        $rule->delete();
        return response()->json([
            "message"=> "Berhasil menghapus rule",
            "data"=>$rule
        ]);
    }
}
