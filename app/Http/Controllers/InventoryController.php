<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["data" => Inventory::with('rooms')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'no_item' => 'required|string|max:255',
            'condition' => 'required',
            'information' => 'required',
        ]);
    
        $rule = Inventory::create($validatedData);   

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
        $inventory = Inventory::find($id);
        if(!$inventory){
            return response()->json(["message"=> "Inventaris tidak ditemukan"]);
        }
        return response()->json(["data"=>$inventory]);    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'no_item' => 'required|string|max:255',
            'condition' => 'required',
            'information' => 'required',
        ]);

        $inventory = Inventory::find($id);
        if(!$inventory){
            return response()->json(["message"=> "Inventory tidak ditemukan"]);
        }

        $inventory->update($validatedData);

        return response()->json([
            "message"=> "Berhasil mengupdate inventory",
            "data"=>$inventory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventory = Inventory::find($id);
        if(!$inventory){
            return response()->json(["message"=> "Inventory tidak ditemukan"]);
        }

        $inventory->delete();
        return response()->json([
            "message"=> "Berhasil menghapus inventory",
            "data"=>$inventory
        ]);
    }
}
