<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use Illuminate\Database\Seeder;

class BusinessUnitSeeder extends Seeder
{
    public function run(): void
    {
        $businessUnitsData = [
            [
                'name' => 'Jaya-Website',
                'description' => 'Layanan Pembuatan & Optimasi Website Jaya',
                'is_active' => true,
                'fee_percentage' => 10.00,
            ],
            [
                'name' => 'Jaya-Sosmed',
                'description' => 'Layanan Pemasaran Sosial Media & Konten Jaya',
                'is_active' => true,
                'fee_percentage' => 15.00,
            ],
            [
                'name' => 'Jaya-Operational',
                'description' => 'Layanan Operasional Lapangan & Teknis Jaya',
                'is_active' => true,
                'fee_percentage' => 20.00,
            ],
        ];

        foreach ($businessUnitsData as $data) {
            BusinessUnit::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                $data
            );
        }
    }
}
