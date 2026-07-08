<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\User;
use App\Models\Invoice;
use App\Models\ChronosEvent;
use Carbon\Carbon;

class ChronosCalendar extends Component
{
    // Filter properties
    public $clientId;
    public $status;
    public $staffId;

    // Calendar navigation
    public $month;
    public $year;

    // Modal state for custom reminders
    public $showModal = false;
    public $showInvoiceModal = false;
    
    // Form fields for custom reminders
    public $selectedDate;
    public $selectedEndDate;
    public $selectedReminderId;
    public $reminderTitle;
    public $reminderDescription;
    public $reminderCategory = 'internal';
    public $reminderColor = 'gold';
    public $reminderClientId;
    public $reminderUserId;

    // Viewing details of Invoice
    public $viewedInvoice;

    protected $listeners = ['refreshCalendar' => '$refresh'];

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function prevMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function openAddModal($dateString)
    {
        $this->resetReminderForm();
        $this->selectedDate = $dateString;
        $this->selectedEndDate = $dateString;
        $this->showModal = true;
    }

    public function openEditModal($reminderId)
    {
        $reminder = ChronosEvent::find($reminderId);
        if ($reminder) {
            $this->selectedReminderId = $reminder->id;
            $this->reminderTitle = $reminder->title;
            $this->reminderDescription = $reminder->description;
            $this->reminderCategory = $reminder->status_type;
            $this->reminderColor = $reminder->color;
            $this->reminderClientId = $reminder->client_id;
            $this->reminderUserId = $reminder->responsible_staff_id;
            $this->selectedDate = $reminder->start_date->toDateString();
            $this->selectedEndDate = $reminder->end_date ? $reminder->end_date->toDateString() : $reminder->start_date->toDateString();
            $this->showModal = true;
        }
    }

    public function viewInvoiceDetails($invoiceId)
    {
        $this->viewedInvoice = Invoice::with(['client', 'creator'])->find($invoiceId);
        if ($this->viewedInvoice) {
            $this->showInvoiceModal = true;
        }
    }

    public function saveReminder()
    {
        $this->validate([
            'reminderTitle' => 'required|string|max:255',
            'reminderDescription' => 'nullable|string',
            'reminderCategory' => 'required|string|in:internal,meeting,draft,overdue,ai_update,other',
            'reminderColor' => 'required|string|in:gold,indigo,emerald,amber,rose,slate',
            'selectedDate' => 'required|date',
            'selectedEndDate' => 'nullable|date|after_or_equal:selectedDate',
        ]);

        $data = [
            'title' => $this->reminderTitle,
            'description' => $this->reminderDescription,
            'status_type' => $this->reminderCategory,
            'color' => $this->reminderColor,
            'start_date' => $this->selectedDate,
            'end_date' => $this->selectedEndDate ?: $this->selectedDate,
            'client_id' => $this->reminderClientId ?: null,
            'responsible_staff_id' => $this->reminderUserId ?: auth()->id(),
        ];

        if ($this->selectedReminderId) {
            $reminder = ChronosEvent::find($this->selectedReminderId);
            if ($reminder) {
                $reminder->update($data);
            }
        } else {
            ChronosEvent::create($data);
        }

        $this->showModal = false;
        $this->resetReminderForm();
        
        $this->dispatch('reminderSaved', [
            'message' => app()->getLocale() == 'en' ? 'Reminder saved successfully!' : 'Pengingat berhasil disimpan!'
        ]);
        
        $this->dispatch('refreshCalendar');
    }

    public function deleteReminder($reminderId)
    {
        $reminder = ChronosEvent::find($reminderId);
        if ($reminder) {
            $reminder->delete();
        }

        $this->showModal = false;
        $this->resetReminderForm();
        $this->dispatch('toast', [
            'message' => app()->getLocale() == 'en' ? 'Reminder deleted successfully!' : 'Pengingat berhasil dihapus!',
            'type' => 'success'
        ]);
        
        $this->dispatch('refreshCalendar');
    }

    private function resetReminderForm()
    {
        $this->selectedReminderId = null;
        $this->reminderTitle = '';
        $this->reminderDescription = '';
        $this->reminderCategory = 'internal';
        $this->reminderColor = 'gold';
        $this->reminderClientId = null;
        $this->reminderUserId = null;
        $this->selectedDate = null;
        $this->selectedEndDate = null;
    }

    public function render()
    {
        return view('livewire.chronos-calendar', [
            'clients' => Client::orderBy('nama_client')->get(),
            'staffs' => User::whereIn('role', ['admin', 'staff'])->get(),
        ]);
    }
}
