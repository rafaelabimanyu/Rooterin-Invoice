<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\User;

class ChronosCalendar extends Component
{
    public $clientId;
    public $status;
    public $staffId;

    public function render()
    {
        return view('livewire.chronos-calendar', [
            'clients' => Client::orderBy('nama_client')->get(),
            'staffs' => User::whereIn('role', ['admin', 'staff'])->get(),
        ]);
    }

    public function updatedFilters()
    {
        $this->dispatch('filtersUpdated', [
            'clientId' => $this->clientId,
            'status' => $this->status,
            'staffId' => $this->staffId,
        ]);
    }
}
