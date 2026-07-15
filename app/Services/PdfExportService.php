<?php

namespace App\Services;

use App\Models\BusinessUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfExportService
{
    protected $reportingService;

    public function __construct(BusinessUnitReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    /**
     * Generate and download a PDF report for a specific Business Unit.
     *
     * @param BusinessUnit $businessUnit
     * @param array $filters
     * @return \Illuminate\Http\Response
     */
    public function exportBusinessUnitReport(BusinessUnit $businessUnit, array $filters = [])
    {
        // Add business unit to the filters
        $filters['business_unit_id'] = $businessUnit->id;

        // Fetch reports data from single source of truth
        $stats = $this->reportingService->getSummaryStats($filters);
        $trend = $this->reportingService->getMonthlyTrend($filters, 6);
        $topClients = $this->reportingService->getTopClients($filters, 5);

        // Fetch filtered invoices for the Ledger Transaksi section
        $invoiceQuery = $businessUnit->invoices()
            ->with(['client', 'payments'])
            ->orderByDesc('created_at');

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $invoiceQuery->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }
        if (!empty($filters['client_id'])) {
            $invoiceQuery->where('client_id', $filters['client_id']);
        }
        $invoices = $invoiceQuery->get();

        // Convert logo to Base64
        $logoPath = public_path('img/logo-jnj.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Prepare context
        $dateRange = 'Seluruh Waktu';
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $dateRange = Carbon::parse($filters['start_date'])->format('d M Y') . ' - ' . Carbon::parse($filters['end_date'])->format('d M Y');
        }

        // Render PDF view
        $pdf = Pdf::loadView('pdf.business_unit_report', compact(
            'businessUnit',
            'stats',
            'trend',
            'topClients',
            'invoices',
            'dateRange',
            'logoBase64'
        ))->setPaper('a4')
          ->setOption([
              'isRemoteEnabled' => true,
              'isHtml5ParserEnabled' => true,
              'defaultFont' => 'sans-serif',
              'enable_php' => true
          ]);

        // Format name safely
        $filename = 'Laporan_' . str_replace(' ', '_', $businessUnit->name) . '_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
