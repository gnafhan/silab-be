<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Room;
use App\Models\Labolatory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventoryImport;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['room', 'laboratory', 'creator']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'LIKE', "%{$search}%")
                  ->orWhere('no_item', 'LIKE', "%{$search}%");
            });
        }

        // Laboratory filter
        if ($request->has('laboratory') && $request->laboratory !== null) {
            $query->where('labolatory_id', $request->laboratory);
        }

        // Pagination with dynamic limit and appending query parameters
        $limit = $request->input('limit', 10);
        $inventories = $query->latest()
            ->paginate($limit)
            ->appends($request->query());
        
        // dd($inventories);

        return Inertia::render('Inventory/Index', [
            'inventories' => $inventories,
            'laboratories' => Labolatory::all(),
            'filters' => $request->only(['search', 'limit', 'laboratory'])
        ]);
    }

    public function create()
    {
        $rooms = Room::all();
        $laboratories = Labolatory::all();

        return Inertia::render('Inventory/Create', [
            'rooms' => $rooms,
            'laboratories' => $laboratories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'no_item' => 'required|string|max:255',
            'condition' => 'required|string',
            'alat/bhp' => 'required|string',
            'no_inv_ugm' => 'required|string',
            'information' => 'nullable|string',
            'room_id' => 'required|exists:rooms,id',
            'labolatory_id' => 'required|exists:labolatories,id',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Inventory::create($validated);

        return redirect()->route('inventory.index')
            ->with('message', 'Inventory created successfully');
    }

    public function edit(Inventory $inventory)
    {
        $rooms = Room::all();
        $laboratories = Labolatory::all();

        return Inertia::render('Inventory/Edit', [
            'inventory' => $inventory,
            'rooms' => $rooms,
            'laboratories' => $laboratories
        ]);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'no_item' => 'required|string|max:255',
            'condition' => 'required|string',
            'alat/bhp' => 'required|string',
            'no_inv_ugm' => 'required|string',
            'information' => 'nullable|string',
            'room_id' => 'required|exists:rooms,id',
            'labolatory_id' => 'required|exists:labolatories,id',
        ]);

        $validated['updated_by'] = auth()->id();

        $inventory->update($validated);

        Inertia::share([
            'flash' => [
                'message' => 'Inventory updated successfully'
            ]
        ]);

        return redirect()->route('inventory.index')
            ->with('message', 'Inventory updated successfully');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('message', 'Inventory deleted successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new InventoryImport, $request->file('file'));
            return redirect()->route('inventory.index')
                ->with('message', 'Data imported successfully');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                return "Row {$failure->row()}: {$failure->errors()[0]}";
            })->join(', ');
            Log::error('Import failed: ' . $errors);

            return back()->withErrors('error', "Import failed: {$errors}");
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            return back()->withErrors('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $path = storage_path('app/templates');
        $filepath = $path . '/inventory_template.xlsx';
        
        if (!file_exists($path)) {
            // Create directory if it doesn't exist
            mkdir($path, 0777, true);
            
            // Generate template if it doesn't exist
            \Illuminate\Support\Facades\Artisan::call('excel:generate-inventory');
        } elseif (!file_exists($filepath)) {
            // Generate template if only the file is missing
            \Illuminate\Support\Facades\Artisan::call('excel:generate-inventory');
        }
        
        return response()->download($filepath);
    }
}
