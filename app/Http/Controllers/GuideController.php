<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function show($section = null)
    {
        if (request()->query('type') === 'sop') {
            abort_if(auth()->user()->role === 'staff', 403, 'Akses ditolak.');
            return redirect()->route('guide.sop');
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

    public function showSop()
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Akses ditolak: Staff tidak memiliki akses ke SOP.');
        return view('guide.user_guide');
    }
}
