<?php

namespace Database\Seeders;

use App\Models\Pengadaan;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PengadaanSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Pengadaan::create([
                'item_name' => $faker->word,
                'spesifikasi' => $faker->sentence,
                'jumlah' => $faker->numberBetween(1, 10),
                'harga_item' => $faker->numberBetween(100000, 1000000),
                'bulan_pengadaan' => $faker->dateTimeBetween('-1 year', 'now'),
                'labolatory_id' => rand(1, 4),
            ]);
        }
    }
}