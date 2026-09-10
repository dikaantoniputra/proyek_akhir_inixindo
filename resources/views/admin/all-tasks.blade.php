@extends('layouts.app')

@section('title', 'Semua Tugas (Admin)')
@section('page_title', 'Semua Tugas Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Admin - Semua Tugas</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-0">
            <div class="card-header border-bottom">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h4 class="header-title mb-0">Semua Tugas Pegawai</h4>
                        <p class="text-muted fs-13 mb-0">Pantau seluruh beban kerja dan status tugas semua pengguna.</p>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-12">
                        <i class="ri-shield-user-line me-1"></i> Mode Admin
                    </span>
                </div>

                <!-- Filter Controls -->
                <form action="{{ route('admin.tasks') }}" method="GET" class="p-2 bg-light rounded">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul/deskripsi tugas..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-6">
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">Semua Pegawai</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="belum dimulai" {{ request('status') == 'belum dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                                <option value="dikerjakan" {{ request('status') == 'dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4 col-6">
                            <select name="priority" class="form-select form-select-sm">
                                <option value="">Semua Prioritas</option>
                                <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="sedang" {{ request('priority') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="rendah" {{ request('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4 col-6 d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm w-100" title="Terapkan Filter">
                                <i class="ri-filter-3-line"></i> Filter
                            </button>
                            @if(request()->hasAny(['search', 'user_id', 'status', 'priority']))
                                <a href="{{ route('admin.tasks') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Pegawai</th>
                                <th>Judul Tugas</th>
                                <th>Kategori</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $index => $task)
                                <tr>
                                    <td class="text-muted fw-semibold">{{ $tasks->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold fs-11">
                                                    {{ $task->user->initials ?? 'U' }}
                                                </span>
                                            </div>
                                            <span class="fw-semibold text-body fs-13">{{ $task->user->name ?? 'User Terhapus' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-body fw-bold text-truncate" style="max-width: 250px;">
                                                {{ $task->title }}
                                            </a>
                                            @if($task->description)
                                                <small class="text-muted text-truncate" style="max-width: 250px;">{{ $task->description }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($task->category)
                                            <span class="badge bg-{{ $task->category->color }}-subtle text-{{ $task->category->color }} border border-{{ $task->category->color }}-subtle">
                                                {{ $task->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="{{ $task->priority_badge_class }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $task->status_badge_class }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            <span class="fs-12 {{ $task->is_overdue ? 'text-danger fw-bold' : 'text-body' }}">
                                                {{ $task->due_date->translatedFormat('d M Y') }}
                                            </span>
                                            @if($task->is_overdue)
                                                <span class="badge bg-danger-subtle text-danger d-block fs-10 mt-1" style="width: fit-content;">Terlewat</span>
                                            @endif
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-info p-1 px-2" title="Detail">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Tidak ada data tugas yang cocok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tasks->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $tasks->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
