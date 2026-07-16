<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\BusinessUnit;
use App\Services\DataAggregatorService;
use App\Services\AutoReportService;
use App\Livewire\DashboardMorningBriefing;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Carbon\Carbon;

class ProactiveIntelligenceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure daily_reports directory clean state
        $dir = storage_path('app/daily_reports');
        if (File::exists($dir)) {
            File::deleteDirectory($dir);
        }

        // Clean urgent_alerts.log
        $logPath = storage_path('logs/urgent_alerts.log');
        if (File::exists($logPath)) {
            File::delete($logPath);
        }
    }

    public function test_trend_calculation_math()
    {
        $unit = BusinessUnit::create(['name' => 'IT Department', 'fee_percentage' => 10.0]);
        $client = Client::create([
            'kode_client' => 'CL-001',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'nama_client' => 'Budi',
            'status' => 'aktif'
        ]);
        
        // Prev month revenue = 20,000,000
        $inv1 = Invoice::create([
            'invoice_number' => 'INV-001',
            'business_unit_id' => $unit->id,
            'client_id' => $client->id,
            'status' => 'paid',
            'subtotal' => 20000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 20000000,
            'due_date' => Carbon::now()->subMonth()->endOfMonth()
        ]);
        DB::table('invoices')->where('id', $inv1->id)->update([
            'created_at' => Carbon::now()->subMonth()->startOfMonth()->toDateTimeString()
        ]);

        // Curr month revenue = 23,000,000 (15% growth)
        $inv2 = Invoice::create([
            'invoice_number' => 'INV-002',
            'business_unit_id' => $unit->id,
            'client_id' => $client->id,
            'status' => 'paid',
            'subtotal' => 23000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 23000000,
            'due_date' => Carbon::now()->endOfMonth()
        ]);
        DB::table('invoices')->where('id', $inv2->id)->update([
            'created_at' => Carbon::now()->startOfMonth()->toDateTimeString()
        ]);

        $trend = app(DataAggregatorService::class)->getRevenueTrend('id');
        
        $this->assertEquals(23000000.0, $trend['current_revenue']);
        $this->assertEquals(20000000.0, $trend['previous_revenue']);
        $this->assertEquals(15.0, $trend['growth_percent']);
        $this->assertStringContainsString('naik 15', $trend['insight']);
    }

    public function test_auto_report_generation_and_storage()
    {
        $unit = BusinessUnit::create(['name' => 'Design', 'fee_percentage' => 10.0]);
        $client = Client::create([
            'kode_client' => 'CL-002',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'nama_client' => 'Siti',
            'status' => 'aktif'
        ]);

        $inv = Invoice::create([
            'invoice_number' => 'INV-003',
            'business_unit_id' => $unit->id,
            'client_id' => $client->id,
            'status' => 'paid',
            'subtotal' => 15000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 15000000,
            'due_date' => Carbon::now()->addDays(30)
        ]);
        DB::table('invoices')->where('id', $inv->id)->update([
            'created_at' => Carbon::now()->toDateTimeString()
        ]);

        $reportService = app(AutoReportService::class);
        $reportService->generateDailyReport();

        $filePath = storage_path('app/daily_reports/morning_briefing.json');
        $this->assertFileExists($filePath);

        $report = $reportService->getLatestReport('id');
        $this->assertNotNull($report);
        $this->assertEquals(15000000.0, $report['total_revenue']);
        $this->assertStringContainsString('Total Pendapatan Terkumpul', $report['text']);
    }

    public function test_urgent_overdue_alerts_logging_and_notifications()
    {
        $unit = BusinessUnit::create(['name' => 'Consulting', 'fee_percentage' => 10.0]);
        $client = Client::create([
            'kode_client' => 'CL-003',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'nama_client' => 'Agus',
            'status' => 'aktif'
        ]);
        
        // Overdue invoice with Rp 15.000.000 (exceeds Rp 10.000.000)
        $invoice = Invoice::create([
            'invoice_number' => 'INV-004',
            'business_unit_id' => $unit->id,
            'client_id' => $client->id,
            'status' => 'overdue',
            'subtotal' => 15000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 15000000,
            'due_date' => Carbon::now()->subDays(15)
        ]);
        DB::table('invoices')->where('id', $invoice->id)->update([
            'created_at' => Carbon::now()->subDays(45)->toDateTimeString()
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $reportService = app(AutoReportService::class);
        $reportService->checkUrgentOverdueInvoices();

        // 1. Check file logging
        $logPath = storage_path('logs/urgent_alerts.log');
        $this->assertFileExists($logPath);
        $logContent = File::get($logPath);
        $this->assertStringContainsString('INV-004', $logContent);
        $this->assertStringContainsString('overdue', $logContent);

        // 2. Check Database notification
        $this->assertEquals(1, $admin->notifications()->count());
        $notification = $admin->notifications()->first();
        $this->assertEquals('overdue', $notification->data['type']);
        $this->assertStringContainsString('INV-004', $notification->data['message']);
    }

    public function test_livewire_morning_briefing_component()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $unit = BusinessUnit::create(['name' => 'Consulting', 'fee_percentage' => 10.0]);
        $client = Client::create([
            'kode_client' => 'CL-004',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'nama_client' => 'Agus',
            'status' => 'aktif'
        ]);
        
        $invoice = Invoice::create([
            'invoice_number' => 'INV-005',
            'business_unit_id' => $unit->id,
            'client_id' => $client->id,
            'status' => 'overdue',
            'subtotal' => 12000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 12000000,
            'due_date' => Carbon::now()->subDays(10)
        ]);
        DB::table('invoices')->where('id', $invoice->id)->update([
            'created_at' => Carbon::now()->subDays(40)->toDateTimeString()
        ]);

        $this->actingAs($admin);

        Livewire::test(DashboardMorningBriefing::class)
            ->assertSet('lastGenerated', function($val) {
                return !empty($val);
            })
            ->assertCount('urgentAlerts', 1)
            ->call('refreshBriefing')
            ->assertDispatched('notify');
    }

    public function test_ai_knowledge_service_refactored_rules()
    {
        $unit = BusinessUnit::create(['name' => 'Consulting', 'fee_percentage' => 10.0]);
        $client = Client::create([
            'kode_client' => 'CL-005',
            'client_type' => 'corporate',
            'industry_sector' => 'tech',
            'nama_client' => 'Test Client',
            'status' => 'aktif'
        ]);
        
        // Tomorrow's due invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-TOMORROW',
            'business_unit_id' => $unit->id,
            'client_id' => $client->id,
            'status' => 'sent',
            'subtotal' => 7500000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 7500000,
            'due_date' => Carbon::tomorrow()
        ]);

        $service = app(\App\Services\AiKnowledgeService::class);

        // 1. Strict Guardrail Test
        $resGuardrail = $service->getAnswer('bagaimana resep memasak rendang daging sapi', 'id');
        $this->assertEquals('Mohon maaf, saya hanya asisten keuangan J&J GROUP. Saya tidak memiliki data untuk topik tersebut.', $resGuardrail['text']);
        $this->assertNull($resGuardrail['routeName']);

        // 2. Invoices Due Tomorrow Test (Dynamic Calculation)
        $resTomorrow = $service->getAnswer('invoice jatuh tempo besok', 'id');
        $this->assertStringContainsString('INV-TOMORROW', $resTomorrow['text']);
        $this->assertStringContainsString('Rp 7.500.000', $resTomorrow['text']);
        $this->assertNull($resTomorrow['routeName']); // Data queries must prioritize data over navigation
        $this->assertEquals(7500000.0, session('last_data_query'));

        // 3. Context Math Accumulation Test
        $resMath = $service->getAnswer('tambahkan dengan 2500000', 'id');
        $this->assertStringContainsString('Rp 7.500.000', $resMath['text']);
        $this->assertStringContainsString('Rp 2.500.000', $resMath['text']);
        $this->assertStringContainsString('Rp 10.000.000', $resMath['text']);
        $this->assertNull($resMath['routeName']);
        $this->assertEquals(10000000.0, session('last_data_query'));

        // 4. Intent Hierarchy Test (Client query returns routeName to display navigation button)
        $resData = $service->getAnswer('berapa total klien aktif', 'id');
        $this->assertEquals('clients.index', $resData['routeName']);

        // 5. Small Talk Engine Test
        $resGreeting = $service->getAnswer('hai, selamat pagi apa kabar', 'id');
        $this->assertStringContainsString('Senior Financial Consultant', $resGreeting['text']);
        $this->assertNull($resGreeting['routeName']);

        // 6. Mapped System Pages Test
        $resChronos = $service->getAnswer('jelaskan tentang kalender chronos', 'id');
        $this->assertStringContainsString('Kalender Chronos', $resChronos['text']);
        $this->assertStringContainsString('adalah modul untuk', $resChronos['text']);
        $this->assertStringContainsString('Anda dapat mengaksesnya melalui tombol di bawah', $resChronos['text']);
        $this->assertEquals('chronos.index', $resChronos['routeName']);

        // 7. Hybrid Fallback Test (empty paid database fallback)
        $resPaidFallback = $service->getAnswer('total invoice lunas', 'id');
        $this->assertStringContainsString('belum ada data tagihan lunas', $resPaidFallback['text']);
        $this->assertEquals('invoices.index', $resPaidFallback['routeName']);

        // 8. Client vs Business Unit Structural Distinction Test
        $resBU = $service->getAnswer('tampilkan daftar unit bisnis', 'id');
        $this->assertStringContainsString('Unit Bisnis Internal J&J GROUP', $resBU['text']);
        $this->assertStringContainsString('Consulting', $resBU['text']);
        $this->assertEquals('business-units.index', $resBU['routeName']);

        // 9. Advanced Financial Analysis (Gross vs Net) Test
        $invoicePaid = Invoice::create([
            'invoice_number' => 'INV-PAID-CURR',
            'business_unit_id' => $unit->id,
            'client_id' => $client->id,
            'status' => 'paid',
            'subtotal' => 10000000,
            'discount' => 0,
            'ppn' => 0,
            'pph' => 0,
            'total' => 10000000,
            'due_date' => Carbon::now()
        ]);

        \App\Models\Payment::create([
            'invoice_id' => $invoicePaid->id,
            'payment_date' => now(),
            'amount' => 10000000,
            'payment_method' => 'Transfer Bank',
            'reference_number' => 'TEST-PAY-AI',
        ]);

        $resTrend = $service->getAnswer('berapa omset bersih', 'id');
        $this->assertStringContainsString('Omset Kotor (Gross Revenue):', $resTrend['text']);
        $this->assertStringContainsString('Beban Operasional (Expenses):', $resTrend['text']);
        $this->assertStringContainsString('Omset Bersih (Net Revenue):', $resTrend['text']);
        $this->assertStringContainsString('Rp 10.000.000', $resTrend['text']); // Gross
        $this->assertStringContainsString('Rp 1.000.000', $resTrend['text']);  // Expenses (10% of 10M)
        $this->assertStringContainsString('Rp 9.000.000', $resTrend['text']);  // Net (10M - 1M)
    }
}
