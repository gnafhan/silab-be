<?php

namespace Database\Seeders;

use App\Models\Labolatory;
use Illuminate\Database\Seeder;

class LabolatorySeeder extends Seeder
{
    public function run()
    {
        $laboratories = [
            ['name' => 'Laboratorium Rekayasa Perangkat Lunak (RPL)'],
            ['name' => 'Laboratorium Instrumentasi dan Kendali (IDK)'],
            ['name' => 'Laboratorium Teknologi dan Aplikasi Jaringan (TAJ)'],
            ['name' => 'Laboratorium Elektronika'],
            ['name' => 'Laboratorium Teknik Tenaga Listrik (TL)']
        ];

        foreach ($laboratories as $lab) {
            Labolatory::create($lab);
        }
    }
}