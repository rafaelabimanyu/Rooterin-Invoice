<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class ClientManager extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $showEditModal = false;
    public $showViewModal = false;
    public $editingClient = null;
    public $viewingClient = null;
    
    // Form fields
    public $nama_client, $nama_perusahaan, $email, $no_hp, $npwp, $alamat, $kota, $provinsi, $catatan, $status_field, $kode_client;

    protected $queryString = ['search', 'status'];

    public function mount()
    {
        if (request()->has('edit')) {
            $client = Client::find(request()->edit);
            if ($client) {
                $this->openEdit($client);
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->resetFields();
        $this->kode_client = Client::generateCode();
        $this->status_field = 'aktif';
        $this->showEditModal = true;
        $this->editingClient = null;
    }

    public function openEdit(Client $client)
    {
        $this->editingClient = $client;
        $this->nama_client = $client->nama_client;
        $this->nama_perusahaan = $client->nama_perusahaan;
        $this->email = $client->email;
        $this->no_hp = $client->no_hp;
        $this->npwp = $client->npwp;
        $this->alamat = $client->alamat;
        $this->kota = $client->kota;
        $this->provinsi = $client->provinsi;
        $this->catatan = $client->catatan;
        $this->status_field = $client->status;
        $this->kode_client = $client->kode_client;
        $this->showEditModal = true;
    }

    public function openView(Client $client)
    {
        $this->viewingClient = $client;
        
        $content = "
            <div class='space-y-8'>
                <div class='grid grid-cols-2 gap-8'>
                    <div>
                        <p class='text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1'>Entity Code</p>
                        <p class='text-sm font-bold text-slate-900'>{$client->kode_client}</p>
                    </div>
                    <div>
                        <p class='text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1'>Status</p>
                        <span class='px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest " . ($client->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400') . "'>{$client->status}</span>
                    </div>
                </div>

                <div>
                    <p class='text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1'>Company / Enterprise</p>
                    <p class='text-sm font-bold text-slate-900'>" . ($client->nama_perusahaan ?? 'Personal Account') . "</p>
                </div>

                <div class='grid grid-cols-2 gap-8'>
                    <div>
                        <p class='text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1'>Communication Channel</p>
                        <p class='text-sm font-bold text-slate-900'>{$client->email}</p>
                        <p class='text-[12px] text-slate-500'>{$client->no_hp}</p>
                    </div>
                    <div>
                        <p class='text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1'>Regional Hub</p>
                        <p class='text-sm font-bold text-slate-900'>{$client->kota}, {$client->provinsi}</p>
                    </div>
                </div>

                <div class='pt-6 border-t border-slate-100'>
                    <div class='flex items-center justify-between mb-4'>
                        <h4 class='text-[11px] font-black text-slate-900 uppercase tracking-widest'>Billing Summary</h4>
                        <a href='/clients/{$client->id}' class='text-[10px] font-black text-indigo-600 uppercase tracking-widest'>Open Full History</a>
                    </div>
                    <div class='grid grid-cols-2 gap-4'>
                        <div class='p-4 bg-slate-50 rounded-2xl'>
                            <p class='text-[9px] font-bold text-slate-400 uppercase'>Total Invoices</p>
                            <p class='text-lg font-black text-slate-900'>" . $client->invoices()->count() . "</p>
                        </div>
                        <div class='p-4 bg-slate-50 rounded-2xl'>
                            <p class='text-[9px] font-bold text-slate-400 uppercase'>Total Revenue</p>
                            <p class='text-lg font-black text-slate-900'>Rp " . number_format($client->invoices()->sum('total'), 0, ',', '.') . "</p>
                        </div>
                    </div>
                </div>
            </div>
        ";

        $this->dispatch('open-slide-over', title: $client->nama_client, content: $content);
    }

    public function save()
    {
        $rules = [
            'nama_client' => 'required|string|max:255',
            'nama_perusahaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'status_field' => 'required|in:aktif,nonaktif',
        ];

        if (!$this->editingClient) {
            $rules['kode_client'] = 'required|unique:clients,kode_client';
        }

        $validated = $this->validate($rules);
        $validated['status'] = $this->status_field;

        if ($this->editingClient) {
            $this->editingClient->update($validated);
            $this->dispatch('notify', ['message' => 'Client updated successfully.', 'type' => 'success']);
        } else {
            Client::create($validated);
            $this->dispatch('notify', ['message' => 'Client registered successfully.', 'type' => 'success']);
        }

        $this->showEditModal = false;
        $this->resetFields();
    }

    public function delete(Client $client)
    {
        $client->delete();
        $this->dispatch('notify', ['message' => 'Client removed from registry.', 'type' => 'success']);
    }

    private function resetFields()
    {
        $this->nama_client = '';
        $this->nama_perusahaan = '';
        $this->email = '';
        $this->no_hp = '';
        $this->npwp = '';
        $this->alamat = '';
        $this->kota = '';
        $this->provinsi = '';
        $this->catatan = '';
        $this->status_field = 'aktif';
        $this->kode_client = '';
    }

    public function getInitial($name)
    {
        $words = explode(' ', $name);
        $initial = '';
        foreach ($words as $w) {
            $initial .= strtoupper(substr($w, 0, 1));
            if (strlen($initial) >= 2) break;
        }
        return $initial ?: '??';
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->search, function($q) {
                $q->where(function($inner) {
                    $inner->where('nama_client', 'like', '%' . $this->search . '%')
                          ->orWhere('nama_perusahaan', 'like', '%' . $this->search . '%')
                          ->orWhere('kode_client', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(12);

        return view('livewire.client-manager', [
            'clients' => $clients
        ]);
    }
}
