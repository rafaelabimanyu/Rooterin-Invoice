# Modul Halaman: Trash Management (Tempat Sampah)

Trash Management adalah modul keamanan data khusus yang dirancang untuk mencegah kehilangan data keuangan secara tidak sengaja di dalam sistem **J&J Group Invoice**. Modul ini menyediakan antarmuka terpusat bagi pengguna dengan hak akses tinggi untuk memulihkan (Restore) atau menghapus secara permanen (Purge) entitas yang telah dihapus sementara (Soft Delete).

---

## 🔗 Route & Akses
- **Halaman Utama**: `/trash`
- **Controller**: `App\Http\Controllers\TrashController`
- **Hak Akses**: `owner` & `admin`. Peran `staff` diblokir secara ketat dari halaman ini (`abort_if` check returning 403).

---

## 🛡️ Prinsip Keamanan & Soft Deletes

Sistem **J&J Group Invoice** menerapkan trait `Illuminate\Database\Eloquent\SoftDeletes` pada model-model utama: `User`, `Client`, `Invoice`, dan `Receipt`.
*   Ketika pengguna menghapus data di halaman operasional biasa, record tidak terhapus dari database melainkan kolom `deleted_at` terisi dengan penanda waktu saat ini.
*   Pemberian metadata tambahan: Pada saat soft-delete dilakukan, sistem mencatat **siapa yang menghapus** (`deleted_by`) dan **alasan penghapusan** (`deletion_reason`) sebagai bentuk akuntabilitas forensik.

---

## ⚙️ Fungsionalitas Modul Trash

Di dalam halaman `/trash`, data dibagi menjadi tiga tab kategori:

### 1. Invoices (Faktur)
Menampilkan daftar invoice yang di-soft-delete beserta total tagihan, nama penghapus, dan alasannya.
*   **Pemulihan (Restore)**: Mengembalikan invoice ke daftar aktif, mereset `deleted_by` dan `deletion_reason` menjadi `null`, serta mencatat aktivitas ke log audit.
*   **Penghapusan Permanen (Purge)**:
    - Menghapus semua file fisik bukti foto lampiran pekerjaan dari disk penyimpanan (`Storage::disk('public')->delete()`).
    - Menghapus record detail item pekerjaan (`invoice_items`).
    - Menghapus secara permanen data kwitansi (`receipts`) yang terhubung jika ada.
    - Menghapus record invoice secara permanen dari database (`forceDelete()`).

### 2. Receipts / Kwitansi (Tanda Terima)
Menampilkan kwitansi yang di-soft-delete.
*   **Pemulihan (Restore)**: Mengembalikan kwitansi ke daftar aktif dan mencatat log aktivitas.
*   **Penghapusan Permanen (Purge)**: Menghapus record kwitansi secara permanen dari database.

### 3. Clients (Klien)
Menampilkan data klien/pelanggan yang dihapus sementara.
*   **Pemulihan (Restore)**: Mengembalikan klien ke status aktif.
*   **Penghapusan Permanen (Purge)**:
    - Sistem menerapkan proteksi integritas data transaksional.
    - Jika klien masih memiliki invoice/receipt yang aktif atau terarsipkan di tabel lain, database akan menolak penghapusan permanen (Foreign Key Constraint).
    - Controller menangkap exception ini dan menampilkan pesan kesalahan: *"Tidak dapat menghapus klien. Klien ini memiliki invoice yang terkait."* untuk menghindari yatim-piatu data (orphaned data).

---

## 📝 Forensik Log Audit (Audit Trail)
Setiap aksi pemulihan atau penghapusan permanen di dalam modul ini akan tercatat sebagai aksi audit di tabel `activity_logs`:
- `restored_invoice` / `purged_invoice`
- `restored_receipt` / `purged_receipt`
- `restored_client` / `purged_client`
