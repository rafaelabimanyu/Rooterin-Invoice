# Modul Halaman: Transaction Ledger

Transaction Ledger (Buku Besar Transaksi) adalah jembatan pelaporan keuangan satu pintu yang menyajikan daftar gabungan seluruh invoice dan kwitansi secara kronologis. Halaman ini dirancang untuk memberikan transparansi penuh kepada Owner, Admin, maupun Staff mengenai seluruh penagihan aktif di dalam sistem **J&J Group Invoice**.

---

## 🔗 Route & Akses
- **URL Route**: `/ledger`
- **Controller**: `App\Http\Controllers\LedgerController`
- **View**: `resources/views/ledger/index.blade.php`
- **Hak Akses**: `owner`, `admin`, dan `staff` (Read-Only)

---

## ⚙️ Fungsionalitas Utama

Halaman Ledger berfungsi sebagai antarmuka read-only (tidak dapat menambah/mengedit transaksi langsung dari sini) untuk meminimalkan risiko manipulasi data keuangan. Fitur utama yang disediakan meliputi:

### 1. Pencarian & Filter Multi-Dimensi
Pengguna dapat memfilter data transaksi secara real-time berdasarkan parameter berikut:
*   **Pencarian Teks**: Mencari berdasarkan nomor invoice (`invoice_number`) atau nama klien/perusahaan (`nama_client` / `nama_perusahaan`).
*   **Filter Status**: Menyaring tagihan berdasarkan status (`draft`, `sent`, `paid`, `overdue`, dll).
*   **Filter Unit Bisnis**: Membatasi pencarian pada unit bisnis tertentu yang terafiliasi dengan invoice.
*   **Filter Tipe Dokumen (Doc Type)**:
    - *Invoice Only*: Menampilkan tagihan yang dibuat secara langsung tanpa melalui proses penawaran (direct invoice).
    - *Has Receipt (Converted)*: Menampilkan tagihan hasil konversi dari modul tanda terima/penawaran (converted from receipt).

### 2. Hubungan Relasional (Relational Bridge)
Setiap baris di dalam ledger menampilkan relasi dokumen secara terpadu:
*   **Klien**: Nama PIC dan perusahaan klien.
*   **Unit Bisnis**: Divisi internal J&J Group yang menangani proyek tersebut.
*   **Kwitansi Asal**: Jika invoice dikonversi dari Receipt, ledger akan menampilkan badge pranala menuju dokumen Receipt/Kwitansi asli.

### 3. Pagination Dinamis
Untuk menjaga performa loading halaman saat data bernilai ribuan, ledger menggunakan pagination otomatis dengan limit **15 baris per halaman**, lengkap dengan pelestarian parameter query string filter saat beralih halaman.
