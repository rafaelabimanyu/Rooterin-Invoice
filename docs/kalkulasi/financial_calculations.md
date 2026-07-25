# Aturan Bisnis: Kalkulasi Finansial & Profit Sharing

Dokumen ini mendokumentasikan seluruh logika perhitungan matematika keuangan dan aturan bisnis yang digunakan di dalam sistem **J&J Group Invoice** untuk menjamin akurasi data finansial di seluruh modul (Invoice, Receipt, Business Units, dan Owner KPI).

---

## 📄 1. Kalkulasi Total Transaksi (Invoices & Receipts)

Semua dokumen penagihan (`Invoice`) dan penawaran harga (`Receipt`) menggunakan trait bersama **`App\Traits\CalculatesTotals`** untuk menjamin konsistensi formula matematika. Hal ini mencegah perbedaan perhitungan antara versi website dan versi cetak PDF.

### Formula Perhitungan
1.  **Subtotal**:
    Jumlah perkalian kuantitas (`qty`) dan harga satuan (`harga`) dari seluruh baris item pekerjaan.
    $$\text{Subtotal} = \sum (\text{Qty} \times \text{Harga})$$
2.  **Tax Nominal (Pajak PPN/PPh)**:
    Persentase pajak dihitung langsung dari nilai Subtotal sebelum diskon.
    $$\text{Tax Nominal} = \text{Subtotal} \times \left(\frac{\text{Tax Percent}}{100}\right)$$
3.  **Discount Nominal (Potongan Harga)**:
    Persentase potongan harga dihitung langsung dari nilai Subtotal.
    $$\text{Discount Nominal} = \text{Subtotal} \times \left(\frac{\text{Discount Percent}}{100}\right)$$
4.  **Grand Total (Nilai Akhir Penagihan)**:
    Nilai akhir yang harus dibayarkan oleh klien.
    $$\text{Grand Total} = \text{Subtotal} + \text{Tax Nominal} - \text{Discount Nominal}$$

---

## 🏢 2. Kalkulasi Profit Sharing Unit Bisnis (Bagi Hasil Divisi)

Setiap unit bisnis internal J&J Group memegang hak bagi hasil (fee sharing) yang berbeda untuk setiap proyek yang diselesaikan. Logika kalkulasi ini diatur di dalam **`App\Services\BusinessUnitReportingService`** dan disajikan di halaman detail unit bisnis serta laporan ekspor PDF.

### Formula Perhitungan
1.  **Gross Revenue (Pendapatan Kotor)**:
    Akumulasi seluruh pembayaran riil yang berhasil dicatat masuk (`payments`) pada invoice yang terafiliasi dengan unit bisnis tersebut selama rentang waktu yang difilter.
    $$\text{Gross Revenue} = \sum (\text{Payment Amount})$$
2.  **Fee Nominal (Pajak Kerja Divisi)**:
    Nominal bagi hasil yang diklaim oleh pusat/organisasi utama J&J Group berdasarkan persentase fee unit bisnis. Hasil dibulatkan hingga 2 desimal demi presisi akuntansi.
    $$\text{Fee Nominal} = \text{Round}\left(\frac{\text{Gross Revenue} \times \text{Fee Percentage}}{100}, 2\right)$$
3.  **Net Revenue (Pendapatan Bersih Divisi)**:
    Sisa pendapatan bersih milik divisi/unit bisnis setelah dikurangi nominal bagi hasil pusat.
    $$\text{Net Revenue} = \text{Round}(\text{Gross Revenue} - \text{Fee Nominal}, 2)$$

---

## 📊 3. Analisis Indikator Risiko Finansial (AI Predictive Insights)

Modul **`App\Services\PredictiveInsightService`** bertugas menganalisis kesehatan keuangan secara berkala berdasarkan data transaksi aktif dengan aturan batas sebagai berikut:

*   **Rasio Kolektibilitas (Collection Rate)**:
    $$\text{Collection Rate} = \left(\frac{\text{Jumlah Invoice Lunas}}{\text{Total Seluruh Invoice}}\right) \times 100$$
    - Jika $< 75\%$: Berstatus **Kritis (Danger)**. Merekomendasikan Term of Payment baru dan DP minimal 30%.
    - Jika $75\% - 84\%$: Berstatus **Warning**. Merekomendasikan pengaktifkan pengingat H-3.
    - Jika $\ge 85\%$: Berstatus **Sehat (Success)**. Pertahankan skema penagihan berjalan.
*   **Piutang Macet (Overdue Receivables Ratio)**:
    $$\text{Overdue Ratio} = \left(\frac{\text{Outstanding Jatuh Tempo (Overdue)}}{\text{Total Piutang Aktif}}\right) \times 100$$
    - Jika $> 20\%$: Berstatus **Bahaya (Danger)**. Merekomendasikan pengiriman Surat Peringatan (SP) pertama.
*   **Konsentrasi Risiko Kredit Klien (Client Concentration Risk)**:
    - Jika salah satu klien memegang $> 40\%$ dari total tunggakan aktif perusahaan.
    - Status: **Warning**. Merekomendasikan penangguhan penambahan order baru untuk klien tersebut hingga tunggakan dilunasi.
