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
    public $selectedReminderId;
    public $reminderTitle;
    public $reminderDescription;
    public $reminderCategory = 'internal';
    public $reminderColor = 'indigo';
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
        $this->showModal = true;
    }

    public function openEditModal($reminderId)
    {
        $reminder = ChronosEvent::find($reminderId);
        if ($reminder) {
            $this->selectedReminderId = $reminder->id;
            $this->reminderTitle = $reminder->title;
            $this->reminderDescription = $reminder->description;
            $this->reminderCategory = $reminder->category;
            $this->reminderColor = $reminder->color;
            $this->reminderClientId = $reminder->client_id;
            $this->reminderUserId = $reminder->user_id;
            $this->selectedDate = $reminder->event_date->toDateString();
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
            'reminderCategory' => 'required|string|in:internal,meeting,ai_update,other',
            'reminderColor' => 'required|string|in:indigo,emerald,amber,rose,slate',
            'selectedDate' => 'required|date',
        ]);

        $data = [
            'title' => $this->reminderTitle,
            'description' => $this->reminderDescription,
            'category' => $this->reminderCategory,
            'color' => $this->reminderColor,
            'event_date' => $this->selectedDate,
            'client_id' => $this->reminderClientId ?: null,
            'user_id' => $this->reminderUserId ?: auth()->id(),
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
        $this->dispatch('toast', [
            'message' => app()->getLocale() == 'en' ? 'Reminder saved successfully!' : 'Pengingat berhasil disimpan!',
            'type' => 'success'
        ]);
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
    }

    private function resetReminderForm()
    {
        $this->selectedReminderId = null;
        $this->reminderTitle = '';
        $this->reminderDescription = '';
        $this->reminderCategory = 'internal';
        $this->reminderColor = 'indigo';
        $this->reminderClientId = null;
        $this->reminderUserId = null;
    }

    public function render()
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();
        
        $startOfWeek = $startDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
        // Adjust for Monday starting the week (0: Mon, 6: Sun)
        $startOfWeek = ($startOfWeek === 0) ? 6 : $startOfWeek - 1;
        $daysInMonth = $startDate->daysInMonth;

        $days = [];

        // Previous month padding days
        for ($i = 0; $i < $startOfWeek; $i++) {
            $days[] = [
                'date' => null,
                'day' => null,
                'is_today' => false,
                'events' => []
            ];
        }

        // Active month days
        $today = Carbon::today();
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateString = sprintf('%04d-%02d-%02d', $this->year, $this->month, $d);
            $dateObj = Carbon::create($this->year, $this->month, $d);
            
            $days[] = [
                'date' => $dateString,
                'day' => $d,
                'is_today' => $today->isSameDay($dateObj),
                'events' => []
            ];
        }

        // Next month padding days to complete grid (multiples of 7 cells)
        $totalCells = ceil(count($days) / 7) * 7;
        $remainingCells = $totalCells - count($days);
        for ($i = 0; $i < $remainingCells; $i++) {
            $days[] = [
                'date' => null,
                'day' => null,
                'is_today' => false,
                'events' => []
            ];
        }

        // 1. Fetch Invoices matching month and filters
        $invoiceQuery = Invoice::with(['client', 'creator'])
            ->whereBetween('due_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if (auth()->user()->hasRole('staff')) {
            $invoiceQuery->where('created_by', auth()->id());
        }

        if ($this->clientId) {
            $invoiceQuery->where('client_id', $this->clientId);
        }
        if ($this->status) {
            $invoiceQuery->where('status', $this->status);
        }
        if ($this->staffId) {
            $invoiceQuery->where('created_by', $this->staffId);
        }

        $invoices = $invoiceQuery->get();

        // 2. Fetch Custom Reminders matching month and filters
        $reminderQuery = ChronosEvent::with(['client', 'user'])
            ->whereBetween('event_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if (auth()->user()->hasRole('staff')) {
            $reminderQuery->where('user_id', auth()->id());
        }

        if ($this->clientId) {
            $reminderQuery->where('client_id', $this->clientId);
        }
        if ($this->staffId) {
            $reminderQuery->where('user_id', $this->staffId);
        }

        $reminders = $reminderQuery->get();

        // Group events by date
        $eventsByDate = [];
        foreach ($invoices as $inv) {
            $dateKey = $inv->due_date->toDateString();
            $color = 'slate';
            if ($inv->status === 'paid') $color = 'emerald';
            elseif ($inv->status === 'overdue') $color = 'rose';
            elseif ($inv->status === 'draft') $color = 'amber';

            $eventsByDate[$dateKey][] = [
                'id' => $inv->id,
                'type' => 'invoice',
                'title' => '[' . strtoupper($inv->status) . '] ' . $inv->invoice_number,
                'color' => $color,
                'client_name' => $inv->client?->nama_client ?? 'N/A',
            ];
        }

        foreach ($reminders as $rem) {
            $dateKey = $rem->event_date->toDateString();
            $eventsByDate[$dateKey][] = [
                'id' => $rem->id,
                'type' => 'reminder',
                'title' => $rem->title,
                'color' => $rem->color, // indigo, emerald, amber, rose, slate
                'client_name' => $rem->client?->nama_client ?? null,
            ];
        }

        // Attach events to their respective days
        foreach ($days as &$day) {
            if ($day['date'] && isset($eventsByDate[$day['date']])) {
                $day['events'] = $eventsByDate[$day['date']];
            }
        }

        return view('livewire.chronos-calendar', [
            'days' => $days,
            'clients' => Client::orderBy('nama_client')->get(),
            'staffs' => User::whereIn('role', ['admin', 'staff'])->get(),
        ]);
    }
}
