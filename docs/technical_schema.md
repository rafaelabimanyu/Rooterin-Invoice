# Technical Schema - Rooterin Invoice

## 1. Database Overview
Database menggunakan engine MySQL/MariaDB dengan dukungan relasi antar tabel (Foreign Keys) dan integritas data (Soft Deletes).

## 2. Entity Relationship Diagram (ERD) Logic
- **Users**: Tabel utama otorisasi. Menjadi parent untuk `ActivityLog` dan `Invoice` (sebagai pencipta).
- **Clients**: Master data klien. Berelasi 1:N dengan `Invoices` dan `Receipts`.
- **Invoices**: Tabel transaksi utama. Memiliki relasi 1:N dengan `InvoiceItems`, `Payments`, dan `InvoiceAttachments`.
- **Receipts**: (Sebelumnya Quotations) Tabel transaksi awal. Memiliki relasi 1:N dengan `ReceiptItems`.

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

### B. Tabel: `clients`
Master data pelanggan.
| Kolom | Tipe Data | Nullable | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | No | ID Klien |
| `kode_client` | String | No | Format: CLI-XXXX (Unique) |
| `nama_client` | String | No | Nama PIC/Klien |
| `nama_perusahaan`| String | Yes | Nama Entitas Bisnis |
| `client_type` | String | Yes | Personal / Corporate |
| `status` | Enum | No | aktif / nonaktif |

### C. Tabel: `invoices`
Data header penagihan.
| Kolom | Tipe Data | Nullable | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | No | ID Invoice |
| `invoice_number` | String | No | Format: ROOT-INV-XXXX (Unique) |
| `client_id` | ForeignID | No | FK ke `clients.id` |
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
1. **Soft Deletes**: Diterapkan pada `User`, `Client`, `Invoice`, dan `Receipt` untuk menjaga integritas histori data.
2. **On Delete Cascade**: Item-item detail akan otomatis terhapus jika parent-nya (Invoice/Receipt) dihapus secara permanen.
3. **Audit Tracking**: Setiap entri baru pada tabel transaksi memicu pencatatan pada `activity_logs`.
