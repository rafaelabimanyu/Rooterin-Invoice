<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::where('status', 'aktif')->count();
        $totalInvoices = Invoice::count();
        $paidInvoicesCount = Invoice::where('status', 'paid')->count();
        $pendingInvoicesCount = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->count();
        
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $pendingRevenue = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');
        
        $totalReceipts = \App\Models\Receipt::count();
        $pendingReceipts = \App\Models\Receipt::where('status', 'sent')->count();

        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('tanggal_invoice', Carbon::now()->month)
            ->sum('total');

        $isStaff = auth()->user()->role === 'staff';
        
        if ($isStaff) {
            $todayInvoicesCount = Invoice::where('created_by', auth()->id())
                ->where('created_at', '>=', now()->startOfDay())
                ->count();
            $todayReceiptsCount = \App\Models\Receipt::where('created_by', auth()->id())
                ->where('created_at', '>=', now()->startOfDay())
                ->count();
            $todayRevenue = Invoice::where('created_by', auth()->id())
                ->where('created_at', '>=', now()->startOfDay())
                ->sum('total');
            
            $recentInvoices = Invoice::with('client')
                ->where('created_by', auth()->id())
                ->latest()
                ->take(5)
                ->get();

            // New Staff Features
            $dailyGoal = 5; // Example goal
            $goalProgress = min(100, round(($todayInvoicesCount / $dailyGoal) * 100));
            
            $quotes = [
                "Quality is not an act, it is a habit.",
                "Success is the sum of small efforts, repeated day-in and day-out.",
                "Your work is going to fill a large part of your life.",
                "Don't count the days, make the days count.",
                "Efficiency is doing things right; effectiveness is doing the right things."
            ];
            $randomQuote = $quotes[array_rand($quotes)];

            $activityLogs = \App\Models\ActivityLog::where('user_id', auth()->id())
                ->where('created_at', '>=', now()->startOfDay())
                ->latest()
                ->take(5)
                ->get();
        } else {
            $recentInvoices = Invoice::with('client')->latest()->take(5)->get();
            $todayInvoicesCount = Invoice::where('created_at', '>=', now()->startOfDay())->count();
            $todayReceiptsCount = \App\Models\Receipt::where('created_at', '>=', now()->startOfDay())->count();
            $todayRevenue = Invoice::where('status', 'paid')
                ->where('created_at', '>=', now()->startOfDay())
                ->sum('total');
            
            $dailyGoal = null;
            $goalProgress = null;
            $randomQuote = null;
            $activityLogs = collect();
        }

        return view('dashboard', compact(
            'totalClients', 
            'totalInvoices', 
            'paidInvoicesCount', 
            'pendingInvoicesCount',
            'totalRevenue',
            'pendingRevenue',
            'monthlyRevenue',
            'recentInvoices',
            'totalReceipts',
            'pendingReceipts',
            'isStaff',
            'todayInvoicesCount',
            'todayReceiptsCount',
            'todayRevenue',
            'dailyGoal',
            'goalProgress',
            'randomQuote',
            'activityLogs'
        ));
    }
}
