# Proyek Akhir: Aplikasi Manajemen Tugas (TaskApp)

Aplikasi web manajemen tugas berbasis **Laravel 12** yang dibuat untuk memenuhi tugas Mini Project Pelatihan. Aplikasi ini berfungsi membantu pegawai dalam mencatat, mengelola, memantau tenggat waktu, serta memperbarui status pengerjaan tugas secara terstruktur dengan hak akses data terisolasi untuk masing-masing pengguna.

---

## Fitur Utama

### 1. Fitur Pengguna (User)
- **Autentikasi**: Registrasi akun baru, Login dengan session & remember me, dan Logout.
- **Profil Pengguna**: Ubah nama, email, dan kata sandi (*current password verification*).
- **Dashboard Ringkasan**:
  - Total tugas, tugas belum dimulai, sedang dikerjakan, dan selesai.
  - Peringatan tugas yang melewati tenggat waktu (*Overdue*).
  - Indikator persentase penyelesaian tugas (*progress bar*).
  - Daftar tugas dengan tenggat waktu terdekat (7 hari ke depan).
- **Manajemen Tugas (CRUD)**:
  - Melihat daftar tugas pribadi.
  - Menambah tugas baru (judul, deskripsi, prioritas, status, kategori, dan tenggat waktu).
  - Melihat detail informasi tugas.
  - Mengubah data tugas.
  - Menghapus tugas dengan modal konfirmasi.
  - Mengubah status tugas secara cepat (*quick status toggle*).
  - Label tenggat waktu cerdas (*Hari Ini*, *Besok*, *X hari lagi*, *Terlewat*).
- **Pencarian, Filter & Ekspor**:
  - Pencarian berdasarkan judul atau isi deskripsi tugas.
  - Filter berdasarkan status (`belum dimulai`, `dikerjakan`, `selesai`, `overdue`).
  - Filter berdasarkan prioritas (`rendah`, `sedang`, `tinggi`).
  - Filter berdasarkan kategori tugas.
  - Pengurutan data (terbaru, terlama, deadline terdekat, prioritas tertinggi).
  - **Ekspor CSV**: Unduh seluruh daftar tugas atau hasil filter ke dalam format file spreadsheet CSV/Excel.
- **Pembatasan Akses (Authorization)**:
  - Menggunakan `TaskPolicy` untuk memastikan pengguna hanya dapat melihat, mengedit, dan menghapus tugas miliknya sendiri. Akses URL ID pengguna lain otomatis diblokir (`403 Forbidden`).

### 2. Fitur Tambahan (Kategori & Admin)
- **Kategori Tugas**: Pengelolaan kategori dengan label warna badge.
- **Menu Administrator**:
  - Monitoring seluruh tugas pegawai dengan fitur filter, ubah status cepat, dan **Ekspor CSV Semua Tugas**.
  - Rekapitulasi beban kerja dan produktivitas tiap pengguna menggunakan DataTables interaktif dan **Ekspor CSV Rekapitulasi**.

---

## Struktur Database

- **users**: `id`, `name`, `email`, `password`, `role` (`admin`/`user`), `timestamps`
- **categories**: `id`, `name`, `slug`, `color`, `description`, `timestamps`
- **tasks**: `id`, `user_id` (FK), `category_id` (FK), `title`, `description`, `priority`, `status`, `due_date`, `timestamps`

---

## Cara Menjalankan Aplikasi

1. **Clone / Buka direktori proyek**:
   ```bash
   cd proyek_akhir
   ```

2. **Salin file konfigurasi environment**:
   ```bash
   cp .env.example .env
   ```

3. **Install dependensi**:
   ```bash
   composer install
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi database dan pengisian data awal (seeder)**:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan server lokal**:
   ```bash
   php artisan serve
   ```
   Buka browser dan akses URL: `http://127.0.0.1:8000`

---

## Akun Pengujian (Demo Accounts)

Data akun berikut telah disiapkan otomatis melalui database seeder:

| Nama Pengguna | Email | Password | Role / Keterangan |
| :--- | :--- | :--- | :--- |
| **Dika** | `dika@example.com` | `password` | User (Mengelola tugas pribadi & proyek akhir) |
| **Radnet Operator** | `radnet@example.com` | `password` | User (Mengelola tugas infrastruktur & jaringan ISP Radnet) |
| **Inixindo Administrator** | `inixindo@example.com` | `password` | Admin (Akses seluruh tugas pegawai & rekapitulasi pelatihan) |

*(Tersedia tombol 1-Click login demo pada halaman login untuk mempermudah demonstrasi pengujian)*.

---

## Pengujian Otomatis

Seluruh pengujian unit dan fitur dapat dijalankan menggunakan perintah:
```bash
php artisan test
```
