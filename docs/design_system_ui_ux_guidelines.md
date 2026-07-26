# Design System & UI/UX Guidelines
## Aplikasi J&J Group Invoice

Dokumen ini memuat panduan visual, spesifikasi antarmuka pengguna (UI), dan standar pengalaman pengguna (UX) untuk aplikasi **J&J Group Invoice**. Panduan ini dirancang untuk memastikan konsistensi visual, keterbacaan data keuangan tingkat tinggi, dan performa implementasi yang optimal bagi tim frontend developer.

---

## 1. Prinsip Desain (Design Principles)

Desain antarmuka J&J Group Invoice didasarkan pada tiga prinsip utama:

*   **Enterprise Dashboard:** Mengutamakan penyajian informasi yang padat namun bersih. Dashboard dirancang untuk efisiensi navigasi data keuangan, pelacakan invoice, dan manajemen entitas (klien, transaksi, inventaris) dengan kognitif load seminimal mungkin.
*   **Clean Minimalist:** Mengeliminasi elemen dekoratif yang tidak perlu (seperti ilustrasi kartun atau efek bayangan 3D yang berlebihan) untuk menjaga fokus penuh pada konten data angka dan teks.
*   **Premium Corporate:** Memanfaatkan kontras warna yang elegan (gelapnya Navy/Charcoal dipadukan dengan kehangatan emas premium) untuk memberikan impresi profesional, mapan, dan tepercaya.
*   **Rounded Precision:** Menggunakan radius sudut yang konsisten dan lembut (antara `8px` hingga `12px`) untuk melunakkan estetika enterprise yang kaku, menjadikannya modern tanpa kehilangan kesan formal.

---

## 2. Palet Warna (Color Palette)

Palet warna aplikasi dikurasi secara ketat untuk menjaga profesionalisme dan keterbacaan data keuangan. Gunakan kode HEX berikut untuk implementasi:

### A. Warna Utama & Latar Belakang (Main & Background Colors)

| Sampel Visual | Nama Warna | Kode HEX | Peruntukan / Penggunaan |
| :--- | :--- | :--- | :--- |
| <span style="background-color:#FFFFFF; border:1px solid #E5E7EB; color:#111827; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#FFFFFF</span> | **Clean White** | `#FFFFFF` | Area konten utama, latar belakang *card*, dan kolom input data. |
| <span style="background-color:#F8FAFC; border:1px solid #E5E7EB; color:#111827; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#F8FAFC</span> | **Slate Off-White** | `#F8FAFC` | Latar belakang halaman aplikasi utama (*page wrapper*). |
| <span style="background-color:#F3F4F6; border:1px solid #E5E7EB; color:#111827; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#F3F4F6</span> | **Light Grey** | `#F3F4F6` | Latar belakang header tabel, *divider* (garis pembatas), dan kolom pencarian saat tidak aktif. |

### B. Warna Sidebar & Elemen Kontras (Dark Colors)

| Sampel Visual | Nama Warna | Kode HEX | Peruntukan / Penggunaan |
| :--- | :--- | :--- | :--- |
| <span style="background-color:#0B0F19; color:#FFFFFF; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#0B0F19</span> | **Deep Navy Blue** | `#0B0F19` | Latar belakang navigasi *sidebar* utama. |
| <span style="background-color:#1E293B; color:#FFFFFF; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#1E293B</span> | **Slate Blue Dark** | `#1E293B` | Elemen penunjang *sidebar* dan beberapa tombol sekunder gelap. |

### C. Warna Aksen & Identitas Brand (Accent Colors)

| Sampel Visual | Nama Warna | Kode HEX | Peruntukan / Penggunaan |
| :--- | :--- | :--- | :--- |
| <span style="background-color:#C5A358; color:#FFFFFF; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#C5A358</span> | **Premium Gold** | `#C5A358` | Aksen warna brand J&J Group, ikon logo, teks aktif, dan *highlight boundary*. |
| <span style="background-color:#AF904E; color:#FFFFFF; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#AF904E</span> | **Gold Hover** | `#AF904E` | Kondisi *hover* pada tombol emas atau elemen beraksen emas. |
| <span style="background-color:#FDF8EC; color:#C5A358; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#FDF8EC</span> | **Soft Gold Tint** | `#FDF8EC` | Background *badge highlight* emas atau navigasi aktif bernuansa cerah. |

### D. Warna Status / Transaksi (Status Badges)

| Sampel Visual | Nama Warna | Kode HEX (BG) | Kode HEX (Teks) | Contoh Status |
| :--- | :--- | :--- | :--- | :--- |
| <span style="background-color:#D1FAE5; color:#065F46; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">LUNAS</span> | **Mint Green Pastel** | `#D1FAE5` | `#065F46` | Status Invoice "LUNAS" (Paid) |
| <span style="background-color:#FEF3C7; color:#92400E; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">PENDING</span> | **Amber Yellow Pastel** | `#FEF3C7` | `#92400E` | Status Invoice "BELUM LUNAS" (Unpaid) / Pending |
| <span style="background-color:#FEE2E2; color:#991B1B; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">BATAL</span> | **Rose Red Pastel** | `#FEE2E2` | `#991B1B` | Status Invoice "DIBATALKAN" (Cancelled) |
| <span style="background-color:#F3F4F6; color:#374151; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">DRAFT</span> | **Cool Grey Pastel** | `#F3F4F6` | `#374151` | Status Invoice "DRAFT" |

### E. Warna Teks Utama (Text Colors)

| Sampel Visual | Nama Warna | Kode HEX | Peruntukan / Penggunaan |
| :--- | :--- | :--- | :--- |
| <span style="background-color:#0F172A; color:#FFFFFF; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#0F172A</span> | **Jet Black** | `#0F172A` | Judul utama (H1/H2), nama klien, dan angka nominal keuangan. |
| <span style="background-color:#4B5563; color:#FFFFFF; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#4B5563</span> | **Dark Grey** | `#4B5563` | Deskripsi, teks paragraf tubuh (*body text*), dan label input. |
| <span style="background-color:#9CA3AF; color:#FFFFFF; padding:4px 12px; border-radius:4px; font-weight:bold; font-family:monospace;">#9CA3AF</span> | **Muted Grey** | `#9CA3AF` | Sub-label kecil, ikon non-aktif, *placeholder* input, dan tanggal transaksi. |

---

## 3. Panduan Tipografi (Typography Guidelines)

Tipografi harus sangat menekankan kejelasan pembacaan, terutama untuk data numerik keuangan. Keluarga font yang direkomendasikan adalah **Inter** atau **Poppins** (Sans-Serif geometris).

### A. Skala dan Hierarki Font

| Elemen UI | Ukuran Font (px / rem) | Font Weight | Line Height | Contoh Penggunaan |
| :--- | :--- | :--- | :--- | :--- |
| **Heading 1 (H1)** | `24px / 1.5rem` | Bold (`700`) | `1.3` | Judul halaman utama (*Dashboard*, *Invoices*). |
| **Heading 2 (H2)** | `20px / 1.25rem` | Semibold (`600`) | `1.4` | Judul bagian / sub-menu modul besar. |
| **Heading 3 (H3)** | `16px / 1rem` | Semibold (`600`) | `1.4` | Judul modal dialog atau judul kartu (*card title*). |
| **Subtitle** | `14px / 0.875rem` | Regular (`400`) | `1.5` | Penjelasan sekunder di bawah judul halaman. |
| **Body Text** | `14px / 0.875rem` | Regular (`400`) | `1.5` | Detail transaksi, item tabel, deskripsi. |
| **Nominal Uang** | `16px - 20px` | Bold / Black (`700 / 900`) | `1.2` | Teks angka Rupiah (IDR) pada total tagihan. |
| **Small Label** | `12px / 0.75rem` | Medium (`500`) | `1.4` | Header kolom tabel, label badge, tanggal. |

### B. Aturan Penulisan Keuangan
1.  **Format Rupiah (IDR):** Selalu gunakan pemisah ribuan titik (`.`) dan tanpa desimal sen jika bernilai bulat (contoh: `Rp 15.250.000`).
2.  **Ketebalan Angka:** Seluruh angka nominal pada total utama, subtotal, dan sisa tagihan **wajib** menggunakan ketebalan minimal `Semibold (600)` atau `Bold (700)`.

---

## 4. Panduan Ikonografi (Iconography)

*   **Gaya Ikon:** Ikon wajib menggunakan gaya **Outline** (garis tipis), dengan garis luar yang konsisten.
*   **Library Standar:** **Feather Icons** atau **Heroicons (Outline)**. Jangan mencampur-adukan gaya outline dengan gaya solid dalam satu halaman menu.
*   **Ketebalan Garis (Stroke Width):** Standar stroke width adalah `1.5px` atau `2px` untuk menjaga kerapian garis tipis.
*   **Ukuran Ikon:**
    *   Ikon Sidebar: `20px` x `20px` (w-5 h-5 dalam utility Tailwind).
    *   Ikon Tabel/Aksi: `16px` x `16px` (w-4 h-4).
    *   Ikon Header/Statistik: `24px` x `24px` (w-6 h-6).

---

## 5. Komponen Antarmuka Pengguna (UI Components)

### A. Tombol (Buttons)

Tombol dirancang menggunakan sudut melengkung sedang (`rounded-lg` atau `8px`) untuk menjaga bentuk tegas namun modern.

```
┌───────────────────────────────────────┐
│              Button Text              │  <- Primary Gold (Gold BG, White Text)
└───────────────────────────────────────┘
┌───────────────────────────────────────┐
│              Button Text              │  <- Secondary Outline (Gray Border, Jet Black Text)
└───────────────────────────────────────┘
```

1.  **Primary Button:**
    *   Background: `#C5A358` (Gold)
    *   Teks: `#FFFFFF` (Bold)
    *   Hover State: `#AF904E` (Gold Hover) dengan transisi halus (`transition-all duration-200`).
    *   Shadow: `shadow-sm` tipis.
2.  **Secondary Button:**
    *   Background: `#FFFFFF` atau transparan.
    *   Border: `#E5E7EB` (Gray 200).
    *   Teks: `#4B5563` (Dark Grey).
    *   Hover State: Background berubah ke `#F9FAFB` (Gray 50).
3.  **Destructive Button:**
    *   Background: `#FEE2E2` dengan teks merah `#991B1B` (untuk membatalkan/menghapus transaksi secara elegan tanpa warna merah menyala yang kasar).

### B. Kartu Informasi (Cards)

Kartu digunakan untuk membungkus ringkasan statistik (KPI), rincian tagihan, atau grafik data.

*   **Background:** `#FFFFFF` (Clean White)
*   **Border:** `1px` solid `#F3F4F6` (sangat tipis dan halus).
*   **Corner Radius:** `12px` (`rounded-xl` pada Tailwind).
*   **Shadow:** Bayangan melayang halus (Drop Shadow tipis):
    ```css
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.025);
    ```

### C. Sidebar Navigasi (Sidebar)

Komponen navigasi utama aplikasi J&J Group Invoice menggunakan tema kontras gelap.

*   **Background Panel:** `#0B0F19` (Deep Navy Blue).
*   **Elemen Logo:** Logo ditempatkan di paling atas menggunakan teks berbobot bold berwarna emas `#C5A358` atau logo bergaris emas.
*   **Navigasi Menu Utama:**
    *   **Kondisi Idle:** Teks berwarna `#9CA3AF` (Muted Grey) dengan ikon yang senada.
    *   **Kondisi Hover:** Latar belakang item menu berubah menjadi `#1E293B` dengan teks menjadi `#FFFFFF`.
    *   **Kondisi Aktif (Active/Selected):** Latar belakang item menu menjadi `#1E293B` (atau `#111827`), dengan aksen border tipis emas (`3px` atau `4px`) di sebelah kiri, ikon berubah warna menjadi emas `#C5A358`, dan teks menu menjadi putih `#FFFFFF`.

### D. Input Pencarian & Form (Inputs)

*   **Border Default:** `1px` solid `#E5E7EB` (Gray 200).
*   **Radius:** `8px` (`rounded-lg`).
*   **Placeholder:** `#9CA3AF` (Muted Grey).
*   **Focus State:** Garis batas berubah menjadi `#C5A358` (Gold) dengan bayangan fokus tipis (ring shadow) berwarna emas pastel:
    ```css
    outline: none;
    border-color: #C5A358;
    box-shadow: 0 0 0 3px rgba(197, 163, 88, 0.15);
    ```
*   **Ikon Kaca Pembesar:** Diletakkan di sebelah kiri kolom input dengan warna `#9CA3AF`.

### E. Tabel Data (Data Tables)

Tabel dirancang untuk keterbacaan baris demi baris transaksi.

*   **Header Tabel:** Background `#F9FAFB` (atau `#F3F4F6`), teks abu-abu medium (`#6B7280`), ukuran font `12px` (Small Label), bertipe `uppercase` dengan *letter spacing* yang sedikit longgar.
*   **Row Tabel:** Background dasar `#FFFFFF`. Setiap pergantian baris memiliki border tipis `#F3F4F6` di bagian bawah.
*   **Hover Row:** Baris tabel berganti latar belakang menjadi `#FAFBFC` secara halus saat kursor melintas di atasnya.
*   **Padding Baris:** `16px` atas-bawah dan `24px` kiri-kanan (`py-4 px-6`) agar tabel terkesan lapang dan tidak menumpuk.

---

## 6. Tata Letak & Sistem Grid (Layout & Grid System)

Sistem tata letak mengikuti standar responsive grid modern yang diatur untuk kenyamanan layar desktop minimal `1280px` (layar kerja enterprise umum).

### A. Pembagian Area Layar (Layout Division)

*   **Sidebar Width:** Lebar tetap `260px` pada layar desktop.
*   **Main Content Wrapper:** Area konten utama bersifat fluid mengisi sisa layar dengan padding horizontal `32px` (`px-8`) dan padding vertikal `24px` (`py-6`).
*   **Max-Width Wrapper:** Area kerja utama disarankan dibatasi maksimal `max-w-7xl` (`1280px`) agar data tidak terlalu merenggang pada monitor ultra-wide.

### B. Grid Spacing (Skala Spacing)

Sistem spacing menggunakan kelipatan `4px` / `8px` untuk konsistensi jarak antar elemen (padding, margin, gap).

*   `4px` (Tailwind `1` / `0.25rem`) : Jarak teks detail kecil, badge padding vertical.
*   `8px` (Tailwind `2` / `0.5rem`) : Badge padding horizontal, jarak kecil antar input label dan field.
*   `12px` (Tailwind `3` / `0.75rem`) : Gap tombol dalam satu baris, border-radius kartu.
*   `16px` (Tailwind `4` / `1rem`) : Jarak antar kartu kecil, padding internal komponen standar.
*   `24px` (Tailwind `6` / `1.5rem`) : Jarak antar baris grid kartu utama, padding halaman.
*   `32px` (Tailwind `8` / `2rem`) : Jarak antar section besar.

### C. Responsivitas (Responsive Breakpoints)

Meskipun aplikasi didesain khusus untuk penggunaan desktop kantor (enterprise dashboard), aspek responsif tetap dijaga dengan ketentuan:

1.  **Mobile (< 768px):** Sidebar otomatis tersembunyi (*drawer*) dan dipicu dengan tombol menu hamburger di pojok kiri atas. Grid kartu KPI berubah menjadi tumpukan vertical (1 kolom).
2.  **Tablet (768px - 1024px):** Grid KPI statistik ditampilkan dalam 2 kolom. Sidebar dapat diringkas menjadi versi ikon saja (*collapsed sidebar*).
3.  **Desktop (> 1024px):** Layout penuh 3 kolom statistik, tabel data lebar penuh, dan sidebar tampil penuh secara permanen.

---

## 7. Rekomendasi Implementasi Tailwind CSS

Untuk menyelaraskan design system ini ke dalam file konfigurasi Tailwind CSS (`tailwind.config.js` atau `tailwind.config.cjs`), tim developer dapat menyalin potongan kode berikut:

```javascript
/** @type {import('tailwindcss').Config} */
module.exports = {
  theme: {
    extend: {
      colors: {
        // Skema Warna Utama J&J Group
        brand: {
          gold: {
            DEFAULT: '#C5A358',
            hover: '#AF904E',
            light: '#FDF8EC',
          },
          navy: {
            DEFAULT: '#0B0F19',
            light: '#1E293B',
          }
        },
        // Skema Status Keuangan
        status: {
          lunas: {
            bg: '#D1FAE5',
            text: '#065F46',
          },
          pending: {
            bg: '#FEF3C7',
            text: '#92400E',
          },
          batal: {
            bg: '#FEE2E2',
            text: '#991B1B',
          },
          draft: {
            bg: '#F3F4F6',
            text: '#374151',
          }
        },
        // Skema Teks & Netral
        neutral: {
          jet: '#0F172A',
          charcoal: '#374151',
          slateBg: '#F8FAFC',
        }
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system'],
      },
      boxShadow: {
        'premium': '0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.025)',
      },
      borderRadius: {
        'lg': '8px',
        'xl': '12px',
      }
    },
  },
  plugins: [],
}
```

---
*Dokumen ini dirancang sebagai panduan tunggal kebenaran visual (Single Source of Truth) untuk tim perancang antarmuka dan pengembang aplikasi J&J Group Invoice.*
