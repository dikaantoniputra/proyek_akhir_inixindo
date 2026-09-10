<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Task::where('user_id', Auth::id())
            ->with('category');

        
        if ($request->filled('status')) {
            $query->filterStatus($request->status);
        }

        
        if ($request->filled('priority')) {
            $query->filterPriority($request->priority);
        }

        
        if ($request->filled('category_id')) {
            $query->filterCategory($request->category_id);
        }

        
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'due_date_asc' => $query->orderByRaw('due_date IS NULL, due_date ASC'),
            'due_date_desc' => $query->orderByRaw('due_date IS NULL, due_date DESC'),
            'priority_high' => $query->orderByRaw("CASE priority WHEN 'tinggi' THEN 1 WHEN 'sedang' THEN 2 WHEN 'rendah' THEN 3 ELSE 4 END"),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $tasks = $query->paginate(10)->withQueryString();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('tasks.create', compact('categories'));
    }

    
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');
            $data['attachment_path'] = $path;
            $data['attachment_name'] = $file->getClientOriginalName();
            $data['attachment_size'] = $file->getSize();
        }

        Task::create($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas baru berhasil ditambahkan.');
    }

    
    public function kanban(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Task::where('user_id', Auth::id())
            ->with(['category', 'checklists']);

        if ($request->filled('category_id')) {
            $query->filterCategory($request->category_id);
        }

        if ($request->filled('priority')) {
            $query->filterPriority($request->priority);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $allTasks = $query->orderBy('due_date', 'asc')->get();

        $todoTasks = $allTasks->where('status', 'belum dimulai');
        $inProgressTasks = $allTasks->where('status', 'dikerjakan');
        $doneTasks = $allTasks->where('status', 'selesai');

        return view('tasks.kanban', compact('todoTasks', 'inProgressTasks', 'doneTasks', 'categories'));
    }

    
    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load(['category', 'user', 'checklists', 'comments.user']);

        return view('tasks.show', compact('task'));
    }

    
    public function storeChecklist(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ], [
            'title.required' => 'Judul sub-tugas wajib diisi.',
            'title.max' => 'Judul sub-tugas maksimal 255 karakter.',
        ]);

        $task->checklists()->create([
            'title' => $validated['title'],
            'is_completed' => false,
        ]);

        return back()->with('success', 'Sub-tugas berhasil ditambahkan.');
    }

    
    public function toggleChecklist(\App\Models\TaskChecklist $checklist)
    {
        Gate::authorize('update', $checklist->task);

        $checklist->update([
            'is_completed' => !$checklist->is_completed,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $checklist->is_completed,
                'progress' => $checklist->task->fresh()->checklist_progress_percentage,
                'message' => 'Status sub-tugas berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Status sub-tugas berhasil diperbarui.');
    }

    
    public function destroyChecklist(\App\Models\TaskChecklist $checklist)
    {
        Gate::authorize('update', $checklist->task);

        $checklist->delete();

        return back()->with('success', 'Sub-tugas berhasil dihapus.');
    }

    
    public function storeComment(Request $request, Task $task)
    {
        Gate::authorize('view', $task);

        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ], [
            'comment.required' => 'Catatan progres tidak boleh kosong.',
            'comment.max' => 'Catatan progres maksimal 2000 karakter.',
        ]);

        $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Catatan progres berhasil diposting.');
    }

    
    public function destroyComment(\App\Models\TaskComment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin menghapus komentar ini.');
        }

        $comment->delete();

        return back()->with('success', 'Catatan progres berhasil dihapus.');
    }

    
    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        $categories = Category::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'categories'));
    }

    
    public function update(TaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $data = $request->validated();

        if ($request->boolean('remove_attachment')) {
            if ($task->attachment_path && Storage::disk('public')->exists($task->attachment_path)) {
                Storage::disk('public')->delete($task->attachment_path);
            }
            $data['attachment_path'] = null;
            $data['attachment_name'] = null;
            $data['attachment_size'] = null;
        }

        if ($request->hasFile('attachment')) {
            if ($task->attachment_path && Storage::disk('public')->exists($task->attachment_path)) {
                Storage::disk('public')->delete($task->attachment_path);
            }
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');
            $data['attachment_path'] = $path;
            $data['attachment_name'] = $file->getClientOriginalName();
            $data['attachment_size'] = $file->getSize();
        }

        $task->update($data);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Data tugas berhasil diperbarui.');
    }

    
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        if ($task->attachment_path && Storage::disk('public')->exists($task->attachment_path)) {
            Storage::disk('public')->delete($task->attachment_path);
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    
    public function updateStatus(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:belum dimulai,dikerjakan,selesai',
        ], [
            'status.required' => 'Status tugas wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', 'Status tugas berhasil diperbarui menjadi "' . $validated['status'] . '".');
    }

    
    public function export(Request $request)
    {
        $query = Task::where('user_id', Auth::id())
            ->with('category');

        if ($request->filled('status')) {
            $query->filterStatus($request->status);
        }
        if ($request->filled('priority')) {
            $query->filterPriority($request->priority);
        }
        if ($request->filled('category_id')) {
            $query->filterCategory($request->category_id);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'due_date_asc' => $query->orderByRaw('due_date IS NULL, due_date ASC'),
            'due_date_desc' => $query->orderByRaw('due_date IS NULL, due_date DESC'),
            'priority_high' => $query->orderByRaw("CASE priority WHEN 'tinggi' THEN 1 WHEN 'sedang' THEN 2 WHEN 'rendah' THEN 3 ELSE 4 END"),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $tasks = $query->get();
        $filename = 'daftar-tugas-' . date('Y-m-d-His') . '.csv';

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
}
