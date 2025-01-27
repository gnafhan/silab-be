<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'name',
		// 'capacity',
		// 'type',
		// 'description'

        $rooms = [
            [
                'name' => 'Ruang 1',
                'capacity' => 10,
                'type' => 'laboratorium',
                'description' => 'Ruang Labolatory 1',
            ],
            [
                'name' => 'Ruang 2',
                'capacity' => 20,
                'type' => 'laboratorium',
                'description' => 'Ruang Labolatory 2',
            ],
            [
                'name' => 'Ruang 3',
                'capacity' => 30,
                'type' => 'laboratorium',
                'description' => 'Ruang Labolatory 3',
            ],
            [
                'name' => 'Ruang 4',
                'capacity' => 40,
                'type' => 'laboratorium',
                'description' => 'Ruang Labolatory 4',
            ],
            [
                'name' => 'Ruang 5',
                'capacity' => 50,
                'type' => 'laboratorium',
                'description' => 'Ruang Labolatory 5',
            ],
        ];

        foreach ($rooms as $room) {
            \App\Models\Room::create($room);
        }
    }
}
