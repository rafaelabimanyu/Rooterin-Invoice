<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChronosController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->hasRole('staff'), 403, 'Unauthorized action.');

        $invoiceQuery = Invoice::query();
        $activityQuery = \App\Models\ActivityLog::query();

        if (auth()->user()->hasRole('staff')) {
            $invoiceQuery->where('created_by', auth()->id());
            $activityQuery->where('user_id', auth()->id());
        }

        $activeArrears = (clone $invoiceQuery)->where('status', 'unpaid')->where('due_date', '<', Carbon::now()->toDateString())->sum('total');
        
        $dueThisWeek = (clone $invoiceQuery)
            ->whereBetween('due_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', '!=', 'paid')
            ->count();
            
        $activities = $activityQuery->latest()->take(5)->get();

        return view('chronos.index', compact('activeArrears', 'dueThisWeek', 'activities'));
    }

    public function events(Request $request)
    {
        abort_if(auth()->user()->hasRole('staff'), 403, 'Unauthorized action.');

        $start = $request->query('start');
        $end = $request->query('end');
        $clientId = $request->query('client_id');
        $status = $request->query('status');
        $staffId = $request->query('staff_id');

        $startDate = $start ? Carbon::parse($start)->toDateString() : null;
        $endDate = $end ? Carbon::parse($end)->toDateString() : null;

        // 1. Query Invoices
        $invoiceQuery = Invoice::with(['client', 'creator']);
        
        if ($startDate && $endDate) {
            $invoiceQuery->whereBetween('due_date', [$startDate, $endDate]);
        }

        // RBAC: Staff only see assigned invoices
        if (auth()->user()->hasRole('staff')) {
            $invoiceQuery->where('created_by', auth()->id());
        } elseif ($staffId) {
            $invoiceQuery->where('created_by', $staffId);
        }

        if ($clientId) {
            $invoiceQuery->where('client_id', $clientId);
        }

        if ($status) {
            $invoiceQuery->where('status', $status);
        }

        $invoices = $invoiceQuery->get();

        // 2. Query ChronosEvents (Reminders)
        $reminderQuery = \App\Models\ChronosEvent::with(['client', 'responsibleStaff']);

        if ($startDate && $endDate) {
            $reminderQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  })
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->whereNull('end_date')
                         ->whereBetween('start_date', [$startDate, $endDate]);
                  });
            });
        }

        // RBAC: Staff only see their own reminders
        if (auth()->user()->hasRole('staff')) {
            $reminderQuery->where('responsible_staff_id', auth()->id());
        } elseif ($staffId) {
            $reminderQuery->where('responsible_staff_id', $staffId);
        }

        if ($clientId) {
            $reminderQuery->where('client_id', $clientId);
        }

        if ($status) {
            $mappedStatus = $status;
            if ($status === 'paid') {
                $mappedStatus = 'meeting';
            }
            $reminderQuery->where('status_type', $mappedStatus);
        }

        $reminders = $reminderQuery->get();

        $events = [];

        // Map invoices
        foreach ($invoices as $invoice) {
            $color = '#3b82f6'; // Blue (default unpaid)
            $statusLabel = 'unpaid';
            if ($invoice->status === 'paid') {
                $color = '#10b981'; // Emerald
                $statusLabel = 'paid';
            } else {
                $isOverdue = $invoice->due_date && $invoice->due_date->isPast();
                if ($isOverdue) {
                    $color = '#f43f5e'; // Rose
                    $statusLabel = 'overdue';
                }
            }

            $events[] = [
                'id' => 'invoice_' . $invoice->id,
                'title' => '[' . strtoupper($statusLabel) . '] ' . $invoice->invoice_number,
                'start' => $invoice->due_date->toDateString(),
                'allDay' => true,
                'color' => $color,
                'editable' => auth()->user()->hasAnyRole(['owner', 'admin']),
                'durationEditable' => false,
                'extendedProps' => [
                    'type' => 'invoice',
                    'dbId' => $invoice->id,
                    'status' => $statusLabel,
                    'total' => 'Rp ' . number_format($invoice->total, 0, ',', '.'),
                    'client' => $invoice->client?->nama_client ?? 'N/A',
                    'responsible_staff' => $invoice->creator?->name ?? 'N/A',
                    'invoice_number' => $invoice->invoice_number,
                ],
            ];
        }

        // Map reminders
        foreach ($reminders as $reminder) {
            $color = '#D4AF37'; // Gold
            if ($reminder->status_type === 'meeting') $color = '#10b981';
            elseif ($reminder->status_type === 'draft') $color = '#f59e0b';
            elseif ($reminder->status_type === 'overdue') $color = '#f43f5e';
            
            if ($reminder->color === 'emerald') $color = '#10b981';
            elseif ($reminder->color === 'amber') $color = '#f59e0b';
            elseif ($reminder->color === 'rose') $color = '#f43f5e';
            elseif ($reminder->color === 'slate') $color = '#94a3b8';
            elseif ($reminder->color === 'indigo' || $reminder->color === 'gold') $color = '#D4AF37';

            $events[] = [
                'id' => 'reminder_' . $reminder->id,
                'title' => $reminder->title,
                'start' => $reminder->start_date->toDateString(),
                'end' => $reminder->end_date ? $reminder->end_date->addDay()->toDateString() : null,
                'allDay' => true,
                'color' => $color,
                'editable' => true,
                'durationEditable' => true,
                'extendedProps' => [
                    'type' => 'reminder',
                    'dbId' => $reminder->id,
                    'status_type' => $reminder->status_type,
                    'description' => $reminder->description ?? '',
                    'client' => $reminder->client?->nama_client ?? 'General',
                    'responsible_staff' => $reminder->responsibleStaff?->name ?? 'N/A',
                ],
            ];
        }

        return response()->json($events);
    }

    public function updateEventDate(Request $request)
    {
        abort_if(auth()->user()->hasRole('staff'), 403, 'Unauthorized action.');

        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'id' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date',
        ]);

        $fullId = $request->input('id');
        $startStr = $request->input('start');
        $endStr = $request->input('end');

        $parts = explode('_', $fullId);
        if (count($parts) < 2) {
            return response()->json(['error' => 'Invalid ID format'], 400);
        }

        $type = $parts[0];
        $dbId = $parts[1];

        $startDate = Carbon::parse($startStr)->toDateString();
        $endDate = $endStr ? Carbon::parse($endStr)->subDay()->toDateString() : null;

        if ($type === 'invoice') {
            if (!auth()->user()->hasAnyRole(['owner', 'admin'])) {
                return response()->json(['error' => 'Unauthorized to modify invoice dates'], 403);
            }

            $invoice = Invoice::find($dbId);
            if (!$invoice) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            $invoice->update([
                'due_date' => $startDate,
            ]);

            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'update_invoice',
                'description' => auth()->user()->name . " memindahkan tanggal jatuh tempo Invoice {$invoice->invoice_number} ke {$startDate} via Kalender.",
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['success' => true, 'message' => 'Invoice date updated successfully']);
        } elseif ($type === 'reminder') {
            $reminder = \App\Models\ChronosEvent::find($dbId);
            if (!$reminder) {
                return response()->json(['error' => 'Reminder not found'], 404);
            }

            if (auth()->user()->hasRole('staff') && $reminder->responsible_staff_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized to modify this reminder'], 403);
            }

            if ($endDate && $endDate < $startDate) {
                $endDate = $startDate;
            }

            $reminder->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            return response()->json(['success' => true, 'message' => 'Reminder date/duration updated successfully']);
        }

        return response()->json(['error' => 'Unknown event type'], 400);
    }
}
