<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $clientsData = [
            [
                'nama_client' => 'Budi Santoso',
                'nama_perusahaan' => 'Rumahan / Umum',
                'client_type' => 'individual',
                'industry_sector' => 'general',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Siti Aminah',
                'nama_perusahaan' => 'Resto Selera Nusantara',
                'client_type' => 'individual',
                'industry_sector' => 'fnb',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'nama_client' => 'Dr. H. Faisal',
                'nama_perusahaan' => 'RSUD Harapan Bangsa',
                'client_type' => 'government',
                'industry_sector' => 'healthcare',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
            ],
            [
                'nama_client' => 'Prof. Joko Susilo',
                'nama_perusahaan' => 'Yayasan Pendidikan Mulia',
                'client_type' => 'Yayasan',
                'industry_sector' => 'education',
                'kota' => 'Semarang',
                'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama_client' => 'John Doe',
                'nama_perusahaan' => 'Global Tech Solutions Inc.',
                'client_type' => 'foreign',
                'industry_sector' => 'tech',
                'kota' => 'Singapore',
                'provinsi' => 'Singapore',
            ],
            [
                'nama_client' => 'Andi Wijaya',
                'nama_perusahaan' => 'PT Solusi Digital Nusantara',
                'client_type' => 'corporate',
                'industry_sector' => 'tech',
                'kota' => 'Jakarta Barat',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Hendra Setiawan',
                'nama_perusahaan' => 'PT Sinar Agung Pertekstilan',
                'client_type' => 'corporate',
                'industry_sector' => 'manufacturing',
                'kota' => 'Solo',
                'provinsi' => 'Jawa Tengah',
            ],
            [
                'nama_client' => 'Supriyadi',
                'nama_perusahaan' => 'Pabrik Semen Perkasa',
                'client_type' => 'corporate',
                'industry_sector' => 'manufacturing',
                'kota' => 'Gresik',
                'provinsi' => 'Jawa Timur',
            ],
            [
                'nama_client' => 'drg. Ratih Rahmawati',
                'nama_perusahaan' => 'Klinik Gigi Medika Utama',
                'client_type' => 'corporate',
                'industry_sector' => 'healthcare',
                'kota' => 'Tangerang',
                'provinsi' => 'Banten',
            ],
            [
                'nama_client' => 'Reza Pahlevi',
                'nama_perusahaan' => 'Kopi Kenangan Senja Group',
                'client_type' => 'corporate',
                'industry_sector' => 'fnb',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Bambang Pamungkas',
                'nama_perusahaan' => 'Koperasi Tani Makmur',
                'client_type' => 'Koperasi',
                'industry_sector' => 'Pertanian',
                'kota' => 'Malang',
                'provinsi' => 'Jawa Timur',
            ],
            [
                'nama_client' => 'Dewi Lestari',
                'nama_perusahaan' => 'Dinas Kesehatan Kota Bandung',
                'client_type' => 'government',
                'industry_sector' => 'healthcare',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'nama_client' => 'Agus Rahman',
                'nama_perusahaan' => 'Yayasan Kasih Ibu',
                'client_type' => 'Yayasan',
                'industry_sector' => 'healthcare',
                'kota' => 'Yogyakarta',
                'provinsi' => 'DI Yogyakarta',
            ],
            [
                'nama_client' => 'Wawan Kurniawan',
                'nama_perusahaan' => 'CV Abadi Sentosa',
                'client_type' => 'corporate',
                'industry_sector' => 'general',
                'kota' => 'Medan',
                'provinsi' => 'Sumatera Utara',
            ],
            [
                'nama_client' => 'Kenji Tanaka',
                'nama_perusahaan' => 'Tokyo Food Import Ltd',
                'client_type' => 'foreign',
                'industry_sector' => 'fnb',
                'kota' => 'Tokyo',
                'provinsi' => 'Japan',
            ],
            [
                'nama_client' => 'Sri Mulyani',
                'nama_perusahaan' => 'SD Negeri 01 Menteng',
                'client_type' => 'government',
                'industry_sector' => 'education',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
            ],
            [
                'nama_client' => 'Rahmat Hidayat',
                'nama_perusahaan' => 'PT Megah Beton',
                'client_type' => 'corporate',
                'industry_sector' => 'manufacturing',
                'kota' => 'Makassar',
                'provinsi' => 'Sulawesi Selatan',
            ],
            [
                'nama_client' => 'Linda Kartika',
                'nama_perusahaan' => 'PT Dirgantara Aero',
                'client_type' => 'corporate',
                'industry_sector' => 'tech',
                'kota' => 'Depok',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'nama_client' => 'Achmad Zaky',
                'nama_perusahaan' => 'Klinik Gigi Dental Care',
                'client_type' => 'individual',
                'industry_sector' => 'healthcare',
                'kota' => 'Palembang',
                'provinsi' => 'Sumatera Selatan',
            ],
            [
                'nama_client' => 'Yusuf Mansur',
                'nama_perusahaan' => 'Katering Berkah Mandiri',
                'client_type' => 'individual',
                'industry_sector' => 'fnb',
                'kota' => 'Balikpapan',
                'provinsi' => 'Kalimantan Timur',
            ],
        ];

        foreach ($clientsData as $data) {
            Client::updateOrCreate(
                ['email' => $data['nama_client'] . '@example.com'], // placeholder key, but let's generate a unique email
                [
                    'kode_client' => Client::generateCode(),
                    'nama_client' => $data['nama_client'],
                    'nama_perusahaan' => $data['nama_perusahaan'],
                    'client_type' => $data['client_type'],
                    'industry_sector' => $data['industry_sector'],
                    'email' => $faker->unique()->companyEmail,
                    'no_hp' => '08' . $faker->numerify('##########'),
                    'npwp' => $faker->numerify('##.###.###.#-###.###'),
                    'alamat' => $faker->address,
                    'kota' => $data['kota'],
                    'provinsi' => $data['provinsi'],
                    'status' => $faker->randomElement(['aktif', 'aktif', 'aktif', 'nonaktif']),
                ]
            );
        }
    }
}
