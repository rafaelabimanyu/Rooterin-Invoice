<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page { 
            margin-top: 40px; 
            margin-bottom: 40px; 
            margin-left: 45px; 
            margin-right: 45px; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            margin: 0; 
            padding: 0; 
            font-size: 9.5pt; 
            line-height: 1.5; 
            background: #fff;
        }
        .divider { 
            border-top: 2px dashed #e2e8f0; 
            margin: 15px 0; 
            clear: both; 
        }
        .page-break {
            page-break-before: always;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #64748b; }
        
        /* Layout elements */
        .section-label { 
            font-size: 8pt; 
            font-weight: 900; 
            color: #94a3b8; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            margin-bottom: 8px; 
            display: block; 
        }
        .company-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        /* Page 1 Specific */
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .highlight-box {
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px 15px;
            color: #b45309;
            font-weight: bold;
            font-size: 10pt;
            margin-top: 10px;
        }
        
        /* Page 2 Table */
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .items-table th { 
            background: #0f172a; 
            color: #fff; 
            text-align: left; 
            padding: 12px; 
            font-size: 8pt; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            border: 1px solid #334155;
        }
        .items-table td { 
            padding: 12px; 
            border: 1px solid #e2e8f0; 
            vertical-align: middle; 
            font-size: 9pt;
        }
        .items-table tr:nth-child(even) { background: #f8fafc; }
        
        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .financial-table td {
            padding: 6px 0;
            font-size: 9pt;
        }
        
        .total-box {
            background: #0f172a;
            color: #ffffff;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 11pt;
            font-weight: bold;
            margin-top: 10px;
        }
        
        /* Page 3 Gallery */
        .gallery-table {
            width: 100%;
            border-collapse: collapse;
        }
        .gallery-table td {
            width: 50%;
            padding: 8px;
            vertical-align: top;
        }
        .gallery-img-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px;
            text-align: center;
        }
        .gallery-img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        /* Footer */
        .footer { 
            position: fixed; 
            bottom: -20px; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 7.5pt;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- ==================== PAGE 1: EXECUTIVE SUMMARY ==================== -->
    <div class="page-container">
        <!-- Letterhead Header -->
        <table class="company-header">
            <tr>
                <td style="width: 65%; vertical-align: top;">
                    <div style="font-size: 14pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; line-height: 1.2;">J&J GROUP PLUMBING SERVICES</div>
                    <div style="font-size: 8.5pt; color: #c89d3c; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; margin-bottom: 6px;">SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR*</div>
                    <div style="font-size: 9pt; color: #475569; line-height: 1.4;">
                        {{ \App\Models\Setting::get('company_address', 'Jl. Dewa RT.002/002 No.70, Ciracas, Jakarta Timur') }}<br>
                        Email: {{ strtolower(\App\Models\Setting::get('company_email', 'jayarooter@gmail.com')) }} | Website: {{ strtolower(\App\Models\Setting::get('company_website', 'jayarooter.com')) }}
                    </div>
                </td>
                <td style="width: 35%; vertical-align: top; text-align: right;">
                    @if(isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" style="height: 50px; margin-bottom: 5px;">
                    @else
                        <div style="font-size: 18px; font-weight: 900; color: #0f172a; margin-bottom: 5px;">J&J GROUP<span style="color: #c89d3c;">.</span></div>
                    @endif
                    <div style="font-size: 18pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 2px;">PARTNERSHIP INVOICE</div>
                    <div style="font-size: 11pt; font-weight: 700; color: #c89d3c;">#{{ $invoice->invoice_number }}</div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <h2 style="font-size: 12pt; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 20px; margin-bottom: 15px;">
            1. Executive Contract Summary / Ringkasan Eksekutif Kemitraan
        </h2>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                    <span class="section-label">{{ app()->getLocale() == 'en' ? 'Client Partner Account' : 'Akun Mitra Klien' }}</span>
                    <div class="summary-card" style="min-height: 120px;">
                        <div style="font-size: 11pt; font-weight: 900; color: #0f172a; margin-bottom: 5px;">
                            {{ optional($invoice->client)->nama_client ?? 'Klien Tidak Ditemukan' }}
                        </div>
                        <div style="font-size: 9pt; color: #64748b; line-height: 1.5;">
                            @if(optional($invoice->client)->nama_perusahaan)
                                <b>{{ $invoice->client->nama_perusahaan }}</b><br>
                            @endif
                            @if(optional($invoice->client)->alamat)
                                {{ $invoice->client->alamat }}<br>
                            @endif
                            @if(optional($invoice->client)->no_hp)
                                Kontak: {{ $invoice->client->no_hp }}
                            @endif
                        </div>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                    <span class="section-label">{{ app()->getLocale() == 'en' ? 'Contract Details' : 'Rincian Kontrak' }}</span>
                    <div class="summary-card" style="min-height: 120px;">
                        <table style="width: 100%; font-size: 9pt; line-height: 1.6;">
                            <tr>
                                <td style="color: #64748b; font-weight: 600; width: 45%;">Invoice No:</td>
                                <td style="font-weight: 700; color: #0f172a;">{{ $invoice->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Issue Date:</td>
                                <td style="font-weight: 700; color: #0f172a;">{{ $invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('d M Y') : '-' }}</td>
                            </tr>
                            @if($invoice->due_date)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Due Date:</td>
                                <td style="font-weight: 700; color: #e11d48;">{{ $invoice->due_date->format('d M Y') }}</td>
                            </tr>
                            @endif
                            @if($invoice->businessUnit)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Business Unit:</td>
                                <td style="font-weight: 700; color: #c89d3c;">{{ $invoice->businessUnit->name }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Prominent Contract Period Callout -->
        @if($invoice->periode_kontrak)
        <span class="section-label">{{ app()->getLocale() == 'en' ? 'Billing Contract Period' : 'Periode Kontrak Penagihan' }}</span>
        <div class="highlight-box" style="margin-bottom: 25px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 10%; vertical-align: middle; text-align: center;">
                        <span style="font-size: 20pt; line-height: 1;">📅</span>
                    </td>
                    <td style="width: 90%; vertical-align: middle;">
                        <div style="font-size: 8.5pt; text-transform: uppercase; letter-spacing: 1px; color: #92400e;">Active Contract Period</div>
                        <div style="font-size: 12pt; font-weight: 900; color: #78350f; margin-top: 2px;">{{ $invoice->periode_kontrak }}</div>
                    </td>
                </tr>
            </table>
        </div>
        @endif

        <span class="section-label">{{ app()->getLocale() == 'en' ? 'Scope of Partnership Agreement' : 'Lingkup Perjanjian Kemitraan' }}</span>
        <div style="font-size: 9pt; color: #475569; line-height: 1.6; margin-bottom: 30px;">
            <p style="margin: 0 0 10px 0;">
                {{ app()->getLocale() == 'en' 
                    ? 'This document serves as the official executive billing summary issued under the active partnership agreement between J&J Group Plumbing Services and the client listed above. The services listed herein have been fully executed, verified, and cataloged in accordance with our service level agreement (SLA).'
                    : 'Dokumen ini berfungsi sebagai ringkasan penagihan eksekutif resmi yang diterbitkan berdasarkan perjanjian kemitraan aktif antara J&J Group Plumbing Services dan klien yang tercantum di atas. Layanan yang tercantum di sini telah sepenuhnya dilaksanakan, diverifikasi, dan dicatat sesuai dengan kesepakatan tingkat layanan (SLA) kami.' }}
            </p>
            <p style="margin: 0;">
                {{ app()->getLocale() == 'en'
                    ? 'All rates, discounts, and terms are derived from the agreed master contract framework. For itemized listings and field service documentation evidence, please refer to Section 2 and Section 3 of this document.'
                    : 'Semua tarif, diskon, dan ketentuan didasarkan pada kerangka kontrak induk yang disepakati. Untuk rincian item layanan dan bukti dokumentasi pekerjaan lapangan, silakan merujuk pada Bagian 2 dan Bagian 3 dari dokumen ini.' }}
            </p>
        </div>

        <span class="section-label">{{ app()->getLocale() == 'en' ? 'Contract Financial Overview' : 'Ikhtisar Keuangan Kontrak' }}</span>
        <table style="width: 100%; border-collapse: collapse; background: #0f172a; border-radius: 12px; color: #ffffff; padding: 20px; margin-bottom: 30px;">
            <tr>
                <td style="padding: 15px 20px; width: 60%; vertical-align: middle;">
                    <div style="font-size: 8.5pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">{{ app()->getLocale() == 'en' ? 'Total Contract Value Due' : 'Total Nilai Kontrak Jatuh Tempo' }}</div>
                    <div style="font-size: 20pt; font-weight: 900; color: #fbbf24; margin-top: 3px;">
                        {{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->total, 0, ',', '.') }}
                    </div>
                </td>
                <td style="padding: 15px 20px; width: 40%; vertical-align: middle; text-align: right; border-left: 1px dashed #334155;">
                    <div style="font-size: 8.5pt; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Status</div>
                    <div style="display: inline-block; padding: 4px 12px; background: {{ $invoice->status === 'paid' ? '#065f46' : '#991b1b' }}; color: #ffffff; border-radius: 50px; font-size: 8.5pt; font-weight: bold; text-transform: uppercase; margin-top: 5px;">
                        {{ strtoupper($invoice->status) }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Page 1 Footer -->
        <div class="footer">
            J&J GROUP PLUMBING SERVICE | {{ app()->getLocale() == 'en' ? 'Page' : 'Halaman' }} 1/3 (Executive Summary)
        </div>
    </div>

    <!-- ==================== PAGE 2: ITEMIZED BILL ==================== -->
    <div class="page-break">
        <!-- Minimal Letterhead Header for Page 2 -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <td style="vertical-align: top;">
                    <div style="font-size: 11pt; font-weight: 900; color: #0f172a; text-transform: uppercase;">J&J GROUP PLUMBING SERVICES</div>
                    <div style="font-size: 8pt; color: #c89d3c; font-weight: bold;">SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR*</div>
                </td>
                <td style="vertical-align: top; text-align: right;">
                    <div style="font-size: 11pt; font-weight: 900; color: #0f172a;">#{{ $invoice->invoice_number }}</div>
                    <div style="font-size: 8pt; color: #64748b;">{{ app()->getLocale() == 'en' ? 'Itemized Billing' : 'Rincian Penagihan' }}</div>
                </td>
            </tr>
        </table>

        <div class="divider" style="margin: 10px 0;"></div>

        <h2 style="font-size: 12pt; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 15px; margin-bottom: 15px;">
            2. Itemized Contract Billing / Rincian Tagihan Kemitraan
        </h2>

        <!-- Billing Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">{{ app()->getLocale() == 'en' ? 'Service/Item Description' : 'Deskripsi Item Layanan' }}</th>
                    <th width="10%" class="text-center">QTY</th>
                    <th width="20%" class="text-right">{{ app()->getLocale() == 'en' ? 'Unit Rate' : 'Harga Satuan' }}</th>
                    <th width="20%" class="text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold; color: #0f172a;">{{ $item->deskripsi }}</div>
                        <div style="font-size: 8pt; color: #94a3b8; margin-top: 2px;">{{ app()->getLocale() == 'en' ? 'Partnership contract agreement fulfillment' : 'Pemenuhan perjanjian kontrak kemitraan' }}</div>
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-right">{{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Calculations & Bank Details Layout -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <tr>
                <!-- Bank Info (Left Side) -->
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; min-height: 180px;">
                        <span class="section-label" style="font-size: 7.5pt; margin-bottom: 10px;">{{ app()->getLocale() == 'en' ? 'Payment Bank Account' : 'Informasi Rekening Pembayaran' }}</span>
                        <div style="font-size: 9pt; color: #475569; line-height: 1.5;">
                            {!! nl2br(e($invoice->bank_account_info ?: "Bank: Bank Central Asia (BCA)\nAcc No: 6281873404\nName: Wibowo Pratikno")) !!}
                        </div>
                    </div>
                </td>
                
                <!-- Financial Summary (Right Side) -->
                <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                    <table class="financial-table">
                        <tr>
                            <td style="color: #64748b; font-weight: 600;">SUBTOTAL:</td>
                            <td style="text-align: right; font-weight: 700; color: #0f172a;">{{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($invoice->discount > 0)
                        <tr>
                            <td style="color: #64748b; font-weight: 600;">{{ app()->getLocale() == 'en' ? 'Discount' : 'Diskon' }}:</td>
                            <td style="text-align: right; font-weight: 700; color: #ef4444;">- {{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($invoice->ppn > 0)
                        <tr>
                            <td style="color: #64748b; font-weight: 600;">PPN:</td>
                            <td style="text-align: right; font-weight: 700; color: #0f172a;">+ {{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($invoice->pph > 0)
                        <tr>
                            <td style="color: #64748b; font-weight: 600;">PPh:</td>
                            <td style="text-align: right; font-weight: 700; color: #0f172a;">+ {{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->pph, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </table>

                    <div class="total-box">
                        <table style="width: 100%; color: #ffffff;">
                            <tr>
                                <td style="font-size: 10pt; font-weight: 900; text-transform: uppercase;">TOTAL DUE:</td>
                                <td style="text-align: right; font-size: 13pt; font-weight: 900;">
                                    {{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Signatures and Verification stamp -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 35px;">
            <tr>
                <td style="width: 50%; vertical-align: bottom; text-align: left; padding-bottom: 20px;">
                    <div style="font-size: 8pt; color: #94a3b8; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">{{ app()->getLocale() == 'en' ? 'Authorized Stamp' : 'Cap Resmi Kemitraan' }}</div>
                    <div style="width: 130px; height: 70px; border: 2px double #c89d3c; border-radius: 8px; color: #c89d3c; font-size: 7.5pt; font-weight: bold; text-align: center; line-height: 1.2; padding-top: 15px; box-sizing: border-box; text-transform: uppercase;">
                        J&J GROUP<br>
                        OFFICIAL PARTNER<br>
                        VERIFIED
                    </div>
                </td>
                <td style="width: 50%; vertical-align: bottom; text-align: center;">
                    <div style="font-size: 8pt; color: #94a3b8; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">{{ app()->getLocale() == 'en' ? 'Authorized Signature' : 'Tanda Tangan Pengesahan' }}</div>
                    <div style="min-height: 70px;">
                        @if(isset($ttdBase64) && $ttdBase64)
                            <img src="{{ $ttdBase64 }}" style="width: 150px; display: inline-block;">
                        @else
                            <div style="height: 70px; color: #cbd5e1; font-style: italic; font-size: 9pt; line-height: 70px;">(Signature)</div>
                        @endif
                    </div>
                    <div style="font-size: 9pt; font-weight: bold; color: #0f172a; border-top: 1px solid #e2e8f0; width: 180px; margin: 5px auto 0 auto; padding-top: 4px;">
                        Wibowo Pratikno
                    </div>
                    <div style="font-size: 8pt; color: #64748b;">Managing Director</div>
                </td>
            </tr>
        </table>

        <!-- Page 2 Footer -->
        <div class="footer">
            J&J GROUP PLUMBING SERVICE | {{ app()->getLocale() == 'en' ? 'Page' : 'Halaman' }} 2/3 (Itemized Bill)
        </div>
    </div>

    <!-- ==================== PAGE 3: SERVICE REPORT GRID ==================== -->
    <div class="page-break">
        <!-- Minimal Letterhead Header for Page 3 -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <td style="vertical-align: top;">
                    <div style="font-size: 11pt; font-weight: 900; color: #0f172a; text-transform: uppercase;">J&J GROUP PLUMBING SERVICES</div>
                    <div style="font-size: 8pt; color: #c89d3c; font-weight: bold;">SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR*</div>
                </td>
                <td style="vertical-align: top; text-align: right;">
                    <div style="font-size: 11pt; font-weight: 900; color: #0f172a;">#{{ $invoice->invoice_number }}</div>
                    <div style="font-size: 8pt; color: #64748b;">{{ app()->getLocale() == 'en' ? 'Service Report' : 'Laporan Dokumentasi' }}</div>
                </td>
            </tr>
        </table>

        <div class="divider" style="margin: 10px 0;"></div>

        <h2 style="font-size: 12pt; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 15px; margin-bottom: 15px;">
            3. Field Service & Work Evidence Report / Laporan Layanan & Bukti Pekerjaan
        </h2>

        <!-- Field Job Metadata -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 9pt;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                    <div style="background: #f8fafc; border-left: 4px solid #10b981; border-radius: 0 8px 8px 0; padding: 10px 15px; margin-bottom: 10px;">
                        <span style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 3px;">{{ app()->getLocale() == 'en' ? 'Field Technicians' : 'Teknisi Lapangan' }}</span>
                        <span style="font-weight: bold; color: #0f172a;">{{ $invoice->technician_names ?: '-' }}</span>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                    <div style="background: #f8fafc; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: 10px 15px; margin-bottom: 10px;">
                        <span style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 3px;">{{ app()->getLocale() == 'en' ? 'Cause of Problem' : 'Penyebab Mampet' }}</span>
                        <span style="font-weight: bold; color: #0f172a;">{{ $invoice->cause_of_problem ?: '-' }}</span>
                    </div>
                </td>
            </tr>
            @if($invoice->warranty || $invoice->notes)
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                    @if($invoice->warranty)
                    <div style="background: #f8fafc; border-left: 4px solid #3b82f6; border-radius: 0 8px 8px 0; padding: 10px 15px;">
                        <span style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 3px;">{{ app()->getLocale() == 'en' ? 'Warranty Period' : 'Masa Garansi' }}</span>
                        <span style="font-weight: bold; color: #0f172a;">{{ $invoice->warranty }}</span>
                    </div>
                    @endif
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                    @if($invoice->notes)
                    <div style="background: #f8fafc; border-left: 4px solid #6366f1; border-radius: 0 8px 8px 0; padding: 10px 15px;">
                        <span style="font-size: 7.5pt; font-weight: bold; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 3px;">{{ app()->getLocale() == 'en' ? 'Additional Notes' : 'Catatan Tambahan' }}</span>
                        <span style="font-weight: bold; color: #0f172a;">{{ $invoice->notes }}</span>
                    </div>
                    @endif
                </td>
            </tr>
            @endif
        </table>

        <!-- Field Documentation Photos Grid (Up to 12 attachments) -->
        <span class="section-label" style="margin-bottom: 12px;">{{ app()->getLocale() == 'en' ? 'Field Job Documentation Evidence' : 'Bukti Dokumentasi Pekerjaan Lapangan' }}</span>
        
        @if(count($attachments) > 0)
            <table class="gallery-table">
                @foreach($attachments->chunk(2) as $row)
                <tr>
                    @foreach($row as $attachment)
                    <td>
                        <div class="gallery-img-container">
                            @if($attachment->base64_data)
                                <img src="{{ $attachment->base64_data }}" class="gallery-img">
                            @else
                                <div style="height: 180px; line-height: 180px; color: #94a3b8; font-size: 9pt;">[Image Missing]</div>
                            @endif
                            <div style="font-size: 7.5pt; font-weight: bold; color: #475569; margin-top: 5px; height: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $attachment->caption ?: 'Evidence Item #' . $loop->parent->index . '-' . $loop->index }}
                            </div>
                        </div>
                    </td>
                    @endforeach
                    @if($row->count() < 2)
                    <td></td>
                    @endif
                </tr>
                @endforeach
            </table>
        @else
            <div style="background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 12px; padding: 40px; text-align: center; color: #94a3b8; font-size: 9.5pt;">
                📷 {{ app()->getLocale() == 'en' ? 'No work documentation photos uploaded for this contract period.' : 'Tidak ada foto dokumentasi pekerjaan yang diunggah untuk periode kontrak ini.' }}
            </div>
        @endif

        <!-- Verification assurance statement -->
        <div style="margin-top: 20px; font-size: 8.5pt; color: #64748b; font-style: italic; text-align: center; line-height: 1.4;">
            This technical document constitutes a legal addendum under the active master service agreement. All works have been verified physically on-site.
        </div>

        <!-- Page 3 Footer -->
        <div class="footer">
            J&J GROUP PLUMBING SERVICE | {{ app()->getLocale() == 'en' ? 'Page' : 'Halaman' }} 3/3 (Service Report)
        </div>
    </div>

</body>
</html>
