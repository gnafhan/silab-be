<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $limit = $request->input('limit', 10);
        $rooms = $query->latest()
            ->paginate($limit)
            ->appends($request->query());

        return Inertia::render('Room/Index', [
            'rooms' => $rooms,
            'filters' => $request->only(['search', 'limit'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Room/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'type' => 'required|in:gudang,laboratorium',
            'description' => 'required|string',
        ]);

        Room::create($validated);

        return redirect()->route('room.index')
            ->with('message', 'Room created successfully');
    }

    public function edit(Room $room)
    {
        return Inertia::render('Room/Edit', [
            'room' => $room
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'type' => 'required|in:gudang,laboratorium',
            'description' => 'required|string',
        ]);

        $room->update($validated);

        return redirect()->route('room.index')
            ->with('message', 'Room updated successfully');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('room.index')
            ->with('message', 'Room deleted successfully');
    }
}
