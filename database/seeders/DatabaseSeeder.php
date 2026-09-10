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

    
    public function run(): void
    {
        
        $admin = User::create([
            'name' => 'Inixindo Administrator',
            'email' => 'inixindo@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $dika = User::create([
            'name' => 'Dika',
            'email' => 'dika@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $radnet = User::create([
            'name' => 'Radnet Operator',
            'email' => 'radnet@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        
        $catInixindo = Category::create([
            'name' => 'Pelatihan Inixindo',
            'slug' => 'pelatihan-inixindo',
            'color' => 'primary',
            'description' => 'Materi pelatihan pemrograman Laravel, penyusunan proyek akhir, ujian sertifikasi, dan evaluasi peserta di Inixindo.',
        ]);

        $catRadnet = Category::create([
            'name' => 'Infrastruktur Radnet',
            'slug' => 'infrastruktur-radnet',
            'color' => 'info',
            'description' => 'Manajemen jaringan ISP Radnet, monitoring bandwidth, routing BGP, dan pemeliharaan server cloud.',
        ]);

        $catDev = Category::create([
            'name' => 'Software Development',
            'slug' => 'software-development',
            'color' => 'success',
            'description' => 'Pengembangan fitur aplikasi TaskApp, pembuatan API, pengujian otomatis, dan integrasi UI Attex.',
        ]);

        $catUrgent = Category::create([
            'name' => 'Insiden & Maintenance',
            'slug' => 'insiden-maintenance',
            'color' => 'danger',
            'description' => 'Penanganan gangguan jaringan mendesak, perbaikan bug darurat, dan patching keamanan server.',
        ]);

        $catOps = Category::create([
            'name' => 'Operasional & Bisnis',
            'slug' => 'operasional-bisnis',
            'color' => 'warning',
            'description' => 'Laporan kinerja harian, koordinasi tim teknis, administrasi SOP, dan layanan pelanggan.',
        ]);

        
        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catInixindo->id,
            'title' => 'Menyelesaikan Proyek Akhir Pelatihan Web Laravel Inixindo',
            'description' => 'Menuntaskan aplikasi manajemen tugas TaskApp dengan fitur lengkap (Auth, CRUD, Kategori, Sub-Tugas, Kanban Board, dan Lampiran Berkas) sesuai standar kurikulum Inixindo.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(3),
        ]);

        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catDev->id,
            'title' => 'Integrasi Template Admin Attex Bootstrap 5 ke Blade Layout',
            'description' => 'Menata layout antarmuka dashboard, sidebar menu, formulir input modern, dan komponen kartu tugas agar profesional dan responsif di berbagai perangkat.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(2),
        ]);

        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catDev->id,
            'title' => 'Implementasi Fitur Papan Kanban & Drag-and-Drop Interaktif',
            'description' => 'Membangun visualisasi kartu tugas berbasis status (Belum Mulai, Sedang Dikerjakan, Selesai) lengkap dengan perpindahan status real-time via AJAX.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(1),
        ]);

        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catInixindo->id,
            'title' => 'Penyusunan Dokumentasi Teknis & Panduan Penggunaan Aplikasi',
            'description' => 'Membuat dokumen README komprehensif, daftar akun demo, panduan instalasi lokal, serta arsitektur database untuk diserahkan ke instruktur Inixindo.',
            'priority' => 'sedang',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(2),
        ]);

        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catRadnet->id,
            'title' => 'Optimasi API Gateway & Koneksi Database Server Radnet',
            'description' => 'Melakukan tuning query Eloquent, indexing database SQLite/MySQL, dan caching respon untuk mempercepat response time sistem.',
            'priority' => 'sedang',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(3),
        ]);

        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catUrgent->id,
            'title' => 'Pembaruan Sertifikat SSL Wildcard Server TaskApp Radnet',
            'description' => 'Memperbarui sertifikat HTTPS Let\'s Encrypt pada domain staging dan mengonfigurasi auto-renewal script.',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->subDays(2), 
        ]);

        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catInixindo->id,
            'title' => 'Persiapan Presentasi & Demo Proyek Akhir di Hadapan Penguji',
            'description' => 'Menyiapkan slide presentasi materi, skenario demo fitur utama, pengujian login role admin/user, dan rekaman video aplikasi.',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->addDays(5),
        ]);

        Task::create([
            'user_id' => $dika->id,
            'category_id' => $catOps->id,
            'title' => 'Evaluasi Feedback Pelatihan & Pengisian Kuesioner Inixindo',
            'description' => 'Mengisi form evaluasi pengajar, materi training Laravel, dan fasilitas laboratorium pelatihan Inixindo.',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->addDays(7),
        ]);

        
        Task::create([
            'user_id' => $radnet->id,
            'category_id' => $catRadnet->id,
            'title' => 'Monitoring Trafik Jaringan Fiber Optic & Bandwidth Gateway',
            'description' => 'Memeriksa utilisasi link backbone utama Radnet, memantau grafik MRTG/Cacti, dan memastikan stabilitas latency di bawah 15ms.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(4),
        ]);

        Task::create([
            'user_id' => $radnet->id,
            'category_id' => $catRadnet->id,
            'title' => 'Audit Keamanan Firewall & Konfigurasi BGP Router Radnet',
            'description' => 'Melakukan review access list firewall, filtering port berbahaya, dan pembaruan prefix list router border ISP Radnet.',
            'priority' => 'tinggi',
            'status' => 'selesai',
            'due_date' => Carbon::today()->subDays(2),
        ]);

        Task::create([
            'user_id' => $radnet->id,
            'category_id' => $catUrgent->id,
            'title' => 'Penanganan Tiket Insiden Gangguan Koneksi Pelanggan Korporat',
            'description' => 'Melakukan troubleshooting gangguan koneksi link FO pelanggan segmen korporasi dan melakukan koordinasi lapangan.',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(1),
        ]);

        Task::create([
            'user_id' => $radnet->id,
            'category_id' => $catRadnet->id,
            'title' => 'Setup Backup Otomatis Database & File Storage ke Disaster Recovery Center',
            'description' => 'Mengonfigurasi cron backup harian database MySQL dan sync file lampiran ke server backup off-site Radnet.',
            'priority' => 'sedang',
            'status' => 'dikerjakan',
            'due_date' => Carbon::today()->addDays(3),
        ]);

        Task::create([
            'user_id' => $radnet->id,
            'category_id' => $catUrgent->id,
            'title' => 'Investigasi Lonjakan Latensi DNS Resolver Publik Radnet',
            'description' => 'Menganalisis anomali query spike pada DNS recursive server dan menerapkan rate limiting mitigasi DNS flood.',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->subDays(1), 
        ]);

        Task::create([
            'user_id' => $radnet->id,
            'category_id' => $catOps->id,
            'title' => 'Penyusunan Rekapitulasi Laporan SLA & Ketersediaan Layanan Bulanan',
            'description' => 'Menghitung persentase uptime jaringan seluruh pelanggan (target 99.8%) dan mendokumentasikan ringkasan insiden bulanan.',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'due_date' => Carbon::today()->addDays(6),
        ]);

        
        $allTasks = Task::all();
        foreach ($allTasks as $idx => $task) {
            
            $task->checklists()->createMany([
                ['title' => 'Tinjau kebutuhan spesifikasi & dokumen teknis', 'is_completed' => true],
                ['title' => 'Implementasi fitur dan eksekusi teknis tahap inti', 'is_completed' => ($task->status !== 'belum dimulai')],
                ['title' => 'Pengujian fungsionalitas, verifikasi & review hasil', 'is_completed' => ($task->status === 'selesai')],
            ]);

            
            $task->comments()->create([
                'user_id' => $task->user_id,
                'comment' => 'Memulai inisiasi pengerjaan tugas sesuai target rencana kerja.',
                'created_at' => Carbon::now()->subDays(2),
            ]);

            if ($task->status === 'selesai') {
                $task->comments()->create([
                    'user_id' => $task->user_id,
                    'comment' => 'Pekerjaan telah rampung dengan baik dan seluruh kriteria pengujian berhasil dilewati.',
                    'created_at' => Carbon::now()->subHours(5),
                ]);
            } elseif ($task->status === 'dikerjakan') {
                $task->comments()->create([
                    'user_id' => $task->user_id,
                    'comment' => 'Sedang dalam proses pengerjaan bagian inti. Estimasi selesai tepat waktu.',
                    'created_at' => Carbon::now()->subHours(2),
                ]);
            }
        }
    }
}
