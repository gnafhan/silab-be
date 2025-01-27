<?php

namespace Database\Seeders;

use App\Models\Labolatory;
use Illuminate\Database\Seeder;

class LabolatorySeeder extends Seeder
{
    public function run()
    {
        $laboratories = [
            ['name' => 'Computer Lab'],
            ['name' => 'Physics Lab'],
            ['name' => 'Chemistry Lab'],
            ['name' => 'Biology Lab'],
        ];

        foreach ($laboratories as $lab) {
            Labolatory::create($lab);
        }
    }
}