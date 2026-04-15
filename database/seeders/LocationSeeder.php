<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Kantor Pusat (Jakarta)', 'type' => 'office'],
            ['name' => 'Kantor Cabang (Surabaya)', 'type' => 'office'],
            ['name' => 'Tambang 01 - Site Borneo', 'type' => 'mine'],
            ['name' => 'Tambang 02 - Site Sumatra', 'type' => 'mine'],
            ['name' => 'Tambang 03 - Site Sulawesi', 'type' => 'mine'],
            ['name' => 'Tambang 04 - Site Papua', 'type' => 'mine'],
            ['name' => 'Tambang 05 - Site Maluku', 'type' => 'mine'],
            ['name' => 'Tambang 06 - Site Java', 'type' => 'mine'],
        ];
        foreach ($locations as $location) {
            \App\Models\Location::create($location);
        }
    }
}
