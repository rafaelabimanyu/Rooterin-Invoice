<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class InvoicesReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
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
        $query = Invoice::with('client')
            ->whereBetween('tanggal_invoice', [$this->startDate, $this->endDate]);

        if ($this->clientId) {
            $query->where('client_id', $this->clientId);
        }

        $invoices = $query->orderBy('tanggal_invoice')->get();
        $data = collect();

        foreach ($invoices as $invoice) {
            $data->push([
                $invoice->invoice_number,
                $invoice->client->nama_client,
                $invoice->client->nama_perusahaan,
                $invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('Y-m-d') : '-',
                $invoice->expiry_date ? $invoice->expiry_date->format('Y-m-d') : '-',
                $invoice->total,
                strtoupper($invoice->status),
            ]);
        }

        $count = $data->count();
        // Append Total Row
        $data->push([
            'TOTAL',
            '',
            '',
            '',
            '',
            '=SUM(F2:F' . ($count + 1) . ')',
            '',
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'NO. FAKTUR',
            'KLIEN',
            'PERUSAHAAN',
            'TANGGAL INVOICE',
            'TANGGAL JATUH TEMPO',
            'TOTAL',
            'STATUS',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(28);
        for ($row = 2; $row <= $lastRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(22);
        }

        // Standard alignments
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Vertical alignment for clean padding
        $sheet->getStyle('A2:G' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [
            1 => [
                'font' => [
                    'name' => 'Segoe UI',
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E293B'], // Slate Gelap Premium
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            $lastRow => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8FAFC'],
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
