<?php

namespace Database\Seeders;

use App\Models\SubKriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubKriteriaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kriteria_id'  => 1,
                'name'  => 'S3 (Doktor)',
                'bobot' => 4,
            ],
            [
                'kriteria_id'  => 1,
                'name'  => 'S2 (Magister)',
                'bobot' => 3,
            ],
            [
                'kriteria_id'  => 1,
                'name'  => 'S1 (Sarjana)',
                'bobot' => 2,
            ],
            [
                'kriteria_id'  => 1,
                'name'  => 'D3/SMA/Lainnya',
                'bobot' => 1,
            ],
            [
                'kriteria_id'  => 2,
                'name'  => '≥ 3.75',
                'bobot' => 4,
            ],
            [
                'kriteria_id'  => 2,
                'name'  => '3.25 - 3.74',
                'bobot' => 3,
            ],
            [
                'kriteria_id'  => 2,
                'name'  => '2.75 - 3.24',
                'bobot' => 2,
            ],
            [
                'kriteria_id'  => 2,
                'name'  => '< 2.75',
                'bobot' => 1,
            ],
            [
                'kriteria_id'  => 3,
                'name'  => '≥ 5',
                'bobot' => 4,
            ],
            [
                'kriteria_id'  => 3,
                'name'  => '3 - 4.99',
                'bobot' => 3,
            ],
            [
                'kriteria_id'  => 3,
                'name'  => '1 - 2.99',
                'bobot' => 2,
            ],
            [
                'kriteria_id'  => 3,
                'name'  => '< 1',
                'bobot' => 1,
            ],
            [
                'kriteria_id'  => 4,
                'name'  => '≥ 3',
                'bobot' => 4,
            ],
            [
                'kriteria_id'  => 4,
                'name'  => '2',
                'bobot' => 3,
            ],
            [
                'kriteria_id'  => 4,
                'name'  => '1',
                'bobot' => 2,
            ],
            [
                'kriteria_id'  => 4,
                'name'  => '0',
                'bobot' => 1,
            ],
            [
                'kriteria_id'  => 5,
                'name'  => '≥ 85',
                'bobot' => 4,
            ],
            [
                'kriteria_id'  => 5,
                'name'  => '70 - 84',
                'bobot' => 3,
            ],
            [
                'kriteria_id'  => 5,
                'name'  => '50 - 69',
                'bobot' => 2,
            ],
            [
                'kriteria_id'  => 5,
                'name'  => '< 50',
                'bobot' => 1,
            ],
            [
                'kriteria_id'  => 6,
                'name'  => '≥ 4.5',
                'bobot' => 4,
            ],
            [
                'kriteria_id'  => 6,
                'name'  => '3.5 - 4.49',
                'bobot' => 3,
            ],
            [
                'kriteria_id'  => 6,
                'name'  => '2.5 - 3.49',
                'bobot' => 2,
            ],
            [
                'kriteria_id'  => 6,
                'name'  => '< 2.5',
                'bobot' => 1,
            ],
        ];

        foreach ($data as $item) {
            SubKriteria::create($item);
        }
    }
}
