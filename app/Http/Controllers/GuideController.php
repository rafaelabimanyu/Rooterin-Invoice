<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function show($section = null)
    {
        if (request()->query('type') === 'sop') {
            $filePath = resource_path('docs/user_guide.md');
            if (file_exists($filePath)) {
                $markdown = file_get_contents($filePath);
                $content = \Illuminate\Support\Str::markdown($markdown);
                return view('guide.user_guide', compact('content'));
            }
        }

        $user = auth()->user();
        $role = $user->role; // 'owner', 'admin', 'staff'
        
        // Ambil data dokumentasi dari file trans (lang) berdasarkan bahasa aktif, dengan fallback ke config
        $docs = __('guide.roles');
        if (!is_array($docs)) {
            $docs = config('docs.roles');
        }
        
        if (!isset($docs[$role])) {
            abort(403, 'Dokumentasi untuk role ini tidak tersedia.');
        }

        $guideData = $docs[$role];
        
        // Logika Proteksi & RBAC Server-side (403)
        // Memastikan Staff tidak bisa mengakses section Owner (misal: /guide/owner-kpi)
        if ($section) {
            if (!array_key_exists($section, $guideData['navigation'])) {
                abort(403, 'Akses ditolak: Anda tidak memiliki otorisasi untuk melihat panduan bagian ini.');
            }
            $activeSectionKey = $section;
        } else {
            // Jika tidak ada section di URL, gunakan section pertama sebagai default
            $activeSectionKey = array_key_first($guideData['navigation']);
        }

        $activeSectionData = $guideData['navigation'][$activeSectionKey];

        return view('guide.index', compact('guideData', 'activeSectionKey', 'activeSectionData', 'role'));
    }
}
