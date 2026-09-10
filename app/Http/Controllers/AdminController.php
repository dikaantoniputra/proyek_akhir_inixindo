<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    public function allTasks(Request $request)
    {
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'belum dimulai')->count(),
            'in_progress' => Task::where('status', 'dikerjakan')->count(),
            'completed' => Task::where('status', 'selesai')->count(),
            'overdue' => Task::where('status', '!=', 'selesai')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', Carbon::today())
                ->count(),
            'users_count' => $users->count(),
        ];

        $query = Task::with(['user', 'category']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('category_id')) {
            $query->filterCategory($request->category_id);
        }

        if ($request->filled('status')) {
            $query->filterStatus($request->status);
        }

        if ($request->filled('priority')) {
            $query->filterPriority($request->priority);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'due_date_asc' => $query->orderByRaw('due_date IS NULL, due_date ASC'),
            'priority_high' => $query->orderByRaw("CASE priority WHEN 'tinggi' THEN 1 WHEN 'sedang' THEN 2 WHEN 'rendah' THEN 3 ELSE 4 END"),
            default => $query->latest(),
        };

        $tasks = $query->paginate(15)->withQueryString();

        return view('admin.all-tasks', compact('tasks', 'users', 'categories', 'stats'));
    }

    
    public function usersSummary()
    {
        $users = User::withCount([
            'tasks',
            'tasks as pending_tasks_count' => fn($q) => $q->where('status', 'belum dimulai'),
            'tasks as in_progress_tasks_count' => fn($q) => $q->where('status', 'dikerjakan'),
            'tasks as completed_tasks_count' => fn($q) => $q->where('status', 'selesai'),
            'tasks as overdue_tasks_count' => fn($q) => $q->where('status', '!=', 'selesai')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', Carbon::today()),
        ])->get();

        $totalTasks = Task::count();
        $totalUsers = $users->count();
        $avgTasksPerUser = $totalUsers > 0 ? round($totalTasks / $totalUsers, 1) : 0;
        $totalCompleted = Task::where('status', 'selesai')->count();
        $overallCompletionRate = $totalTasks > 0 ? round(($totalCompleted / $totalTasks) * 100) : 0;

        $summaryStats = [
            'total_users' => $totalUsers,
            'total_tasks' => $totalTasks,
            'avg_tasks' => $avgTasksPerUser,
            'completion_rate' => $overallCompletionRate,
        ];

        return view('admin.users-summary', compact('users', 'summaryStats'));
    }

    
    public function exportTasks(Request $request)
    {
        $query = Task::with(['user', 'category']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('category_id')) {
            $query->filterCategory($request->category_id);
        }
        if ($request->filled('status')) {
            $query->filterStatus($request->status);
        }
        if ($request->filled('priority')) {
            $query->filterPriority($request->priority);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'due_date_asc' => $query->orderByRaw('due_date IS NULL, due_date ASC'),
            'priority_high' => $query->orderByRaw("CASE priority WHEN 'tinggi' THEN 1 WHEN 'sedang' THEN 2 WHEN 'rendah' THEN 3 ELSE 4 END"),
            default => $query->latest(),
        };

        $tasks = $query->get();
        $filename = 'admin-semua-tugas-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($tasks) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID',
                'Nama Pegawai',
                'Email Pegawai',
                'Judul Tugas',
                'Deskripsi',
                'Kategori',
                'Prioritas',
                'Status',
                'Tenggat Waktu',
                'Keterangan Deadline',
                'Tanggal Dibuat',
            ]);

            foreach ($tasks as $task) {
                fputcsv($file, [
                    $task->id,
                    $task->user ? $task->user->name : '-',
                    $task->user ? $task->user->email : '-',
                    $task->title,
                    $task->description ?? '-',
                    $task->category ? $task->category->name : 'Tanpa Kategori',
                    ucfirst($task->priority),
                    ucfirst($task->status),
                    $task->due_date ? $task->due_date->format('d/m/Y') : '-',
                    $task->due_date_label ?? '-',
                    $task->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    
    public function exportUsersSummary()
    {
        $users = User::withCount([
            'tasks',
            'tasks as pending_tasks_count' => fn($q) => $q->where('status', 'belum dimulai'),
            'tasks as in_progress_tasks_count' => fn($q) => $q->where('status', 'dikerjakan'),
            'tasks as completed_tasks_count' => fn($q) => $q->where('status', 'selesai'),
            'tasks as overdue_tasks_count' => fn($q) => $q->where('status', '!=', 'selesai')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', Carbon::today()),
        ])->get();

        $filename = 'admin-rekap-penggunaan-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID User',
                'Nama Pegawai',
                'Email',
                'Role',
                'Total Tugas',
                'Belum Dimulai',
                'Sedang Dikerjakan',
                'Selesai',
                'Overdue (Terlewat)',
                'Tingkat Penyelesaian (%)',
            ]);

            foreach ($users as $user) {
                $pct = $user->tasks_count > 0 ? round(($user->completed_tasks_count / $user->tasks_count) * 100) : 0;
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->tasks_count,
                    $user->pending_tasks_count,
                    $user->in_progress_tasks_count,
                    $user->completed_tasks_count,
                    $user->overdue_tasks_count,
                    $pct . '%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
