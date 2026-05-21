<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChronosController;
use App\Http\Controllers\AiInvoiceController;
use App\Http\Controllers\AiChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
    }
    return redirect()->back();
})->name('lang.switch');

Route::post('/help/password-reset', \App\Http\Controllers\Auth\PasswordResetHelpController::class)->name('password.help');

Route::middleware(['auth', 'verified'])->group(function () {

    // All Roles (Owner, Admin, Staff)
    Route::middleware(['role:owner,admin,staff'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/guide/{section?}', [\App\Http\Controllers\GuideController::class, 'show'])->name('guide.index');

        // Footer Static Pages
        Route::get('/privacy-policy', function () {
            return view('pages.privacy'); })->name('privacy.index');
        Route::get('/terms-of-service', function () {
            return view('pages.terms'); })->name('terms.index');
        Route::get('/help-center', function () {
            return view('pages.help'); })->name('help.index');

        // Clients (Show/Index/Create/Edit)
        Route::resource('clients', ClientController::class);
        Route::post('api/clients', [\App\Http\Controllers\Api\ClientController::class, 'store'])->name('api.clients.store');

        // Invoices
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::post('invoices/{invoice}/ai-email-draft', [AiInvoiceController::class, 'generateEmailDraft'])->name('invoices.ai-email-draft');
        Route::resource('invoices', InvoiceController::class);

        // Security Intelligence
        Route::get('/intelligence', [\App\Http\Controllers\Admin\IntelligenceController::class, 'index'])->name('intelligence.index');
        Route::get('/intelligence/read/{id}', [\App\Http\Controllers\Admin\IntelligenceController::class, 'markAsRead'])->name('intelligence.read');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');


        // Receipts
        Route::get('receipts/{receipt}/pdf', [ReceiptController::class, 'downloadPdf'])->name('receipts.pdf');
        Route::post('receipts/{receipt}/convert', [ReceiptController::class, 'convertToInvoice'])->name('receipts.convert');
        Route::resource('receipts', ReceiptController::class);



        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Chronos (Billing Calendar)
        Route::get('/chronos', [ChronosController::class, 'index'])->name('chronos.index');
        Route::get('/api/chronos/events', [ChronosController::class, 'events'])->name('chronos.events');
        Route::post('/chronos/update-date/{invoice}', [ChronosController::class, 'updateDate'])->name('chronos.update');

        // AI Voice Command Intent Router
        Route::post('ai-assistant/voice-command', [AiChatController::class, 'handleVoiceCommand'])->name('ai-assistant.voice-command');
    });

    // Elevated Roles (Owner, Admin)
    Route::middleware(['role:owner,admin'])->group(function () {
        // User Management
        Route::resource('users', UserManagementController::class);

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Owner KPI Dashboard
        Route::get('/owner-kpi', [\App\Http\Controllers\OwnerKpiController::class, 'index'])->name('owner.kpi');

        // Security Command Center
        Route::get('/security-center', function () {
            return view('security.center');
        })->name('security.center');

        // AI Chatbot Assistant & History
        Route::get('ai-assistant', [AiChatController::class, 'index'])->name('ai-assistant.index');
        Route::post('ai-assistant/chat', [AiChatController::class, 'handleChat'])->name('ai-assistant.chat');
        Route::get('ai-assistant/session/{session_id}', [AiChatController::class, 'getSessionChat'])->name('ai-assistant.session');
        Route::get('ai-assistant/sessions-list', [AiChatController::class, 'getSessionsList'])->name('ai-assistant.sessions-list');
        Route::post('ai-assistant/session/{session_id}/rename', [AiChatController::class, 'renameSession'])->name('ai-assistant.session.rename');
        Route::delete('ai-assistant/session/{session_id}', [AiChatController::class, 'deleteSession'])->name('ai-assistant.session.delete');
        Route::post('ai-assistant/upload', [AiChatController::class, 'handleUpload'])->name('ai-assistant.upload');
        Route::get('ai-assistant/proactive-check', [AiChatController::class, 'proactiveCheck'])->name('ai-assistant.proactive-check');
    });
});

require __DIR__ . '/auth.php';

Route::get('/test-gemini-models', function() {
    try {
        $models = \Gemini\Laravel\Facades\Gemini::models()->list();
        return response()->json($models);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});
