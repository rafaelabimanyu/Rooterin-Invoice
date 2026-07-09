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
            'dateRange'
        ));

        // Format name safely
        $filename = 'Laporan_' . str_replace(' ', '_', $businessUnit->name) . '_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
