# 📝 Panduan Operasional: Operational Staff

Selamat bekerja! Sebagai garda depan operasional **J&J Group Invoice**, fokus utama Anda adalah melayani pelanggan melalui pembuatan Invoice dan Receipt secara cepat dan akurat.

## ✍️ Tanggung Jawab Utama
1. **Penerbitan Dokumen**: Membuat penawaran (Receipt) dan menagih pembayaran (Invoice).
2. **Update Status**: Memperbarui status pembayaran jika klien sudah melakukan transfer.
3. **Konversi Dokumen**: Mengubah Receipt yang disetujui klien menjadi Invoice resmi dalam satu klik.

## 🔗 Daftar URL Penting (Routes)
| Modul | URL Route | Fungsi |
| :--- | :--- | :--- |
| **Create Invoice** | `/invoices/create` | Membuat penagihan baru untuk klien. |
| **Create Receipt** | `/receipts/create` | Membuat penawaran harga/quotation. |
| **Dashboard Staff** | `/dashboard` | Melihat ringkasan pekerjaan harian Anda. |
| **Payment Log** | `/payments` | Mencatat bukti pembayaran dari klien. |

## ⚠️ Kebijakan Penting (Wajib Dibaca)
- **Aturan 24 Jam**: Berdasarkan kebijakan `InvoicePolicy`, Anda hanya dapat mengedit atau menghapus Invoice/Receipt yang Anda buat sendiri dalam kurun waktu **24 jam terakhir**. Jika lewat dari waktu tersebut, Anda harus meminta bantuan **Admin** untuk melakukan perubahan.
- **Perhitungan Otomatis**: Gunakan sistem itemization saat input data. Sistem secara otomatis menghitung Pajak dan Diskon menggunakan `CalculatesTotals` engine untuk menghindari kesalahan manual.
- **Lampiran**: Pastikan mengunggah foto dokumentasi pekerjaan pada kolom lampiran invoice sebelum dokumen dikirim.
