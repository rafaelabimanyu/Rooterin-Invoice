<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Proteksi level controller dari manipulasi request/link
        // Memastikan staff tidak bisa mengakses/memaksa render panduan Owner
        $requestedRole = $request->get('role');
        if ($requestedRole === 'owner' && !$user->hasFullAccess()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki otorisasi untuk melihat panduan Owner.');
        }

        // Tentukan role mana yang dirender
        $activeRole = $user->hasFullAccess() ? 'owner' : 'staff';
        
        // Owner bisa melakukan preview guide staff jika menambahkan param ?role=staff
        if ($user->hasFullAccess() && $requestedRole === 'staff') {
            $activeRole = 'staff'; 
        }

        // Boilerplate struktur data (Array) untuk memisahkan konten teks (sesuai permintaan)
        // Struktur ini membuat pengelolaan teks lebih modular di masa depan.
        $guides = [
            'staff' => [
                'header' => [
                    'title' => 'SOP Operasional Harian',
                    'subtitle' => 'Panduan teknis dan alur kerja harian untuk staf operasional Rooterin.',
                ],
                'steps' => [
                    ['label' => 'Input Client', 'step' => 'Step 1'],
                    ['label' => 'Buat Invoice', 'step' => 'Step 2'],
                    ['label' => 'Kirim PDF', 'step' => 'Step 3'],
                    ['label' => 'Catat Pembayaran', 'step' => 'Step 4'],
                ],
                'menus' => [
                    ['id' => 'getting-started', 'label' => 'Getting Started'],
                    ['id' => 'clients', 'label' => '1. Input Client'],
                    ['id' => 'invoices', 'label' => '2. Pembuatan Invoice'],
                    ['id' => 'send-pdf', 'label' => '3. Pengiriman PDF'],
                ]
            ],
            'owner' => [
                'header' => [
                    'title' => 'Rooterin Executive Guide',
                    'subtitle' => 'Panduan komprehensif tingkat lanjut untuk analisis, pengaturan sistem, dan keputusan strategis.',
                ],
                'steps' => [
                    ['label' => 'Pantau KPI', 'step' => 'Step 1'],
                    ['label' => 'Cek Laporan', 'step' => 'Step 2'],
                    ['label' => 'Review Cashflow', 'step' => 'Step 3'],
                    ['label' => 'Kelola Sistem', 'step' => 'Step 4'],
                ],
                'menus' => [
                    ['id' => 'getting-started', 'label' => 'Getting Started'],
                    ['id' => 'owner-kpi', 'label' => '1. Analisis Owner KPI'],
                    ['id' => 'reports', 'label' => '2. Laporan Keuangan'],
                    ['id' => 'settings', 'label' => '3. System Settings'],
                    ['id' => 'user-management', 'label' => '4. Manajemen User'],
                ]
            ]
        ];

        $guideData = $guides[$activeRole];

        return view('guide.index', compact('guideData', 'activeRole'));
    }
}
