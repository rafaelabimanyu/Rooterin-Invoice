# Technical Schema - J&J Group Invoice

## 1. Database Overview
Database menggunakan engine MySQL/MariaDB (default di lingkungan production) dengan dukungan relasi antar tabel (Foreign Keys), indeks performa, dan pemulihan data (Soft Deletes). Driver SQLite juga didukung untuk keperluan pengujian lokal / automated testing.

## 2. Entity Relationship Diagram (ERD) Logic
- **Users**: Tabel utama otorisasi. Menyimpan data login, preferensi lokal, status Two-Factor Authentication (2FA), dan menjadi parent untuk `ActivityLog` serta `Invoice` (sebagai pencipta).
- **Clients**: Master data klien. Berelasi 1:N dengan `Invoices` dan `Receipts`.
- **Invoices**: Tabel transaksi penagihan utama. Memiliki relasi 1:N dengan `InvoiceItems` (detail item), `Payments` (riwayat pembayaran), dan `InvoiceAttachments` (file bukti lampiran fisik pekerjaan).
- **Receipts**: Tabel transaksi awal / penawaran harga. Memiliki relasi 1:N dengan `ReceiptItems`.
- **Business Units**: Tabel unit bisnis/divisi internal J&J Group. Berelasi 1:N dengan `Invoices` untuk melacak omset dan menghitung nominal profit sharing.

---

## 3. Data Dictionary

### A. Tabel: `users`
Menyimpan data otentikasi dan profil pengguna.
| Kolom | Tipe Data | Nullable | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | No | Auto increment ID |
| `name` | String | No | Nama lengkap user |
| `email` | String | No | Email (Unique) untuk login |
| `role` | Enum | No | owner, admin, staff |
| `is_active` | Boolean | No | Status aktif login |
| `locale` | String | Yes | Preferensi bahasa (id/en) |
| `two_factor_secret` | Text | Yes | Enkripsi secret key untuk 2FA TOTP |

### B. Tabel: `clients`
Master data pelanggan.
| Kolom | Tipe Data | Nullable | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | No | ID Klien |
| `kode_client` | String | No | Format: CLI-XXXX (Unique) |
| `nama_client` | String | No | Nama PIC/Klien |
| `nama_perusahaan`| String | Yes | Nama Entitas Bisnis / PT / CV |
| `client_type` | String | Yes | Personal / Corporate |
| `status` | Enum | No | aktif / nonaktif |

### C. Tabel: `invoices`
Data header penagihan.
| Kolom | Tipe Data | Nullable | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | No | ID Invoice |
| `invoice_number` | String | No | Format: INV-{number}-{year} (Unique) |
| `client_id` | ForeignID | No | FK ke `clients.id` |
| `business_unit_id` | ForeignID | Yes | FK ke `business_units.id` |
| `subtotal` | Decimal | No | Total sebelum tax/disc |
| `tax_percent` | Decimal | No | Persentase pajak |
| `discount_percent`| Decimal | No | Persentase diskon |
| `total` | Decimal | No | Nilai akhir (kalkulasi) |
| `status` | Enum | No | draft, sent, paid, overdue, dll |
| `created_by` | ForeignID | No | FK ke `users.id` |

### D. Tabel: `invoice_items`
Detail baris pada invoice.
| Kolom | Tipe Data | Nullable | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | No | ID Item |
| `invoice_id` | ForeignID | No | FK ke `invoices.id` |
| `deskripsi` | String | No | Deskripsi produk/jasa |
| `qty` | Decimal | No | Jumlah unit |
| `harga` | Decimal | No | Harga per unit |
| `total` | Decimal | No | qty * harga |

---

## 4. Constraint & Rules
1. **Soft Deletes**: Diterapkan pada model `User`, `Client`, `Invoice`, dan `Receipt` untuk menjaga integritas histori data (tidak langsung terhapus permanen dari database).
2. **On Delete Cascade**: Item-item detail (seperti `InvoiceItems`) akan otomatis terhapus jika parent-nya (Invoice/Receipt) dihapus secara permanen (`force delete` / `purge`).
3. **Audit Tracking**: Setiap entri baru pada tabel transaksi memicu pencatatan otomatis pada `activity_logs`.
