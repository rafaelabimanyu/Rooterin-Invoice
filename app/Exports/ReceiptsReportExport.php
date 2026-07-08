<?php

namespace App\Exports;

use App\Models\Receipt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReceiptsReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
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
        $query = Receipt::with(['invoice.client'])
            ->whereBetween('payment_date', [$this->startDate, $this->endDate]);

        if ($this->clientId) {
            $query->whereHas('invoice', function ($q) {
                $q->where('client_id', $this->clientId);
            });
        }

        $payments = $query->orderBy('payment_date')->get();
        $data = collect();

        foreach ($payments as $payment) {
            $data->push([
                $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '-',
                $payment->invoice->client->nama_client ?? '-',
                $payment->invoice->client->nama_perusahaan ?? '-',
                $payment->invoice->invoice_number ?? '-',
                $payment->receipt_number ?? '-',
                $payment->amount,
                strtoupper($payment->payment_method),
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
            'TANGGAL PEMBAYARAN',
            'KLIEN',
            'PERUSAHAAN',
            'NO. FAKTUR',
            'NO. REFERENSI',
            'JUMLAH',
            'METODE PEMBAYARAN',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '"Rp" #,##0;("Rp" #,##0);"-"',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Standard alignments
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
