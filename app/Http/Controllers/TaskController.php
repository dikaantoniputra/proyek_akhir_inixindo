<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Tampilkan daftar seluruh tugas milik pengguna dengan pencarian dan filter.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Task::where('user_id', Auth::id())
            ->with('category');

        // Filter status
        if ($request->filled('status')) {
            $query->filterStatus($request->status);
        }

        // Filter prioritas
        if ($request->filled('priority')) {
            $query->filterPriority($request->priority);
        }

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->filterCategory($request->category_id);
        }

        // Pencarian judul / deskripsi
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting
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

    /**
     * Tampilkan formulir tambah tugas baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('tasks.create', compact('categories'));
    }

    /**
     * Simpan tugas baru ke database.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        Task::create($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail informasi suatu tugas.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load('category', 'user');

        return view('tasks.show', compact('task'));
    }

    /**
     * Tampilkan formulir edit tugas.
     */
    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        $categories = Category::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Perbarui data tugas di database.
     */
    public function update(TaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Data tugas berhasil diperbarui.');
    }

    /**
     * Hapus data tugas dari database.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Ubah status tugas secara cepat.
     */
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
}
