<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\ItemPengadaan;
use App\Models\Pengadaan;
use App\Models\Labolatory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PengadaanImport;
use Illuminate\Support\Facades\Log;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengadaan::with(['laboratory']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'LIKE', "%{$search}%")
                  ->orWhere('spesifikasi', 'LIKE', "%{$search}%");
            });
        }

        // Laboratory filter
        if ($request->has('laboratory') && $request->laboratory !== null) {
            $query->where('labolatory_id', $request->laboratory);
        }

        // Month filter
        if ($request->has('month') && $request->month !== null) {
            $query->whereMonth('bulan_pengadaan', $request->month);
        }

        // Year filter
        if ($request->has('year') && $request->year !== null) {
            $query->whereYear('bulan_pengadaan', $request->year);
        }

        // Pagination with dynamic limit
        $limit = $request->input('limit', 10);
        $pengadaans = $query->latest()
            ->paginate($limit)
            ->appends($request->query());

        return Inertia::render('Pengadaan/Index', [
            'pengadaans' => $pengadaans,
            'laboratories' => Labolatory::all(),
            'filters' => $request->only(['search', 'limit', 'laboratory', 'month', 'year', 'page'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Pengadaan/Create', [
            'laboratories' => Labolatory::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'spesifikasi' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'harga_item' => 'required|integer|min:0',
            'bulan_pengadaan' => 'required|date',
            'labolatory_id' => 'required|exists:labolatories,id',
        ]);

        Pengadaan::create($validated);

        return redirect()->route('pengadaan.index')
            ->with('message', 'Pengadaan created successfully');
    }

    public function edit(Pengadaan $pengadaan)
    {
        return Inertia::render('Pengadaan/Edit', [
            'pengadaan' => $pengadaan,
            'laboratories' => Labolatory::all()
        ]);
    }

    public function update(Request $request, Pengadaan $pengadaan)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'spesifikasi' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'harga_item' => 'required|integer|min:0',
            'bulan_pengadaan' => 'required|date',
            'labolatory_id' => 'required|exists:labolatories,id',
        ]);

        $pengadaan->update($validated);

        return redirect()->route('pengadaan.index')
            ->with('message', 'Pengadaan updated successfully');
    }

    public function destroy(Pengadaan $pengadaan)
    {
        $pengadaan->delete();

        return redirect()->route('pengadaan.index')
            ->with('message', 'Pengadaan deleted successfully');
    }

    public function show(Pengadaan $pengadaan)
    {
        $pengadaan->load(['laboratory', 'itemPengadaans.inventory']);
        
        return Inertia::render('Pengadaan/Show', [
            'pengadaan' => $pengadaan
        ]);
    }

    public function editInventory(Pengadaan $pengadaan)
    {
        $pengadaan->load(['itemPengadaans.inventory']);
        
        // Get IDs of inventories that are already in any pengadaan
        $usedInventoryIds = ItemPengadaan::pluck('inventory_id')->unique();
        
        // Get available inventories (not in any pengadaan)
        $availableInventories = Inventory::whereNotIn('id', $usedInventoryIds)
            ->where('labolatory_id', $pengadaan->labolatory_id)
            ->get();

        return Inertia::render('Pengadaan/EditInventory', [
            'pengadaan' => $pengadaan,
            'availableInventories' => $availableInventories
        ]);
    }

    public function updateInventory(Request $request, Pengadaan $pengadaan)
    {
        $validated = $request->validate([
            'inventory_ids' => 'required|array',
            'inventory_ids.*' => 'exists:inventories,id'
        ]);

        // Delete existing relationships
        ItemPengadaan::where('pengadaan_id', $pengadaan->id)->delete();

        // Create new relationships
        foreach ($validated['inventory_ids'] as $inventoryId) {
            ItemPengadaan::create([
                'pengadaan_id' => $pengadaan->id,
                'inventory_id' => $inventoryId
            ]);
        }

        return redirect()->route('pengadaan.show', $pengadaan->id)
            ->with('message', 'Inventories updated successfully');
    }

    public function removeInventory(Pengadaan $pengadaan, $inventoryId)
    {
        ItemPengadaan::where('pengadaan_id', $pengadaan->id)
            ->where('inventory_id', $inventoryId)
            ->delete();

        return redirect()->route('pengadaan.show', $pengadaan->id)
            ->with('message', 'Inventory removed successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new PengadaanImport, $request->file('file'));
            return redirect()->route('pengadaan.index')
                ->with('message', 'Data imported successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route('pengadaan.index')
                ->withErrors('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $path = storage_path('app/templates');
        $filepath = $path . '/pengadaan_template.xlsx';
        
        if (!file_exists($path)) {
            // Create directory if it doesn't exist
            mkdir($path, 0777, true);
            
            // Generate template if it doesn't exist
            \Illuminate\Support\Facades\Artisan::call('excel:generate-pengadaan');
        } elseif (!file_exists($filepath)) {
            // Generate template if only the file is missing
            \Illuminate\Support\Facades\Artisan::call('excel:generate-pengadaan');
        }
        
        return response()->download($filepath);
    }
}
