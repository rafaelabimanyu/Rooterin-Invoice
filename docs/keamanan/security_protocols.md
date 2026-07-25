# Protokol Keamanan & Perlindungan Sistem

Sistem **J&J Group Invoice** memprioritaskan keamanan data transaksi dan administrasi. Dokumen ini menjelaskan empat lapisan pengamanan utama yang melindungi panel kontrol, data login, dan riwayat aktivitas tim.

---

## 🔑 1. Autentikasi Multi-Faktor (TOTP MFA / 2FA)

Sistem mengimplementasikan pengamanan ganda di atas kata sandi standar menggunakan standar algoritma sandi sekali waktu berbasis waktu (**TOTP - Time-Based One-Time Password**).
*   **Enkripsi Kunci Rahasia**: Kunci rahasia (Secret Key) untuk integrasi dengan Google Authenticator atau Authy disimpan di dalam tabel `users` pada kolom `two_factor_secret`. Nilai rahasia ini dienkripsi secara aman menggunakan enkripsi simetris **AES-256-CBC** sebelum disimpan ke database.
*   **Pemulihan Akun (Recovery Codes)**: Sistem menghasilkan 8 buah kode pemulihan sekali pakai (recovery codes) yang di-hash menggunakan algoritma **bcrypt / Argon2id** pada saat 2FA diaktifkan, meminimalkan risiko pengambilalihan akun jika perangkat autentikator hilang.

---

## 🛡️ 2. Perlindungan Mode Sudo (Sudo-Mode Protection)

Area konfigurasi sensitif (seperti halaman pengaturan global, backup database, dan pusat keamanan) dilindungi oleh gerbang verifikasi identitas ulang (Sudo-Mode).

### Alur Kerja & Kebijakan
1.  **Deteksi Sesi**: Saat user mengakses halaman terproteksi, Livewire component (`SecurityCommandCenter`) akan mendeteksi penanda waktu `sudo_verified_at` di dalam session browser.
2.  **Batas Waktu Validitas Sesi (TTL)**: Sesi Sudo berlaku selama **2 jam (7200 detik)** sejak verifikasi kata sandi terakhir kali berhasil dilakukan. Jika batas waktu ini terlewati, pengguna harus memasukkan kata sandi kembali untuk mengaktifkan Sudo Mode.
3.  **Rate Limiter & Proteksi Brute Force**:
    - Percobaan verifikasi Sudo Mode dibatasi menggunakan Laravel Rate Limiter dengan key `sudo-verify:{user_id}`.
    - Jika pengguna gagal memasukkan password berkali-kali, akun akan terkunci sementara untuk percobaan Sudo Mode (Rate Limiting Lockout) dan menampilkan pesan: *"Too many attempts. Locked for X seconds."*
4.  **Forensik Log**:
    - Keberhasilan verifikasi mencatat aksi: *"Security Command Center Accessed (Sudo Verified)"*.
    - Kegagalan verifikasi mencatat aksi dengan bendera peringatan: *"Failed Sudo Verification Attempt"* di log aktivitas.

---

## 📡 3. Telemetri Sesi Aktif (Active Session Telemetry)

Owner dan Admin memiliki kemampuan memantau aktivitas sesi secara real-time untuk mendeteksi anomali login (misalnya kebocoran akun / diakses oleh perangkat mencurigakan).
*   **Deteksi Perangkat & Lokasi**: Sistem mem-parsing header request `User-Agent` untuk menampilkan ikon sistem operasi (Windows, Mac, Linux, Android, iOS) dan tipe browser (Chrome, Firefox, Safari, Edge) yang digunakan.
*   **Pemantauan IP Address**: Setiap sesi masuk diikat dengan alamat IP asal klien.
*   **Sesi Pencabutan Instan (Session Revocation)**: Owner dan Admin dapat menonaktifkan atau memaksa keluar (Log Out) sesi perangkat lain secara instan dari panel kontrol `/security-center`.

---

## 🗄️ 4. Log Aktivitas Forensik (Immutable Audit Trail)

Semua tindakan penting di dalam sistem direkam ke tabel `activity_logs` melalui `ActivityLog` helper. Log ini dirancang agar tidak dapat dimodifikasi oleh peran Staff.
*   **Aksi yang Dicatat**:
    - Login sukses / gagal, logout.
    - Pembuatan, pemulihan, dan pembersihan permanen dokumen (Invoice / Receipt / Client).
    - Perubahan data profil pribadi dan pengaturan global aplikasi.
    - Ekspor laporan dan pembuatan cadangan file (Backup SQL / Docs).
*   **Informasi Log**: Setiap baris log mencatat user pelaku aksi, nama tindakan, deskripsi detail bahasa manusia, IP Address, dan penanda waktu (timestamp).
