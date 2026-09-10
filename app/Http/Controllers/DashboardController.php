<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        
        $taskQuery = Task::where('user_id', $user->id);

        $totalTasks = (clone $taskQuery)->count();
        $pendingTasks = (clone $taskQuery)->where('status', 'belum dimulai')->count();
        $inProgressTasks = (clone $taskQuery)->where('status', 'dikerjakan')->count();
        $completedTasks = (clone $taskQuery)->where('status', 'selesai')->count();
        
        
        $overdueTasks = (clone $taskQuery)->overdue()->count();

        
        $upcomingTasks = (clone $taskQuery)
            ->where('status', '!=', 'selesai')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', Carbon::today())
            ->whereDate('due_date', '<=', Carbon::today()->addDays(7))
            ->with('category')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        
        $recentTasks = (clone $taskQuery)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        
        $priorityHighCount = (clone $taskQuery)->where('priority', 'tinggi')->count();
        $priorityMediumCount = (clone $taskQuery)->where('priority', 'sedang')->count();
        $priorityLowCount = (clone $taskQuery)->where('priority', 'rendah')->count();

        
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks',
            'upcomingTasks',
            'recentTasks',
            'completionRate',
            'priorityHighCount',
            'priorityMediumCount',
            'priorityLowCount'
        ));
    }
}
