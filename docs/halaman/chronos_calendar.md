# Modul Halaman: Chronos Calendar (Billing Scheduler)

Chronos Calendar adalah pusat penjadwalan jatuh tempo penagihan interaktif di dalam ekosistem **J&J Group Invoice**. Modul ini menyajikan visualisasi kalender bulanan/mingguan terpadu untuk melacak tenggat waktu invoice dan mengelola janji temu penagihan atau pengingat (reminders) dengan klien.

---

## 🔗 Route & Akses
- **Halaman Utama**: `/chronos`
- **API Fetch Events**: `/api/chronos/events`
- **API Drag & Drop Update**: `/api/chronos/update-event` (POST)
- **Controller**: `App\Http\Controllers\ChronosController`
- **Hak Akses**: `owner` & `admin` memiliki kontrol penuh (bisa melihat semua, menambah reminder, serta memindahkan tanggal jatuh tempo). `staff` tidak diizinkan mengakses halaman ini secara default (`abort_if` check).

---

## ⚙️ Jenis Entitas di Kalender (Events)

Chronos membagi data visualisasi menjadi dua entitas utama:

### 1. Invoice Due Dates (Jatuh Tempo Invoice)
Setiap invoice yang diterbitkan akan otomatis diplot pada kalender berdasarkan kolom `due_date` (tenggat waktu pelunasan).
*   **Identifikasi Warna Status**:
    - <span style="color:#10b981">●</span> **Hijau (Emerald)**: Invoice berstatus *Paid* (Lunas).
    - <span style="color:#f43f5e">●</span> **Merah (Rose)**: Invoice berstatus *Overdue* (Menunggak).
    - <span style="color:#f59e0b">●</span> **Oranye (Amber)**: Invoice berstatus *Draft* (Rancangan).
    - <span style="color:#3b82f6">●</span> **Biru (Blue)**: Invoice berstatus *Sent* (Terkirim).
*   **Aksi Cepat**: Klik pada event invoice untuk membuka pop-up ringkasan detail (Klien, Total Nilai Tagihan, dan Teknisi yang ditugaskan).

### 2. Reminders / ChronosEvents (Pengingat)
Merupakan pengingat manual yang dibuat oleh Owner/Admin untuk janji temu penagihan, jadwal pembersihan saluran mampet, atau koordinasi internal.
*   **Status Reminder**: `meeting`, `draft`, `overdue` (atau custom color).
*   **Aksi Cepat**: Mendukung rentang durasi kerja (multiple-day event) dan dapat dipindahkan/diperpanjang durasinya langsung di kalender.

---

## 📅 Fitur Interaktif Drag & Drop (Pemindahan Tanggal)

Owner dan Admin dapat melakukan penjadwalan ulang secara cepat menggunakan mouse/sentuhan:
1.  **Geser Event (Drag)**: Geser event Invoice atau Reminder dari satu tanggal ke tanggal lainnya.
2.  **Ubah Durasi (Resize)**: Menarik ujung kanan event Reminder untuk memperpanjang tenggat waktu proyek.
3.  **Protokol Keamanan & Audit Trail**:
    - Setiap pemindahan tanggal jatuh tempo invoice akan memicu verifikasi otorisasi di controller.
    - Sistem akan mencatat aksi pemindahan ini ke dalam tabel log aktivitas forensik (`activity_logs`) secara otomatis untuk akuntabilitas data keuangan (contoh log: *"Rafael Abimanyu memindahkan tanggal jatuh tempo Invoice INV-12-2026 ke 2026-07-28 via Kalender"*).
