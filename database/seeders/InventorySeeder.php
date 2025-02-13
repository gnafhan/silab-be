<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\User;
use App\Models\Room;
use App\Models\Labolatory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $user = User::first() ?? User::factory()->create();
        
        // Get existing room and laboratory IDs
        $roomIds = Room::pluck('id')->toArray();
        $labIds = Labolatory::pluck('id')->toArray();
        
        // Only proceed if we have rooms and laboratories
        if (empty($roomIds) || empty($labIds)) {
            echo "Please seed rooms and laboratories tables first.\n";
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            Inventory::create([
                'item_name' => $faker->word,
                'no_item' => $faker->unique()->numberBetween(1000, 9999),
                'condition' => $faker->randomElement(['good', 'bad']),
                'alat/bhp' => $faker->randomElement(['alat', 'bhp']),
                'no_inv_ugm' => $faker->unique()->numerify('UGM-####'),
                'information' => $faker->sentence,
                'room_id' => $faker->randomElement($roomIds),
                'labolatory_id' => $faker->randomElement($labIds),
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }
    }
}
