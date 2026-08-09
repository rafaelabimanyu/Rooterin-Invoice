<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected ?string $search;
    protected ?string $status;

    public function __construct(?string $search = null, ?string $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }

    public function query()
    {
        $query = Client::query();

        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_client', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('kode_client', 'like', "%{$search}%");
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID Klien',
            'Kode Klien',
            'Nama Klien',
            'Nama Perusahaan',
            'Tipe Klien',
            'Sektor Industri',
            'Email',
            'No Telepon / WA',
            'NPWP',
            'Kota',
            'Provinsi',
            'Alamat',
            'Status',
            'Tanggal Terdaftar',
        ];
    }

    public function map($client): array
    {
        return [
            $client->id,
            $client->kode_client,
            $client->nama_client,
            $client->nama_perusahaan ?? '-',
            ucfirst($client->client_type ?? '-'),
            ucfirst($client->industry_sector ?? '-'),
            $client->email ?? '-',
            $client->no_hp ?? '-',
            $client->npwp ?? '-',
            $client->kota ?? '-',
            $client->provinsi ?? '-',
            $client->alamat ?? '-',
            ucfirst($client->status ?? 'aktif'),
            $client->created_at ? $client->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
