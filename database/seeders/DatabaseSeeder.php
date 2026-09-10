<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with rich, realistic data.
     */
    public function run(): void
    {
        // 1. Akun Pengguna & Admin
        $admin = User::create([
            'name' => 'Administrator TaskApp',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $siti = User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Kategori Tugas
        $catDev = Category::create([
            'name' => 'Software Development',
            'slug' => 'software-development',
            'color' => 'primary',
            'description' => 'Aktivitas perancangan arsitektur sistem, pemrograman frontend & backend, serta integrasi API.',
        ]);

        $catDesign = Category::create([
            'name' => 'UI/UX & Branding',
            'slug' => 'ui-ux-branding',
            'color' => 'info',
            'description' => 'Perancangan wireframe, mockup aplikasi, ilustrasi visual, dan perbaikan antarmuka pengguna.',
        ]);

        $catOps = Category::create([
            'name' => 'Operasional & Bisnis',
            'slug' => 'operasional-bisnis',
            'color' => 'success',
            'description' => 'Laporan keuangan bulanan, koordinasi klien, evaluasi SOP, dan administrasi perkantoran.',
        ]);

        $catUrgent = Category::create([
            'name' => 'Insiden & Maintenance',
            'slug' => 'insiden-maintenance',
            'color' => 'danger',
            'description' => 'Penanganan bug kritis, patching keamanan server, dan perpanjangan infrastruktur cloud.',
        ]);

        $catMarketing = Category::create([
            'name' => 'Pemasaran Digital',
            'slug' => 'pemasaran-digital',
            'color' => 'warning',
            'description' => 'Kampanye media sosial, konten edukasi, SEO, dan analisis pertumbuhan pengguna.',
        ]);

        // 3. Tugas untuk Budi Santoso (Fullstack Developer & Team Lead)
        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catDev->id,
            'title' => 'Implementasi Authentication & Role-Based Policy',
            'description' => 'Membangun fitur login, registrasi, session guard, dan policy otorisasi ketat agar user hanya bisa mengelola tugas miliknya sendiri.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(4),
        ]);

        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catDev->id,
            'title' => 'Integrasi Template Admin Attex Bootstrap 5 ke Blade Views',
            'description' => 'Menata aset CSS, JavaScript plugins, ikon RemixIcon, dan membuat master layout modular untuk dashboard dan form tasks.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(2),
        ]);

        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catDev->id,
            'title' => 'Penyusunan Form Request Validation Bahasa Indonesia',
            'description' => 'Membuat validasi kustom untuk judul tugas, status enum, prioritas, dan tanggal deadline dengan pesan feedback yang ramah pengguna.',
            'priority' => 'sedang',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(1),
        ]);

        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catOps->id,
            'title' => 'Menyusun Laporan Evaluasi Kinerja Sprint & KPI Tim',
            'description' => 'Mengumpulkan data velocity sprint, issue resolution time, dan menyajikan ringkasan untuk bahan evaluasi mingguan manajemen.',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(2),
        ]);

        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catDev->id,
            'title' => 'Optimasi Query Database & Pagination Daftar Tugas',
            'description' => 'Menambahkan query scope untuk filter multi-kriteria (status, prioritas, kategori) dan indexing foreign key user_id.',
            'priority' => 'sedang',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(3),
        ]);

        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catUrgent->id,
            'title' => 'Perpanjangan Sertifikat SSL & Langganan Server Database',
            'description' => 'Melakukan pembayaran tagihan server staging cloud dan update wildcard SSL certificate sebelum expired.',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->subDays(2), // Overdue
        ]);

        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catDesign->id,
            'title' => 'Redesain Komponen Modal Konfirmasi Hapus Data',
            'description' => 'Mempercantik modal dialog hapus tugas menggunakan icon peringatan dan transisi fade halus khas tema Attex.',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->addDays(5),
        ]);

        Task::create([
            'user_id' => $budi->id,
            'category_id' => $catOps->id,
            'title' => 'Penyusunan Dokumen SOP Deployment Aplikasi Laravel',
            'description' => 'Menulis panduan step-by-step CI/CD pipeline, konfigurasi environment produksi, dan checklist rollback deployment.',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->addDays(8),
        ]);

        // 4. Tugas untuk Siti Rahmawati (Product Designer & QA Engineer)
        Task::create([
            'user_id' => $siti->id,
            'category_id' => $catDesign->id,
            'title' => 'Desain Wireframe & User Flow Aplikasi Manajemen Tugas',
            'description' => 'Menyusun wireframe resolusi tinggi untuk halaman login, ringkasan dashboard, tabel daftar tugas, dan form input.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(5),
        ]);

        Task::create([
            'user_id' => $siti->id,
            'category_id' => $catDev->id,
            'title' => 'Penulisan Unit & Feature Testing untuk Otentikasi & Policy',
            'description' => 'Menulis 21 test case otomatis untuk memvalidasi alur auth, CRUD tugas, dan isolasi data antar pengguna (403 Forbidden).',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(2),
        ]);

        Task::create([
            'user_id' => $siti->id,
            'category_id' => $catDesign->id,
            'title' => 'Audit Responsivitas Antarmuka pada Tampilan Mobile',
            'description' => 'Memeriksa layout navigation sidebar, topbar menu, widget metrik, dan tabel tugas agar proporsional di layar smartphone.',
            'priority' => 'sedang',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(1),
        ]);

        Task::create([
            'user_id' => $siti->id,
            'category_id' => $catMarketing->id,
            'title' => 'Pembuatan Video Screen Recording Demonstrasi Aplikasi',
            'description' => 'Merekam video walkthrough berdurasi maksimal 5 menit memperlihatkan login 2 user, CRUD tugas, filter, dan uji proteksi akses.',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(3),
        ]);

        Task::create([
            'user_id' => $siti->id,
            'category_id' => $catUrgent->id,
            'title' => 'Investigasi & Pengujian Keamanan Akses Endpoint Privat',
            'description' => 'Memastikan tidak ada celah Information Disclosure pada ID tugas pengguna lain ketika parameter URL dimanipulasi.',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->subDays(1), // Overdue
        ]);

        Task::create([
            'user_id' => $siti->id,
            'category_id' => $catOps->id,
            'title' => 'Penyusunan Panduan Pengguna (User Guide) & FAQ',
            'description' => 'Menulis artikel panduan cara membuat tugas, memfilter data, mengubah status cepat, dan mengelola kategori tugas.',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->addDays(7),
        ]);
    }
}
