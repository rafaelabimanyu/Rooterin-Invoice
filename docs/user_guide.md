# J&J GROUP Enterprise - Panduan Operasional & SOP Sistem

Selamat datang di Panduan Operasional Resmi J&J GROUP. Dokumen ini dirancang sebagai acuan standar bagi seluruh Staff, Admin, dan Owner dalam mengoperasikan sistem penagihan dan pelaporan terpadu.

---

## 📋 Daftar Isi / Table of Contents
1. [SOP Pembuatan Tagihan (Invoice Creation SOP)](#1-sop-pembuatan-tagihan-invoice-creation-sop)
2. [Manajemen Tanda Terima / Kwitansi (Receipts Management)](#2-manajemen-tanda-terima--kwitansi-receipts-management)
3. [Modul Teknisi Lapangan (Field Technicians Module)](#3-modul-teknisi-lapangan-field-technicians-module)
4. [Sistem Rekapan Unit Bisnis (Business Unit Reporting)](#4-sistem-rekapan-unit-bisnis-business-unit-reporting)
5. [Integrasi Kecerdasan Buatan (AI Copywriter & Assistant)](#5-integrasi-kecerdasan-buatan-ai-copywriter--assistant)

---

## 1. SOP Pembuatan Tagihan (Invoice Creation SOP)

### Alur Langkah-demi-Langkah (Step-by-Step Flow)
1. **Registrasi Klien & Unit Bisnis**: Sebelum merilis tagihan, pastikan Unit Bisnis dan Akun Klien (termasuk NPWP/alamat) telah terdaftar dengan benar di sistem.
2. **Pengisian Item Pekerjaan**:
   - Tentukan Deskripsi pekerjaan secara mendetail (misal: "Pembersihan Saluran Mampet di Area Dapur").
   - Masukkan kuantitas (Qty) dan harga satuan (Rate) secara akurat.
3. **Kalkulasi Pajak & Diskon**:
   - Cek apakah transaksi dikenakan **PPN** atau **PPh**.
   - Input potongan harga pada kolom **Discount** jika berlaku.
4. **Teknisi Lapangan**: Wajib mencantumkan nama teknisi lapangan yang melakukan pekerjaan di lokasi pada kolom **Teknisi Lapangan / Field Technicians**.
5. **Penyebab Mampet & Dokumentasi**:
   - Masukkan indikasi penyebab mampet pada kolom "Penyebab Mampet".
   - Unggah foto dokumentasi pekerjaan (sebelum/sesudah) sebagai bukti otentik.
6. **Verifikasi**: Lakukan review visual sebelum menyimpan perubahan. Pastikan nama perusahaan, nomor tagihan, dan total nilai tagihan sudah tepat.

---

## 2. Manajemen Tanda Terima / Kwitansi (Receipts Management)

Kwitansi atau Tanda Terima dikeluarkan setelah pembayaran (penuh atau sebagian) diterima dari klien.
- **Catat Pembayaran**: Gunakan tombol "Catat Pembayaran" (Record Payment) di halaman detail Invoice untuk memasukkan nominal pembayaran masuk.
- **Generasi Kwitansi Otomatis**: Sistem akan membuat kwitansi digital secara otomatis berdasarkan riwayat pembayaran yang disimpan.
- **Tanda Tangan Digital**: Kwitansi secara otomatis memuat tanda tangan digital penanggung jawab dan watermark formal "ORIGINAL" untuk validitas hukum.

---

## 3. Modul Teknisi Lapangan (Field Technicians Module)

Modul ini digunakan untuk melacak akuntabilitas personil lapangan yang bertugas mengeksekusi layanan pembersihan di lokasi klien.
- **Penginputan**: Ditulis berupa string nama teknisi yang dipisahkan dengan koma (contoh: `Budi Santoso, Andi Wijaya`).
- **Tampilan PDF**: Nama-nama teknisi akan otomatis tercetak di dokumen PDF Invoice maupun Kwitansi di bagian bawah tabel detail pekerjaan untuk transparansi penuh dengan klien.

---

## 4. Sistem Rekapan Unit Bisnis (Business Unit Reporting)

Sistem menggunakan arsitektur **BusinessUnitReportingService** sebagai satu-satunya sumber kebenaran data (Single Source of Truth).
- **Detail Unit Bisnis**: Setiap unit bisnis memiliki halaman detail performa dinamis.
- **Metrik Utama**:
   - **Total Billed**: Akumulasi seluruh tagihan yang diterbitkan.
   - **Total Revenue**: Total pendapatan riil yang berstatus lunas (`paid`).
   - **Outstanding**: Sisa piutang yang belum terbayar (dikurangi pembayaran parsial).
- **Watermark & Ekspor PDF**: Laporan performa dapat diunduh dalam format PDF dengan branding resmi (Navy & Emerald) dilengkapi watermark transparan "INTERNAL".

---

## 5. Integrasi Kecerdasan Buatan (AI Copywriter & Assistant)

Sistem dilengkapi dengan fitur AI pintar untuk membantu operasional sehari-hari:
- **AI Copywriter**: Membantu merumuskan draf email penagihan secara otomatis berdasarkan nada (tone) yang dipilih:
  - *Sopan & Profesional* (Friendly Reminder)
  - *Tegas & Formal* (Immediate Payment Request)
  - *Urgent* (Mendesak / Batas Waktu Terlewati)
- **AI Chatbot Assistant**: Chatbot internal yang dapat berkonsultasi mengenai SOP penagihan, pencarian data invoice, atau tips teknis penanganan saluran mampet.
