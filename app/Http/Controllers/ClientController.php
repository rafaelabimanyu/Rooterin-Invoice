<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_client', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('kode_client', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->latest()->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $kode_client = Client::generateCode();
        return view('clients.create', compact('kode_client'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_client' => 'required|unique:clients,kode_client',
            'nama_client' => 'required|string|max:255',
            'nama_perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return redirect()->route('clients.index', ['edit' => $client->id]);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nama_client' => 'required|string|max:255',
            'nama_perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
