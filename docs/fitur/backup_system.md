# Fitur Sistem: Backup & Restore (Database & Dokumentasi Pekerjaan)

Fitur Backup di dalam **J&J Group Invoice** dirancang untuk menjaga kelangsungan bisnis (business continuity) dengan mengamankan data transaksi (database SQL) dan berkas bukti fisik pekerjaan lapangan (attachment/foto dokumentasi) secara berkala.

---

## 🔗 Route & Kelas Utama
- **URL Halaman**: `/backup`
- **Controller**: `App\Http\Controllers\BackupController`
- **Service Layer**: `App\Services\BackupService`
- **Hak Akses**: `owner` dan `admin`. Peran `staff` diblokir dari akses halaman ini.

---

## ⚙️ Komponen Sistem Backup

Sistem backup terbagi menjadi dua kategori independen:

### 1. Database Backup (Cadangan Data Transaksi)
Mengamankan seluruh isi database (tabel, struktur, relasi, dan baris data) dalam format SQL dump terkompresi ZIP.
*   **Kompatibilitas Multi-Driver**: `BackupService` mendeteksi driver database secara otomatis:
    - *MySQL/MariaDB*: Menggunakan kueri `SHOW TABLES` dan `SHOW CREATE TABLE` untuk menyusun struktur SQL.
    - *SQLite*: Menggunakan kueri dari `sqlite_master` untuk portabilitas pengujian.
*   **Backup Manual**: Klik tombol **Export Database** pada UI `/backup`. Sistem akan membuat SQL, membungkusnya ke ZIP, memicu unduhan langsung di browser pengguna, dan menghapus berkas sementara dari server setelah unduhan selesai (`deleteFileAfterSend(true)`).
*   **Backup Otomatis (Schedule)**:
    - Konfigurasi status (On/Off), frekuensi (Daily/Weekly), dan jam eksekusi (misal: 23:00) yang disimpan di tabel `settings`.
    - Dijalankan via Laravel Task Scheduler (Console Command) yang memanggil `generateBackup(true)`.

### 2. Job Documentation Backup (Cadangan Foto Bukti Lapangan)
Mengompres foto-foto bukti pekerjaan lapangan (sebelum/sesudah pengerjaan pembersihan saluran) yang diunggah oleh staff ke dalam satu arsip ZIP.
*   **Format Penamaan File ZIP**: Nama file foto di dalam ZIP akan diawali dengan nomor invoice terkait (misal: `INV_12_2026_foto_saluran.jpg`) untuk memudahkan pencarian berkas fisik secara manual oleh tim audit eksternal.
*   **Backup Manual**: Memerlukan parameter filter rentang tanggal (`start_date` dan `end_date`). Berguna jika owner ingin mengunduh seluruh dokumentasi pekerjaan untuk bulan tertentu saja.
*   **Backup Otomatis (Schedule)**:
    - Konfigurasi status (On/Off), frekuensi (Daily/Weekly/Monthly), dan jam eksekusi yang disimpan di tabel `settings`.
    - Dijalankan via Scheduler.

---

## 🕒 Kebijakan Rotasi & Retensi File Backup (Backup Rotation)

Untuk mencegah kepenuhan kapasitas penyimpanan (disk space depletion) pada server akibat backup otomatis, `BackupService` menerapkan aturan pembersihan otomatis:
*   Setiap kali backup otomatis dijalankan, sistem memanggil fungsi `rotateBackups()` dan `rotateDocsBackups()`.
*   Sistem akan mencari file backup ZIP otomatis di folder `storage/app/backups/automated/` dan `storage/app/backups/docs/automated/`.
*   File backup otomatis yang memiliki usia modifikasi **lebih dari 7 hari** akan dihapus secara permanen dari server secara otomatis.
*   *Catatan:* Backup manual yang diunduh langsung ke komputer pengguna tidak memengaruhi kapasitas penyimpanan server dan tidak masuk dalam siklus rotasi ini.
