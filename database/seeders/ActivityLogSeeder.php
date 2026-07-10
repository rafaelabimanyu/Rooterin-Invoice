<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\SecurityLog;
use App\Models\AiChatHistory;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $users = User::all();
        $clients = Client::all();

        if ($users->isEmpty()) {
            return;
        }

        // 1. Seed AI Chat History
        $aiMessages = [
            [
                'msg' => 'Berapa total piutang jatuh tempo bulan ini?',
                'res' => 'Total piutang jatuh tempo bulan ini adalah Rp 42.500.000 dari 4 invoice. Klien dengan tunggakan terbesar adalah RSUD Harapan Bangsa sebesar Rp 18.000.000.',
            ],
            [
                'msg' => 'Buatkan draft email pengingat pembayaran untuk klien Siti Aminah',
                'res' => "Berikut draft email yang bisa Anda kirim:\n\nSubjek: Pengingat Pembayaran Invoice #JNJ-INV-00012\n\nHalo Ibu Siti,\nKami ingin mengingatkan bahwa tagihan untuk layanan katering Resto Selera Nusantara sebesar Rp 8.400.000 telah melewati batas waktu pembayaran. Silakan lakukan pembayaran ke rekening BCA PT J&J Group Indonesia. Terima kasih.",
            ],
            [
                'msg' => 'Bagaimana performa arus kas J&J GROUP bulan ini?',
                'res' => 'Arus kas bulan ini sangat sehat dengan Collection Rate mencapai 84%. Penerimaan tunai utama berasal dari pelunasan invoice PT Solusi Digital Nusantara.',
            ],
            [
                'msg' => 'Analisis proyeksi keuangan 3 bulan ke depan',
                'res' => 'Berdasarkan data tagihan aktif dan tren historis, proyeksi pendapatan 3 bulan mendatang diperkirakan mencapai Rp 280.000.000 dengan resiko gagal bayar di bawah 5%.',
            ],
            [
                'msg' => 'Buat rekomendasi taktis mengatasi tagihan macet',
                'res' => "1. Terapkan denda keterlambatan 1% per minggu.\n2. Tawarkan cicilan pembayaran bertahap 3x bagi instansi pemerintah.\n3. Integrasikan AI Copywriter untuk otomatisasi email reminder H-3, H+1, dan H+7.",
            ]
        ];

        foreach ($users as $user) {
            if ($user->role !== User::ROLE_STAFF) {
                for ($session = 1; $session <= 2; $session++) {
                    $sessionId = 'sess_' . $faker->bothify('??##??##');
                    foreach ($aiMessages as $chat) {
                        if ($faker->boolean(70)) {
                            AiChatHistory::create([
                                'user_id' => $user->id,
                                'session_id' => $sessionId,
                                'message' => $chat['msg'],
                                'response' => $chat['res'],
                                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 10)),
                            ]);
                        }
                    }
                }
            }
        }

        // 2. Seed Activity Logs & Security Logs
        $actions = [
            'login' => 'Telah masuk ke dalam aplikasi.',
            'create_invoice' => 'Membuat invoice baru dengan nomor JNJ-INV-',
            'update_invoice' => 'Memperbarui status invoice JNJ-INV-',
            'create_client' => 'Mendaftarkan klien baru ',
            'create_receipt' => 'Menerbitkan kwitansi baru JNJ-KWT-',
        ];

        for ($k = 1; $k <= 40; $k++) {
            $user = $users->random();
            $actionKey = array_rand($actions);
            $actionText = $actions[$actionKey];

            if ($actionKey === 'create_invoice' || $actionKey === 'update_invoice') {
                $actionText .= str_pad($faker->numberBetween(1, 30), 5, '0', STR_PAD_LEFT);
            } elseif ($actionKey === 'create_client' && !$clients->isEmpty()) {
                $actionText .= $clients->random()->nama_client;
            } elseif ($actionKey === 'create_receipt') {
                $actionText .= str_pad($faker->numberBetween(1, 10), 5, '0', STR_PAD_LEFT);
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $actionKey,
                'description' => $user->name . ' ' . $actionText,
                'ip_address' => $faker->ipv4,
                'created_at' => Carbon::now()->subHours($faker->numberBetween(1, 100)),
            ]);

            if ($actionKey === 'login') {
                SecurityLog::create([
                    'user_id' => $user->id,
                    'activity' => "User Login: {$user->name} logged in successfully.",
                    'ip_address' => $faker->ipv4,
                    'user_agent' => $faker->userAgent,
                    'location' => $faker->city,
                    'is_suspicious' => false,
                    'created_at' => Carbon::now()->subHours($faker->numberBetween(1, 100)),
                ]);
            }
        }

        // Failed logins
        for ($k = 1; $k <= 3; $k++) {
            SecurityLog::create([
                'user_id' => null,
                'activity' => 'Failed login attempt using email: hacker' . $k . '@hacker.com',
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'location' => $faker->city,
                'is_suspicious' => true,
                'created_at' => Carbon::now()->subHours($faker->numberBetween(1, 50)),
            ]);
        }
    }
}
