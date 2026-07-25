# 👑 Panduan Operasional: System Owner

Selamat datang di Panel Kendali Utama **J&J Group Invoice**. Sebagai Owner, Anda memiliki otoritas tertinggi untuk memantau kesehatan finansial perusahaan dan memastikan keamanan data sistem.

## 📊 Tanggung Jawab Utama
1. **Analisis KPI Strategis**: Memantau grafik pertumbuhan pendapatan, rasio kolektibilitas, dan performa penagihan secara real-time.
2. **Pengawasan Keamanan**: Memantau log aktivitas dan pusat keamanan untuk mencegah akses yang tidak sah.
3. **Manajemen Pengguna**: Menambah, menonaktifkan, atau mengubah peran (role) personil di dalam sistem (Owner, Admin, Staff).

## 🔗 Daftar URL Penting (Routes)
| Modul | URL Route | Fungsi |
| :--- | :--- | :--- |
| **KPI Dashboard** | `/owner-kpi` | Laporan performa bisnis tingkat tinggi dan estimasi profit sharing. |
| **Security Center** | `/security-center` | Memantau log keamanan dan aktivitas mencurigakan. |
| **User Management** | `/users` | Mengelola data login personil (Staff/Admin/Owner). |
| **Global Settings** | `/settings` | Mengatur parameter sistem (pajak default, info bank). |
| **Financial Reports** | `/reports` | Laporan rekonsiliasi pembayaran dan invoice. |

## 💡 Kebijakan Akses Owner
- **Full Data Visibility**: Owner tidak dibatasi oleh aturan waktu (24 jam) dan dapat melihat seluruh data histori dari semua personil.
- **System Integrity**: Segala perubahan pada pengaturan sistem akan dicatat sebagai log aktivitas Owner.
