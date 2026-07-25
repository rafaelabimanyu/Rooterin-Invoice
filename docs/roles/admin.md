# 🛡️ Panduan Operasional: System Admin

Sebagai System Admin, peran Anda adalah memastikan kelancaran operasional harian, memelihara integritas data master, dan memverifikasi validitas transaksi yang masuk ke dalam sistem **J&J Group Invoice**.

## 📋 Tanggung Jawab Utama
1. **Manajemen Klien**: Memasukkan dan memperbarui data master pelanggan (`Clients`).
2. **Verifikasi & Approval**: Meninjau draft invoice/receipt yang dibuat oleh staff sebelum dikirim ke pelanggan.
3. **Pemeliharaan Data**: Melakukan koreksi pada transaksi lama yang sudah melewati batas waktu edit staff.

## 🔗 Daftar URL Penting (Routes)
| Modul | URL Route | Fungsi |
| :--- | :--- | :--- |
| **Master Clients** | `/clients` | Mengelola data pelanggan, NPWP, dan kontak. |
| **Invoice Manager** | `/invoices` | Melihat dan mengelola seluruh daftar penagihan. |
| **Receipt Manager** | `/receipts` | Mengelola penawaran/quotation sebelum menjadi invoice. |
| **Data Export** | `/reports` | Mengunduh data transaksi untuk kebutuhan akuntansi. |

## 💡 Kebijakan Akses Admin
- **Override Authority**: Admin memiliki hak akses `InvoicePolicy` yang dikecualikan dari batasan 24 jam. Admin dapat mengedit transaksi lama untuk kebutuhan audit/koreksi.
- **Client Integrity**: Admin bertanggung jawab atas akurasi `kode_client` dan status aktif/nonaktif pelanggan.
