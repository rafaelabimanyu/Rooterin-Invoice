<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_type' => 'nullable|string|max:100',
            'industry_sector' => 'nullable|string|max:100',
            'nama_client' => 'required|string|max:255',
            'nama_perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['kode_client'] = Client::generateCode();

        $client = Client::create($validated);

        return response()->json([
            'success' => true,
            'client' => $client
        ]);
    }
}
