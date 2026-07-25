# Fitur Sistem: AI Voice Commands (Perintah Suara CFO)

Fitur AI Voice Commands adalah asisten kontrol berbasis suara (voice-activated control) di dalam ekosistem **J&J Group Invoice**. Fitur ini memungkinkan Owner dan Admin untuk bernavigasi dan menanyakan data keuangan penting secara cepat tanpa perlu mengetik, layaknya berinteraksi dengan Chief Financial Officer (CFO) digital.

---

## 🔗 Route & Kelas Utama
- **Route Endpoint**: `ai-assistant/voice-command` (POST)
- **Controller**: `App\Http\Controllers\AiChatController@handleVoiceCommand`
- **Integrasi LLM**: Google Gemini API (`gemini-2.5-flash`)
- **Hak Akses**: `owner` dan `admin`. Peran `staff` diblokir dari akses fitur ini.

---

## ⚙️ Logika Pengenalan & Alur Kerja (Intent Router)

Ketika teks perintah suara dikirimkan dari frontend, controller akan menganalisis teks menggunakan pencocokan kata kunci (regex/string-matching) serta fallback berbasis AI. Berikut alur pemrosesannya:

### 1. Navigasi Cepat (Navigation Intents)
Sistem mendeteksi keinginan pengguna untuk membuka halaman tertentu dan mengembalikan respons redirect otomatis di browser frontend:
*   **Kalender / Chronos**: Mengarahkan ke halaman Kalender Jatuh Tempo (`/chronos`).
*   **Dashboard**: Mengarahkan ke halaman Dashboard Utama (`/dashboard`).
*   **Invoice**: Mengarahkan ke halaman Daftar Tagihan (`/invoices`).
*   **Klien**: Mengarahkan ke halaman Daftar Klien (`/clients`).
*   **Kwitansi / Kwitansi / Receipt**: Mengarahkan ke halaman Daftar Kwitansi (`/receipts`).
*   **Pengaturan / Settings**: Mengarahkan ke halaman Pengaturan Aplikasi (`/settings`).

### 2. Kueri Transaksi Tertentu (Specific Transaction Queries)
Sistem melakukan kueri basis data secara langsung untuk menjawab pertanyaan spesifik:
*   **Invoice Terbesar**:
    - *Kata kunci*: `invoice terbesar`, `tagihan terbesar`.
    - *Logika*: Mengambil 1 invoice teratas yang berstatus belum lunas (`sent`, `pending`, `dp`) dengan pengurutan nominal `total` terbesar.
    - *Respons*: Mengembalikan Nomor Invoice, Nama Klien (dan nama perusahaan jika ada), Nominal Tagihan (format Rupiah), serta tanggal Jatuh Tempo.
*   **Total Tunggakan Aktif**:
    - *Kata kunci*: `total tunggakan`, `tunggakan aktif`, `tunggakan minggu ini`, `jumlah tunggakan`.
    - *Logika*: Menghitung jumlah (count) dan total nominal (sum) seluruh invoice aktif yang berstatus belum lunas (`sent`, `pending`, `dp`).
    - *Respons*: Mengembalikan total nominal akumulasi tunggakan dan jumlah invoice yang menunggak.

### 3. Fallback Gemini AI (Generative Interpretation)
Jika perintah suara pengguna tidak cocok dengan aturan navigasi atau kueri database di atas:
*   Sistem mengirimkan prompt suara ke model **`gemini-2.5-flash`** dengan context instruksi khusus:
    - *"Kamu adalah sistem CFO Suara (Voice Command Processing) untuk J&J GROUP Invoice. Proses teks suara berikut dari user: '{perintah_suara}'. Berikan respons suara singkat, taktis, dan informatif (maksimal 2-3 kalimat) dalam Bahasa Indonesia."*
*   Model Gemini akan menghasilkan jawaban yang ringkas dan ramah suara (voice-friendly response).

### 4. Local Safe Fallback (Pencegah Error)
Jika koneksi internet atau API Gemini mengalami gangguan/timeout:
*   Sistem mengembalikan panduan penggunaan default:
    - *"Saya mendengar: '{perintah_suara}'. Maaf, perintah spesifik ini belum dikonfigurasi. Anda dapat mencoba perintah seperti 'Buka halaman kalender' atau 'Berapa total tunggakan aktif minggu ini?'."*
