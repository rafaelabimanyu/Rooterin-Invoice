<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ClientsReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $startDate;
    protected $endDate;
    protected $clientId;

    public function __construct($startDate, $endDate, $clientId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->clientId = $clientId;
    }

    public function collection()
    {
        $query = Client::query();

        if ($this->clientId) {
            $query->where('clients.id', $this->clientId);
        }

        $query->select('clients.nama_client', 'clients.nama_perusahaan');

        // Add subqueries for client intelligence in the date range using safe parameterized queries
        $query->selectRaw("
            (SELECT COUNT(*) FROM invoices 
             WHERE invoices.client_id = clients.id 
             AND invoices.created_at BETWEEN ? AND ?) as total_invoices_count
        ", [$this->startDate, $this->endDate]);

        $query->selectRaw("
            (SELECT COALESCE(SUM(total), 0) FROM invoices 
             WHERE invoices.client_id = clients.id 
             AND invoices.created_at BETWEEN ? AND ?) as total_invoice_value
        ", [$this->startDate, $this->endDate]);

        $query->selectRaw("
            (SELECT COALESCE(SUM(total), 0) FROM invoices 
             WHERE invoices.client_id = clients.id 
             AND invoices.status = 'paid' 
             AND invoices.created_at BETWEEN ? AND ?) as total_revenue
        ", [$this->startDate, $this->endDate]);

        $query->selectRaw("
            (SELECT COALESCE(SUM(total - COALESCE((SELECT SUM(amount_received) FROM receipts WHERE receipts.invoice_id = invoices.id), 0)), 0) FROM invoices 
             WHERE invoices.client_id = clients.id 
             AND invoices.status IN ('sent', 'dp', 'pending', 'overdue') 
             AND invoices.created_at BETWEEN ? AND ?) as total_outstanding
        ", [$this->startDate, $this->endDate]);

        $clients = $query->orderBy('clients.nama_client')->get();
        $data = collect();

        foreach ($clients as $client) {
            $data->push([
                $client->nama_client,
                $client->nama_perusahaan,
                (int)$client->total_invoices_count,
                (float)$client->total_invoice_value,
                (float)$client->total_revenue,
                (float)$client->total_outstanding,
            ]);
        }

        $count = $data->count();
        // Append Total Row
        $data->push([
            'TOTAL',
            '',
            '=SUM(C2:C' . ($count + 1) . ')',
            '=SUM(D2:D' . ($count + 1) . ')',
            '=SUM(E2:E' . ($count + 1) . ')',
            '=SUM(F2:F' . ($count + 1) . ')',
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'NAMA KLIEN',
            'PERUSAHAAN',
            'JUMLAH FAKTUR',
            'TOTAL NILAI FAKTUR',
            'PENDAPATAN TERKUMPUL',
            'TOTAL TUNGGAKAN',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '"Rp" #,##0;("Rp" #,##0);"-"',
            'E' => '"Rp" #,##0;("Rp" #,##0);"-"',
            'F' => '"Rp" #,##0;("Rp" #,##0);"-"',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Standard alignments
        $sheet->getStyle('A2:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F172A'], // Navy / Slate-900
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],
            $lastRow => [
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_DOUBLE,
                    ],
                ]
            ]
        ];
    }
}
