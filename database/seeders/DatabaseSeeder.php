<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoomSeeder::class,
            LabolatorySeeder::class,
            InventorySeeder::class,
            PengadaanSeeder::class,
            // ItemPengadaanSeeder::class,
        ]);
    }
}
