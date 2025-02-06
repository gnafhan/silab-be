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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\InventoryGallery;
use Illuminate\Auth\Access\Gate;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'laboran') {
            $query = Inventory::with(['room', 'laboratory', 'creator', 'galleries' => function ($q) {
                $q->latest()->take(1);
            }]);
        } else {
            $query = Inventory::with(['room', 'laboratory', 'creator', 'galleries' => function ($q) {
                $q->latest()->take(1);
            }]);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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
            'filters' => $request->only(['search', 'limit', 'laboratory']),
            'can' => [
                'update_inventory' => true,
                'delete_inventory' => true
            ]
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'laboran') {
            abort(403, 'Unauthorized action.');
        }

        $rooms = Room::all();
        $laboratories = Labolatory::all();

        return Inertia::render('Inventory/Create', [
            'rooms' => $rooms,
            'laboratories' => $laboratories
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'laboran') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'no_item' => 'required|string|max:255',
            'condition' => 'required|string',
            'alat/bhp' => 'required|string',
            'no_inv_ugm' => 'required|string',
            'information' => 'nullable|string',
            'room_id' => 'required|exists:rooms,id',
            'labolatory_id' => [
                'required',
                'exists:labolatories,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->role === 'laboran' && $value != $user->lab_id) {
                        $fail('You can only add inventory to your own laboratory.');
                    }
                },
            ],
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $inventory = Inventory::create($validated);


        // Handle gallery uploads
        if ($request->hasFile('gallery')) {
            // dd($request->gallery);
            // dd($request->file('gallery')->store('inventory-galleries', 'public'));

            foreach ($request->gallery as $image) {
                // dd("aa", $image);
                $path = $image->store('inventory-galleries', 'public');
                $inventory->galleries()->create([
                    'filepath' => $path,
                    'filename' => $image->getClientOriginalName()
                ]);
            }
        }

        return redirect()->route('inventory.index')
            ->with('message', 'Inventory created successfully');
    }

    public function edit(Inventory $inventory)
    {
        try {
            $this->authorize('update', $inventory);

            $rooms = Room::all();
            $laboratories = Labolatory::all();
            $inventoriy_with_galleries = Inventory::with('galleries')->find($inventory->id);
            // dd($inventoriy_with_galleries);


            return Inertia::render('Inventory/Edit', [
                'inventory' => $inventoriy_with_galleries,
                'rooms' => $rooms,
                'laboratories' => $laboratories
            ]);
        } catch (\Exception $e) {
            abort(403, 'You are not authorized to edit this inventory.');
        }
    }

    public function update(Request $request, Inventory $inventory)
    {
        try {
            $this->authorize('update', $inventory);
            $user = Auth::user();

            $validated = $request->validate([
                'item_name' => 'required|string|max:255',
                'no_item' => 'required|string|max:255',
                'condition' => 'required|string',
                'alat/bhp' => 'required|string',
                'no_inv_ugm' => 'required|string',
                'information' => 'nullable|string',
                'room_id' => 'required|exists:rooms,id',
                'labolatory_id' => [
                    'required',
                    'exists:labolatories,id',
                    function ($attribute, $value, $fail) use ($user, $inventory) {
                        if ($user->role === 'laboran') {
                            if ($value != $user->lab_id) {
                                $fail('You can only manage inventory in your own laboratory.');
                            }
                            if ($inventory->labolatory_id != $user->lab_id) {
                                $fail('You can only edit inventory from your own laboratory.');
                            }
                        }
                    },
                ],
                'galleries.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $validated['updated_by'] = auth()->id();
            $inventory->update($validated);

            // Handle gallery uploads
            if ($request->hasFile('galleries')) {
                foreach ($request->galleries as $image) {
                    $path = $image->store('inventory-galleries', 'public');
                    $inventory->galleries()->create([
                        'filepath' => $path,
                        'filename' => $image->getClientOriginalName()
                    ]);
                }
            }

            return redirect()->route('inventory.index')
                ->with('message', 'Inventory updated successfully');
        } catch (\Exception $e) {
            Log::error('Update inventory error: ' . $e->getMessage());
            abort(403, 'Error updating inventory: ' . $e->getMessage());
        }
    }

    public function destroy(Inventory $inventory)
    {
        try {
            $this->authorize('delete', $inventory);

            $inventory->delete();

            return redirect()->route('inventory.index')
                ->with('message', 'Inventory deleted successfully');
        } catch (\Exception $e) {
            abort(403, 'You are not authorized to delete this inventory.');
        }
    }

    public function import(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'laboran') {
            abort(403, 'Unauthorized action.');
        }

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
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'laboran') {
            abort(403, 'Unauthorized action.');
        }

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

    public function deleteGallery($id)
    {
        $gallery = InventoryGallery::findOrFail($id);
        $this->authorize('delete', $gallery->inventory);

        // Delete file from storage
        Storage::disk('public')->delete($gallery->filepath);

        // Delete record
        $gallery->delete();

        return back()->with('message', 'Image deleted successfully');
    }
}
