<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code'  => 'C1',
                'name'  => 'Pendidikan Terakhir',
                // 'bobot' => 15,
                'bobot' => 40,
            ],
            [
                'code'  => 'C2',
                'name'  => 'IPK/Nilai Akhir',
                // 'bobot' => 20,
                'bobot' => 30,
            ],
            [
                'code'  => 'C3',
                'name'  => 'Pengalaman Kerja',
                // 'bobot' => 25,
                'bobot' => 30,
            ],
            // [
            //     'code'  => 'C4',
            //     'name'  => 'Sertifikasi/Prestasi',
            //     'bobot' => 10,
            // ],
            // [
            //     'code'  => 'C5',
            //     'name'  => 'HardSkill',
            //     'bobot' => 20,
            // ],
            // [
            //     'code'  => 'C6',
            //     'name'  => 'SoftSkill',
            //     'bobot' => 10,
            // ],
        ];

        foreach ($data as $item) {
            Kriteria::create($item);
        }
    }
}
