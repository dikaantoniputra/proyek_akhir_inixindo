<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Tampilkan seluruh tugas dari seluruh pengguna (Fitur Tambahan Admin).
     */
    public function allTasks(Request $request)
    {
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $query = Task::with(['user', 'category']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
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

        $tasks = $query->latest()->paginate(15)->withQueryString();

        return view('admin.all-tasks', compact('tasks', 'users', 'categories'));
    }

    /**
     * Tampilkan rekap jumlah tugas per pengguna.
     */
    public function usersSummary()
    {
        $users = User::withCount([
            'tasks',
            'tasks as pending_tasks_count' => fn($q) => $q->where('status', 'belum dimulai'),
            'tasks as in_progress_tasks_count' => fn($q) => $q->where('status', 'dikerjakan'),
            'tasks as completed_tasks_count' => fn($q) => $q->where('status', 'selesai'),
        ])->get();

        return view('admin.users-summary', compact('users'));
    }
}
