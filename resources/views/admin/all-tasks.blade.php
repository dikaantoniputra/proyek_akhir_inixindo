@extends('layouts.app')

@section('title', 'Semua Tugas Pegawai')
@section('page_title', 'Semua Tugas Pegawai')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Semua Tugas Pegawai</li>
@endsection

@section('content')

<div class="row">
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-3 fs-20">
                            <i class="ri-folder-shared-line"></i>
                        </span>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase mt-0" title="Total Tugas Seluruh Pegawai">Total Tugas</h6>
                <h2 class="my-2 text-dark">{{ $stats['total'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-primary me-1"><i class="ri-team-line"></i> {{ $stats['users_count'] }} Pegawai</span>
                    <span class="text-nowrap">terdaftar</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-info-subtle text-info rounded-3 fs-20">
                            <i class="ri-loader-2-line"></i>
                        </span>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase mt-0" title="Tugas Sedang Dikerjakan">Sedang Berjalan</h6>
                <h2 class="my-2 text-info">{{ $stats['in_progress'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-info me-1"><i class="ri-time-line"></i> {{ $stats['pending'] }} Belum Mulai</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-success-subtle text-success rounded-3 fs-20">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase mt-0" title="Tugas Selesai">Tuntas Selesai</h6>
                <h2 class="my-2 text-success">{{ $stats['completed'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="badge bg-success-subtle text-success">{{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%</span>
                    <span class="text-nowrap ms-1">tingkat penyelesaian</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                            <i class="ri-alarm-warning-line"></i>
                        </span>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase mt-0" title="Tugas Lewat Tenggat">Melewati Tenggat</h6>
                <h2 class="my-2 text-danger">{{ $stats['overdue'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-danger me-1"><i class="ri-error-warning-line"></i> Perlu perhatian</span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h4 class="header-title mb-1">Daftar Semua Tugas Pegawai</h4>
                        <p class="text-muted fs-14 mb-0">Pantau seluruh beban kerja, progres, dan status tugas dari seluruh pegawai.</p>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0 d-flex justify-content-sm-end gap-2">
                        <a href="{{ route('admin.tasks.export', request()->query()) }}" class="btn btn-outline-success btn-sm" title="Unduh semua data tugas ke file CSV/Excel">
                            <i class="ri-file-excel-line me-1"></i> Ekspor CSV
                        </a>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-circle-line me-1"></i> Tambah Tugas Baru
                        </a>
                    </div>
                </div>

                
                <form action="{{ route('admin.tasks') }}" method="GET" class="p-3 bg-light rounded mb-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul/isi tugas..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">-- Semua Pegawai --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="category_id" class="form-select form-select-sm">
                                <option value="">-- Semua Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">-- Semua Status --</option>
                                <option value="belum dimulai" {{ request('status') == 'belum dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                                <option value="dikerjakan" {{ request('status') == 'dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="priority" class="form-select form-select-sm">
                                <option value="">-- Semua Prioritas --</option>
                                <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="sedang" {{ request('priority') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="rendah" {{ request('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            </select>
                        </div>

                        <div class="col-lg-1 col-12 d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill" title="Terapkan Filter">
                                <i class="ri-filter-3-line"></i>
                            </button>
                            @if(request()->hasAny(['search', 'user_id', 'status', 'priority', 'category_id']))
                                <a href="{{ route('admin.tasks') }}" class="btn btn-secondary btn-sm" title="Reset Filter">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                
                <div class="table-responsive">
                    <table class="table table-striped table-centered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Pegawai</th>
                                <th>Judul Tugas</th>
                                <th>Kategori</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Tenggat Waktu</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $index => $task)
                                <tr>
                                    <td class="text-muted fw-semibold">{{ $tasks->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2 flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold fs-12">
                                                    {{ $task->user->initials ?? 'U' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark fs-13 d-block">{{ $task->user->name ?? 'User Terhapus' }}</span>
                                                <small class="text-muted fs-11">{{ $task->user->email ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('tasks.show', $task) }}" class="text-body fw-semibold text-truncate" style="max-width: 250px;">
                                                    {{ $task->title }}
                                                </a>
                                                @if($task->has_attachment)
                                                    <span class="badge bg-light text-primary border fs-10 px-1 py-0" title="Lampiran: {{ $task->attachment_name }} ({{ $task->attachment_size_formatted }})">
                                                        <i class="ri-attachment-2"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            @if($task->description)
                                                <small class="text-muted text-truncate" style="max-width: 250px;">{{ $task->description }}</small>
                                            @endif
                                            @if($task->total_checklists_count > 0)
                                                <div class="d-flex align-items-center gap-2 mt-1" style="max-width: 180px;">
                                                    <div class="progress flex-grow-1" style="height: 4px;">
                                                        <div class="progress-bar {{ $task->checklist_progress_percentage == 100 ? 'bg-success' : 'bg-primary' }}" 
                                                             style="width: {{ $task->checklist_progress_percentage }}%;"></div>
                                                    </div>
                                                    <small class="text-muted fs-10 fw-semibold">{{ $task->completed_checklists_count }}/{{ $task->total_checklists_count }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($task->category)
                                            <span class="badge bg-{{ $task->category->color }}-subtle text-{{ $task->category->color }}">
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
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle {{ $task->status_badge_class }} py-0 px-2 fs-12" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ ucfirst($task->status) }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-animated shadow-sm py-1">
                                                <li>
                                                    <form action="{{ route('tasks.status', $task) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="belum dimulai">
                                                        <button type="submit" class="dropdown-item py-1 fs-12 {{ $task->status == 'belum dimulai' ? 'active' : '' }}">
                                                            <i class="ri-time-line me-1 text-secondary"></i> Belum Dimulai
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('tasks.status', $task) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="dikerjakan">
                                                        <button type="submit" class="dropdown-item py-1 fs-12 {{ $task->status == 'dikerjakan' ? 'active' : '' }}">
                                                            <i class="ri-loader-2-line me-1 text-primary"></i> Sedang Dikerjakan
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('tasks.status', $task) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" class="dropdown-item py-1 fs-12 {{ $task->status == 'selesai' ? 'active' : '' }}">
                                                            <i class="ri-checkbox-circle-line me-1 text-success"></i> Selesai
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            <div class="d-flex flex-column gap-1">
                                                <span class="fs-12 fw-semibold {{ $task->is_overdue ? 'text-danger' : 'text-body' }}">
                                                    <i class="ri-calendar-event-line me-1 text-muted"></i>{{ $task->due_date->translatedFormat('d M Y') }}
                                                </span>
                                                <span class="{{ $task->due_date_badge }}" style="width: fit-content;">
                                                    {{ $task->due_date_label }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info" title="Lihat Detail">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-warning" title="Ubah Data">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#adminDeleteModal{{ $task->id }}" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>

                                        
                                        <div class="modal fade text-start" id="adminDeleteModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content text-center p-4">
                                                    <i class="ri-alert-line text-danger fs-48 d-block mb-2"></i>
                                                    <h5 class="modal-title mb-2">Hapus Tugas Ini?</h5>
                                                    <p class="text-muted fs-13 mb-3">Tugas <strong>"{{ $task->title }}"</strong> milik <strong>{{ $task->user->name ?? 'User' }}</strong> akan dihapus permanen.</p>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ri-inbox-line fs-24 d-block mb-1 text-secondary"></i>
                                        Tidak ada data tugas yang sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                
                @if($tasks->hasPages())
                    <div class="pt-3 d-flex justify-content-end">
                        {{ $tasks->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
