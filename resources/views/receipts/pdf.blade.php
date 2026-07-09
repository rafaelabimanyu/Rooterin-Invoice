<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() == 'en' ? 'Receipt' : 'Kuitansi' }} #{{ $receipt->receipt_number }}</title>
    <style>
        @page { 
            margin-top: 50px; 
            margin-bottom: 50px; 
            margin-left: 50px; 
            margin-right: 50px; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            margin: 0; 
            padding: 0; 
            font-size: 10pt; 
            line-height: 1.6; 
            background: #fff;
        }
        .container { 
            padding: 0; 
            padding-bottom: 350px; 
            position: relative; 
            box-sizing: border-box;
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: 900;
            color: rgba(241, 245, 249, 0.08);
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 20px;
        }
        
        .container-page2 {
            padding: 0;
            position: relative;
            box-sizing: border-box;
            min-height: 800px;
        }

        .divider { border-top: 2px solid #f1f5f9; margin: 20px 0; clear: both; }

        /* Addressing */
        .addressing { margin-bottom: 30px; }
        .bill-to { float: left; width: 50%; }
        .status-box { float: right; width: 45%; text-align: right; }
        .section-label { font-size: 8.5pt; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; display: block; }
        
        .client-card { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #f1f5f9; }
        .client-name { font-size: 11pt; font-weight: 900; color: #0f172a; margin-bottom: 5px; }
        .client-details { font-size: 9pt; color: #64748b; line-height: 1.5; }

        .badge { display: inline-block; padding: 8px 20px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; font-size: 9pt; border-radius: 50px; }
        .badge-paid { background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; }
        .badge-unpaid { background: #fef2f2; color: #e11d48; border: 1px solid #fee2e2; }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { 
            background: #0f172a; 
            color: #fff; 
            text-align: left; 
            padding: 15px; 
            font-size: 8.5pt; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            border: 1px solid #334155;
        }
        .items-table td { 
            padding: 18px 15px; 
            border: 1px solid #e2e8f0; 
            vertical-align: middle; 
        }
        .items-table tr:nth-child(even) { background: #fcfdfe; }
        
        .item-desc-primary { font-weight: 700; color: #0f172a; font-size: 10pt; }
        .item-desc-secondary { font-size: 8.5pt; color: #94a3b8; margin-top: 2px; }
        
        /* Financials Box Styling */
        .bank-box { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; }
        .bank-title { font-size: 9pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 12px; display: block; }
        .bank-details { font-size: 9pt; color: #64748b; line-height: 1.6; }
        .bank-details b { color: #0f172a; }

        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }

        .bottom-section {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
        }

        .footer { 
            position: absolute; 
            bottom: 0px; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
            font-weight: bold;
        }

        .clearfix::after { content: ""; clear: both; display: table; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <!-- Halaman Pertama: Detail Kuitansi & Transaksi -->
    <div class="container">
        <!-- Watermark -->
        <div class="watermark">{{ app()->getLocale() == 'en' ? 'RECEIPT' : 'KUITANSI' }}</div>

        <!-- Header / Letterhead Table -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
            <tr>
                <td style="width: 65%; vertical-align: top;">
                    <div style="font-size: 14pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; line-height: 1.2;">J&J GROUP PLUMBING SERVICES</div>
                    <div style="font-size: 8.5pt; color: #c89d3c; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; margin-bottom: 8px;">SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR*</div>
                    <div style="font-size: 9pt; color: #475569; line-height: 1.5;">
                        <span style="display: block; margin-bottom: 3px; font-weight: bold; color: #0f172a;">{{ \App\Models\Setting::get('company_address', 'Jl. Dewa RT.002/002 No.70, Ciracas, Jakarta Timur') }}</span>
                        @php
                            $phonesStr = \App\Models\Setting::get('company_phone', '0812-40000-759 / 0812-40000-749 / 0812-83-300-900');
                            $phones = array_map('trim', explode('/', $phonesStr));
                        @endphp
                        Kontak: 
                        @foreach($phones as $phone)
                            <strong style="color: #0f172a;">{{ $phone }}</strong>@if(!$loop->last) / @endif
                        @endforeach
                        <br>
                        Email: {{ strtolower(\App\Models\Setting::get('company_email', 'jayarooter@gmail.com / jawarooter@gmail.com')) }}
                    </div>
                </td>
                <td style="width: 35%; vertical-align: top; text-align: right;">
                    @if(isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" style="height: 60px; margin-bottom: 8px;">
                    @else
                        <div style="font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 8px;">J&J GROUP<span style="color: #c89d3c;">.</span></div>
                    @endif
                    <div style="font-size: 24pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 2px; letter-spacing: -1px; line-height: 1.1;">{{ app()->getLocale() == 'en' ? 'Receipt' : 'Kuitansi' }}</div>
                    <div style="font-size: 13pt; font-weight: 700; color: #c89d3c; margin-bottom: 5px;">#{{ $receipt->receipt_number }}</div>
                    <div style="font-size: 8.5pt; color: #475569; font-weight: bold; margin-top: 5px;">
                        {{ strtolower(\App\Models\Setting::get('company_website', 'jayarooter.com / jawarooter.com')) }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Addressing -->
        <div class="addressing clearfix">
            <div class="bill-to">
                <span class="section-label">{{ app()->getLocale() == 'en' ? 'CLIENT' : 'KLIEN' }}</span>
                <div class="client-card">
                    <div class="client-name">{{ optional($receipt->client)->nama_client ?? 'Klien Tidak Ditemukan' }}</div>
                    <div class="client-details">
                        @if(optional($receipt->client)->nama_perusahaan)
                            <b>{{ $receipt->client->nama_perusahaan }}</b><br>
                        @endif
                        @if(optional($receipt->client)->alamat)
                            {{ $receipt->client->alamat }}<br>
                        @endif
                        @if(optional($receipt->client)->kota || optional($receipt->client)->provinsi)
                            {{ implode(', ', array_filter([$receipt->client->kota, $receipt->client->provinsi])) }}<br>
                        @endif
                        @if(optional($receipt->client)->no_hp)
                            {{ app()->getLocale() == 'en' ? 'Contact' : 'Kontak' }}: {{ $receipt->client->no_hp }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="status-box">
                <span class="section-label">{{ app()->getLocale() == 'en' ? 'RECEIPT STATUS' : 'STATUS KUITANSI' }}</span>
                <div class="badge badge-paid" style="margin-bottom: 12px;">
                    {{ app()->getLocale() == 'en' ? 'Paid' : 'Lunas' }}
                </div>
                <div style="font-size: 9pt; color: #475569; line-height: 1.4;">
                    {{ app()->getLocale() == 'en' ? 'Receipt Date' : 'Tanggal Kuitansi' }}: <b style="color: #0f172a;">{{ $receipt->tanggal_receipt ? $receipt->tanggal_receipt->format('d M Y') : '-' }}</b><br>
                    {{ app()->getLocale() == 'en' ? 'Expiry Date' : 'Tanggal Kedaluwarsa' }}: <b style="color: #0f172a;">{{ $receipt->expiry_date ? $receipt->expiry_date->format('d M Y') : '-' }}</b>
                </div>
                @if($receipt->invoice && $receipt->invoice->warranty)
                <div style="margin-top: 12px;">
                    <span class="section-label" style="margin-bottom: 4px; display: block;">{{ app()->getLocale() == 'en' ? 'Warranty Period' : 'Masa Garansi' }}</span>
                    <div style="font-size: 10pt; font-weight: 700; color: #0f172a;">{{ $receipt->invoice->warranty }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">DESKRIPSI</th>
                    <th width="10%" class="text-center">QTY</th>
                    <th width="20%" class="text-right">HARGA</th>
                    <th width="20%" class="text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->items as $item)
                <tr>
                    <td>
                        <div class="item-desc-primary">{{ $item->deskripsi }}</div>
                        <div class="item-desc-secondary">{{ app()->getLocale() == 'en' ? 'Service quotation fulfillment' : 'Pemenuhan penawaran layanan' }}</div>
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Bottom Layout Table: Bank Account Info (Left) & Financial Summary + Standalone Signature (Right) -->
        <div class="bottom-section" style="page-break-inside: avoid;">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                <tr>
                    <!-- Kolom Kiri (50%): Informasi Rekening Bank -->
                    <td style="width: 50%; vertical-align: top; padding-right: 25px;">
                        <div class="bank-box" style="margin: 0;">
                            <span class="bank-title">{{ app()->getLocale() == 'en' ? 'Bank Account' : 'Rekening Bank' }}</span>
                            <div class="bank-details" style="font-size: 9.5pt; line-height: 1.6; color: #475569;">
                                {!! nl2br(e(\App\Models\Setting::get('bank_account_info') ?: "Bank: Bank Central Asia (BCA)\nAcc No: 6281873404\nName: Wibowo Pratikno")) !!}
                            </div>
                        </div>
                    </td>
                    
                    <!-- Kolom Kanan (50%): Ringkasan Keuangan & Tanda Tangan -->
                    <td style="width: 50%; vertical-align: top; padding-left: 25px;">
                        <!-- Ringkasan Keuangan -->
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">SUBTOTAL</td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">Rp {{ number_format($receipt->invoice ? $receipt->invoice->subtotal : $receipt->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if($receipt->invoice && $receipt->invoice->discount > 0)
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">Discount</td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #ef4444; font-size: 10pt;">- Rp {{ number_format($receipt->invoice->discount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($receipt->invoice && $receipt->invoice->ppn > 0)
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">PPN</td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">+ Rp {{ number_format($receipt->invoice->ppn, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($receipt->invoice && $receipt->invoice->pph > 0)
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">PPh</td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">+ Rp {{ number_format($receipt->invoice->pph, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding: 15px; background: #0f172a; border-top-left-radius: 12px; border-bottom-left-radius: 12px; color: rgba(255,255,255,0.8); font-size: 10.5pt; font-weight: 900; text-transform: uppercase;">TOTAL</td>
                                <td style="padding: 15px; background: #0f172a; border-top-right-radius: 12px; border-bottom-right-radius: 12px; text-align: right; color: #ffffff; font-size: 15pt; font-weight: 900; white-space: nowrap;">Rp {{ number_format($receipt->total, 0, ',', '.') }}</td>
                            </tr>
                        </table>

                        <!-- Tanda Tangan (di bawah ringkasan keuangan, margin-top cukup, dipusatkan) -->
                        <div style="margin-top: 30px; text-align: center;">
                            @if(isset($ttdBase64) && $ttdBase64)
                                <img src="{{ $ttdBase64 }}" style="width: 180px; display: inline-block;">
                            @else
                                <div style="height: 70px; color: #94a3b8; font-style: italic; font-size: 9pt; line-height: 70px;">(Tanda Tangan)</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer Halaman 1 -->
        <div class="footer">
            J&J GROUP PLUMBING SERVICE | SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR!
        </div>
    </div>

    <!-- Halaman Kedua: Dokumentasi & Catatan -->
    <div style="page-break-before: always;">
        <div class="container-page2">
            <!-- Header Halaman 2 -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                <tr>
                    <td style="width: 65%; vertical-align: top;">
                        <div style="font-size: 14pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; line-height: 1.2;">J&J GROUP PLUMBING SERVICES</div>
                        <div style="font-size: 8.5pt; color: #c89d3c; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; margin-bottom: 8px;">SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR*</div>
                        <div style="font-size: 8.5pt; color: #475569; line-height: 1.5;">
                            {{ \App\Models\Setting::get('company_address', 'Jl. Dewa RT.002/002 No.70, Ciracas, Jakarta Timur') }}
                        </div>
                    </td>
                    <td style="width: 35%; vertical-align: top; text-align: right;">
                        @if(isset($logoBase64) && $logoBase64)
                            <img src="{{ $logoBase64 }}" style="height: 45px; margin-bottom: 5px;">
                        @endif
                        <div style="font-size: 13pt; font-weight: 900; color: #0f172a; text-transform: uppercase; line-height: 1.1;">DOKUMENTASI</div>
                        <div style="font-size: 10pt; font-weight: 700; color: #c89d3c;">#{{ $receipt->receipt_number }}</div>
                    </td>
                </tr>
            </table>

            <div class="divider" style="margin: 10px 0;"></div>

            <!-- Dokumentasi Pekerjaan Section -->
            <span class="section-label" style="font-size: 10pt; color: #0f172a; border-bottom: 2px solid #c89d3c; padding-bottom: 2px; margin-bottom: 10px;">DOKUMENTASI PEKERJAAN</span>
            
            <div style="font-size: 9pt; color: #64748b; margin-bottom: 8px; font-weight: bold;">
                Total foto terdeteksi: {{ count($attachments) }}
            </div>
            
            @if(count($attachments) > 0)
                @php
                    $count = count($attachments);
                @endphp
                
                @if($count === 1)
                    <!-- Single Image Layout (50% width aligned to left) -->
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr>
                            <td style="width: 50%; padding: 4px; vertical-align: top;">
                                <div style="width: 100%; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; text-align: center; overflow: hidden;">
                                    @if($attachments[0]->base64_data)
                                        <img src="{{ $attachments[0]->base64_data }}" style="width: 100%; height: auto; display: block; margin: 0 auto;">
                                    @else
                                        <div style="color: #94a3b8; font-size: 9pt; padding: 40px 0;">{{ app()->getLocale() == 'en' ? 'Image Missing' : 'Gambar Tidak Ditemukan' }}</div>
                                    @endif
                                </div>
                                @if($attachments[0]->caption)
                                    <div style="font-size: 8pt; color: #475569; margin-top: 4px; font-weight: 600; text-align: center; line-height: 1.2;">{{ $attachments[0]->caption }}</div>
                                @endif
                            </td>
                            <td style="width: 50%; padding: 4px;"></td>
                        </tr>
                    </table>
                @elseif($count === 2)
                    <!-- Two Image Layout -->
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr>
                            @foreach($attachments as $attachment)
                                <td style="width: 50%; padding: 4px; vertical-align: top;">
                                    <div style="width: 100%; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; text-align: center; overflow: hidden;">
                                        @if($attachment->base64_data)
                                            <img src="{{ $attachment->base64_data }}" style="width: 100%; height: auto; display: block; margin: 0 auto;">
                                        @else
                                            <div style="color: #94a3b8; font-size: 9pt; padding: 30px 0;">{{ app()->getLocale() == 'en' ? 'Image Missing' : 'Gambar Tidak Ditemukan' }}</div>
                                        @endif
                                    </div>
                                    @if($attachment->caption)
                                        <div style="font-size: 8pt; color: #475569; margin-top: 4px; font-weight: 600; text-align: center; line-height: 1.2;">{{ $attachment->caption }}</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </table>
                @else
                    <!-- Grid 2x2 Layout (for 3 or 4 images) -->
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                        @foreach($attachments->chunk(2) as $row)
                            <tr>
                                @foreach($row as $attachment)
                                    <td style="width: 50%; padding: 4px; vertical-align: top;">
                                        <div style="width: 100%; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; text-align: center; overflow: hidden;">
                                            @if($attachment->base64_data)
                                                <img src="{{ $attachment->base64_data }}" style="width: 100%; height: auto; display: block; margin: 0 auto;">
                                            @else
                                                <div style="color: #94a3b8; font-size: 9pt;">{{ app()->getLocale() == 'en' ? 'Image Missing' : 'Gambar Tidak Ditemukan' }}</div>
                                            @endif
                                        </div>
                                        @if($attachment->caption)
                                            <div style="font-size: 7.5pt; color: #475569; margin-top: 3px; font-weight: 600; text-align: center; line-height: 1.2;">{{ $attachment->caption }}</div>
                                        @endif
                                    </td>
                                @endforeach
                                @if($row->count() < 2)
                                    <td style="width: 50%; padding: 4px;"></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                @endif
            @else
                <div style="padding: 20px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1; text-align: center; color: #94a3b8; font-size: 9pt; margin-bottom: 15px;">
                    {{ app()->getLocale() == 'en' ? 'No work documentation photos uploaded' : 'Tidak ada foto dokumentasi pekerjaan yang diunggah' }}
                </div>
            @endif

            <!-- Field Technicians Section -->
            @if($receipt->invoice && $receipt->invoice->technician_names)
                <div style="margin-bottom: 20px;">
                    <span class="section-label" style="font-size: 10pt; color: #0f172a; border-bottom: 2px solid #c89d3c; padding-bottom: 2px; margin-bottom: 6px;">TEKNISI LAPANGAN</span>
                    <div style="font-size: 9pt; color: #0f172a; font-weight: bold; background: #f8fafc; padding: 10px 15px; border-radius: 6px; border-left: 3px solid #1FAF5A;">
                        {{ $receipt->invoice->technician_names }}
                    </div>
                </div>
            @endif

            <!-- Penyebab Section -->
            @if($receipt->invoice && $receipt->invoice->cause_of_problem)
                <div style="margin-bottom: 15px;">
                    <span class="section-label" style="font-size: 10pt; color: #0f172a; border-bottom: 2px solid #c89d3c; padding-bottom: 2px; margin-bottom: 6px;">PENYEBAB</span>
                    <div style="font-size: 9pt; color: #334155; line-height: 1.4; background: #f8fafc; padding: 10px 15px; border-radius: 6px; border-left: 3px solid #c89d3c;">
                        {{ $receipt->invoice->cause_of_problem }}
                    </div>
                </div>
            @endif

            <!-- Catatan Section -->
            <div style="margin-bottom: 20px;">
                <span class="section-label" style="font-size: 10pt; color: #0f172a; border-bottom: 2px solid #c89d3c; padding-bottom: 2px; margin-bottom: 6px;">CATATAN</span>
                <div style="font-size: 9pt; color: #475569; line-height: 1.5; background: #f8fafc; padding: 10px 15px; border-radius: 6px; font-style: italic; border-left: 3px solid #0f172a;">
                    Pekerjaan ini telah diverifikasi langsung di lokasi oleh teknisi kami menggunakan peralatan presisi tinggi, sesuai dengan standar kualitas J&J GROUP.
                </div>
                @if($receipt->notes)
                <div style="font-size: 8.5pt; color: #64748b; line-height: 1.4; padding-left: 10px; margin-top: 8px;">
                    <strong>Catatan Tambahan:</strong> {{ $receipt->notes }}
                </div>
                @endif
            </div>

            <!-- Footer Halaman 2 -->
            <div class="footer">
                J&J GROUP PLUMBING SERVICE | SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR!
            </div>
        </div>
    </div>
</body>
</html>
