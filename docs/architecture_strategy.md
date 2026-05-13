# Architecture Strategy - Rooterin Invoice

## 1. System Overview
Rooterin Invoice adalah sistem manajemen penagihan berbasis web yang dibangun menggunakan **Laravel 11** dan **Livewire**. Sistem ini dirancang untuk menangani alur kerja dari penawaran (Receipt/Quotation) hingga penagihan (Invoice) dengan sistem otorisasi berbasis peran (RBAC).

## 2. Design Patterns & Principles

### A. Model-View-Controller (MVC)
Sistem mengikuti pola MVC standar Laravel untuk memisahkan logika data, presentasi, dan alur aplikasi.

### B. Trait-Based Calculation
Logika finansial (subtotal, tax, discount) dipusatkan menggunakan `App\Traits\CalculatesTotals`. Hal ini memastikan konsistensi perhitungan di seluruh modul (Invoice & Receipt) dan memudahkan pemeliharaan rumus finansial.

### C. Policy-Based Authorization
Otorisasi tidak lagi dilakukan secara manual di Controller. Sistem menggunakan **Laravel Policies** untuk menentukan hak akses user terhadap resource (Invoice/Receipt). Hal ini memisahkan logika bisnis dari logika keamanan.

## 3. Security & Access Control

### User Roles
- **Owner**: Akses penuh ke seluruh sistem, pengaturan, dan laporan keuangan tingkat tinggi.
- **Admin**: Manajemen operasional penuh, manajemen user, dan pengawasan transaksi.
- **Staff**: Role operasional dengan batasan akses data mandiri dan jendela waktu edit terbatas (24 jam).

### Audit Trail
Setiap perubahan data krusial dicatat dalam tabel `activity_logs` melalui helper `ActivityLog::log()`, mencatat siapa, kapan, dan apa yang berubah.

## 4. Key Workflows

### A. Receipt to Invoice Conversion
Sistem memungkinkan konversi langsung dari `Receipt` ke `Invoice`. Selama proses ini, seluruh metadata finansial dan item dipindahkan ke tabel invoice baru, sementara status receipt berubah menjadi `invoiced`.

### B. Staff Restriction Logic
Keamanan data dijaga dengan memastikan staff tidak dapat mengubah histori transaksi lama. Kebijakan 24 jam memastikan input data tetap akurat dan meminimalkan risiko manipulasi data masa lalu.

## 5. Technology Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Livewire, Tailwind CSS
- **PDF Engine**: DomPDF (Barryvdh/Laravel-DomPDF)
- **Tooling**: Vite (Assets Bundling)
