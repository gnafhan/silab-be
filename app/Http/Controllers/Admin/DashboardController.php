<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Labolatory;
use App\Models\Room;
use App\Models\Pengadaan;
use App\Models\ItemPengadaan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all laboratories with room and inventory counts
        $laboratories = Labolatory::withCount(['inventories'])->get();
        
        // Total counts
        $totalRooms = Room::count();
        $totalInventories = Inventory::count();
        $totalPengadaan = Pengadaan::count();
        
        // Calculate total inventory value
        $totalInventoryValue = Pengadaan::sum(DB::raw('jumlah * harga_item'));
        
        // Get inventory conditions distribution
        $inventoryConditions = Inventory::select('condition', DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->get();
            
        // Monthly pengadaan statistics for current year
        $monthlyPengadaan = Pengadaan::selectRaw('MONTH(bulan_pengadaan) as month, SUM(jumlah * harga_item) as total_value')
            ->whereYear('bulan_pengadaan', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Create array with all months (1-12)
        $monthlyData = array_fill(1, 12, 0);
        
        // Fill in actual data
        foreach ($monthlyPengadaan as $item) {
            $monthlyData[$item->month] = floatval($item->total_value);
        }
        
        // Get top 5 laboratories by inventory count
        $topLabs = Labolatory::withCount('inventories')
            ->orderBy('inventories_count', 'desc')
            ->take(5)
            ->get();
            
        // Get rooms for each laboratory
        $labRooms = [];
        foreach ($laboratories as $lab) {
            $labRooms[$lab->id] = Room::where('name', $lab->name)->count();
        }

        return Inertia::render('Dashboard', [
            'statistics' => [
                'totalLabs' => $laboratories->count(),
                'totalRooms' => $totalRooms,
                'totalInventories' => $totalInventories,
                'totalPengadaan' => $totalPengadaan,
                'totalInventoryValue' => $totalInventoryValue,
            ],
            'laboratories' => $laboratories->map(function ($lab) use ($labRooms) {
                return [
                    'id' => $lab->id,
                    'name' => $lab->name,
                    'inventoryCount' => $lab->inventories_count,
                    'roomCount' => $labRooms[$lab->id] ?? 0,
                ];
            }),
            'conditions' => $inventoryConditions,
            'monthlyData' => $monthlyData,
            'topLabs' => $topLabs,
        ]);
    }
}
