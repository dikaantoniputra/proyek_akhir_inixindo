<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama.
     */
    public function index()
    {
        $user = Auth::user();

        // Query tugas milik user yang sedang login
        $taskQuery = Task::where('user_id', $user->id);

        $totalTasks = (clone $taskQuery)->count();
        $pendingTasks = (clone $taskQuery)->where('status', 'belum dimulai')->count();
        $inProgressTasks = (clone $taskQuery)->where('status', 'dikerjakan')->count();
        $completedTasks = (clone $taskQuery)->where('status', 'selesai')->count();
        
        // Tugas yang melewati tenggat (belum selesai dan tanggal < hari ini)
        $overdueTasks = (clone $taskQuery)->overdue()->count();

        // Daftar tugas yang mendekati tenggat (7 hari ke depan)
        $upcomingTasks = (clone $taskQuery)
            ->where('status', '!=', 'selesai')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', Carbon::today())
            ->whereDate('due_date', '<=', Carbon::today()->addDays(7))
            ->with('category')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // 5 Tugas terbaru
        $recentTasks = (clone $taskQuery)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Persentase penyelesaian
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks',
            'upcomingTasks',
            'recentTasks',
            'completionRate'
        ));
    }
}
