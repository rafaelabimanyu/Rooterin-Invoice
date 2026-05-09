# Rooterin-Invoice
### Smart Invoice & Business Management System
[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-black.svg)](#)

**Rooterin-Invoice** adalah platform manajemen keuangan dan penagihan tingkat perusahaan (Enterprise-Grade) yang dirancang khusus untuk sektor jasa teknis, kontraktor, dan pemeliharaan properti. Sistem ini mengubah alur kerja manual menjadi ekosistem digital yang efisien, transparan, dan profesional.

---

## 🚀 Overview
Rooterin-Invoice bukan sekadar aplikasi pembuat invoice. Ini adalah solusi **Business Intelligence** yang membantu pemilik bisnis mengelola siklus hidup pembayaran dari penawaran harga (*Quotation*) hingga laporan pendapatan (*Revenue Reports*). Terinspirasi oleh standar SaaS global seperti Stripe dan QuickBooks, Rooterin menghadirkan estetika korporat yang mewah ke dalam operasional harian Anda.

### Target Users:
*   👷 **Contractors** (Konstruksi & Renovasi)
*   🚰 **Plumbing Services**
*   ⚡ **Technical & Electrical Engineers**
*   🛠️ **Maintenance Companies**
*   🏢 **SME Businesses** (UMKM Profesional)

---

## ✨ Key Features

### 💎 Core Business Modules
*   **Client Ledger**: Manajemen database klien dengan riwayat penagihan yang komprehensif.
*   **Smart Invoicing**: Pembuatan invoice dengan kalkulasi otomatis (Subtotal, PPN, Diskon).
*   **Quotation System**: Proposalkan harga ke klien dan konversi menjadi invoice dalam satu klik.
*   **Payment Tracking**: Pencatatan termin pembayaran (DP, Partial, Full Payment) secara presisi.
*   **Advanced Reporting**: Visualisasi performa bisnis dan arus kas masuk secara *real-time*.

### 🛠️ Enterprise Capabilities
*   **PDF Generation**: Dokumen invoice B2B profesional siap kirim.
*   **Job Documentation**: Lampirkan foto bukti pekerjaan langsung di dalam invoice.
*   **Role-Based Access Control (RBAC)**: Pembatasan akses aman untuk Owner, Admin, dan Staff.
*   **Multi-Language Support**: Antarmuka dalam Bahasa Indonesia dan English.
*   **In-App Guide**: Sistem panduan internal untuk mempercepat proses *onboarding* tim.

---

## 💻 Tech Stack
Sistem dibangun menggunakan teknologi modern untuk memastikan performa yang stabil dan aman:
*   **Framework**: [Laravel 11](https://laravel.com)
*   **Frontend**: Tailwind CSS v3 & Alpine.js
*   **Icons**: Lucide Icons
*   **Database**: MySQL / MariaDB
*   **PDF Engine**: DomPDF
*   **Design System**: Bespoke Corporate SaaS Design

---

## 🛠️ Installation Guide

### Prerequisites
*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   MySQL Server

### Step-by-Step Setup
1.  **Clone the Repository**
    ```bash
    git clone https://github.com/rafaelabimanyu/Rooterin-Invoice.git
    cd Rooterin-Invoice
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Configuration**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Setup**
    Configure your `.env` with your database credentials, then run:
    ```bash
    php artisan migrate --seed
    ```
    *Note: Seeder ini akan membuat data dummy realistis (Owner, Admin, Clients, Invoices) untuk keperluan demo.*

5.  **Launch Application**
    ```bash
    php artisan serve
    npm run dev
    ```

---

## 👥 User Roles & Permissions
| Feature | Owner | Admin | Staff |
| :--- | :---: | :---: | :---: |
| Dashboard & Analytics | ✅ | ✅ | ✅ |
| Client & Invoicing | ✅ | ✅ | ✅ |
| Team Management | ✅ | ✅ | ❌ |
| System Settings | ✅ | ✅ | ❌ |
| Activity Logs | ✅ | ❌ | ❌ |

---

## 📈 Business Workflow
Alur kerja standar dalam ekosistem Rooterin:
1.  **Lead to Quote**: Daftarkan klien dan buat penawaran harga (*Quotation*).
2.  **Agreement**: Klien menyetujui penawaran.
3.  **Invoice Issuance**: Konversi Quotation menjadi **Invoice**.
4.  **Work Documentation**: Unggah foto bukti pengerjaan ke invoice.
5.  **Payment Collection**: Catat pembayaran (DP atau Pelunasan).
6.  **Reporting**: Pantau pertumbuhan pendapatan di halaman laporan.

---

## 🗺️ Future Roadmap
*   [ ] **WhatsApp Integration**: Kirim invoice langsung via WA API.
*   [ ] **Email Notifications**: Pengingat otomatis untuk invoice yang hampir jatuh tempo.
*   [ ] **Payment Gateway**: Integrasi Midtrans/Xendit untuk pembayaran otomatis.
*   [ ] **Multi-Company Support**: Kelola banyak perusahaan dalam satu dashboard.
*   [ ] **Client Portal**: Akses khusus untuk klien melihat riwayat tagihan mereka sendiri.

---

## 📂 Project Structure (Overview)
```text
app/
 ├── Http/Controllers/   # Business Logic
 ├── Models/             # Database Entities
 ├── Middleware/         # Security & Role Logic
database/
 ├── migrations/         # Schema Definitions
 ├── seeders/            # Realistic Demo Data
resources/
 ├── views/              # Blade Templates (Premium UI)
 ├── lang/               # Localization (EN/ID)
routes/
 ├── web.php             # Application Routes
```

---

## 📄 License
**Proprietary Software**. Project ini dikembangkan sebagai sistem internal perusahaan Rooterin. Penggunaan, modifikasi, atau distribusi tanpa izin tertulis dari pihak Rooterin sangat dilarang.

---

## 📞 Contact & Support
**Rooterin Enterprise System**
*   **Website**: [www.rooterin.com](https://www.rooterin.com)
*   **Support**: support@rooterin.com
*   **Developer**: [Fajar / Antigravity AI]

---
*Created with passion for professional business operations.*
