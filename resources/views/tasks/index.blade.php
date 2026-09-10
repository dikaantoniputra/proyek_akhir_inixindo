@extends('layouts.app')

@section('title', 'Daftar Tugas')
@section('page_title', 'Daftar Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Daftar Tugas</li>
@endsection

@section('content')
<!-- Metric Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-3 fs-20">
                            <i class="ri-folder-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase">Semua Tugas</h5>
                <h3 class="my-2 fw-bold text-dark">{{ $tasks->total() }}</h3>
                <small class="text-muted">Total data ditemukan</small>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-secondary-subtle text-secondary rounded-3 fs-20">
                            <i class="ri-time-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase">Belum Dimulai</h5>
                <h3 class="my-2 fw-bold text-secondary">{{ Auth::user()->tasks()->where('status', 'belum dimulai')->count() }}</h3>
                <small class="text-muted">Dalam antrean</small>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-info-subtle text-info rounded-3 fs-20">
                            <i class="ri-loader-2-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase">Sedang Dikerjakan</h5>
                <h3 class="my-2 fw-bold text-info">{{ Auth::user()->tasks()->where('status', 'dikerjakan')->count() }}</h3>
                <small class="text-muted">Sedang diproses</small>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-success-subtle text-success rounded-3 fs-20">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase">Selesai</h5>
                <h3 class="my-2 fw-bold text-success">{{ Auth::user()->tasks()->where('status', 'selesai')->count() }}</h3>
                <small class="text-muted">Tuntas dikerjakan</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-0">
            <!-- Card Header with Action & Filter Form -->
            <div class="card-header border-bottom">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h4 class="header-title mb-0">Data Tugas</h4>
                        <p class="text-muted fs-13 mb-0">Kelola dan pantau seluruh tugas pekerjaan Anda.</p>
                    </div>
                    <div>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line me-1"></i> Tambah Tugas Baru
                        </a>
                    </div>
                </div>

                <!-- Filter Controls -->
                <form action="{{ route('tasks.index') }}" method="GET" class="p-2 bg-light rounded">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul atau isi tugas..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="belum dimulai" {{ request('status') == 'belum dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                                <option value="dikerjakan" {{ request('status') == 'dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlewat Deadline</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="priority" class="form-select form-select-sm">
                                <option value="">Semua Prioritas</option>
                                <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="sedang" {{ request('priority') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="rendah" {{ request('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4 col-6">
                            <select name="category_id" class="form-select form-select-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4 col-6">
                            <select name="sort" class="form-select form-select-sm">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru Dibuat</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama Dibuat</option>
                                <option value="due_date_asc" {{ request('sort') == 'due_date_asc' ? 'selected' : '' }}>Deadline Terdekat</option>
                                <option value="priority_high" {{ request('sort') == 'priority_high' ? 'selected' : '' }}>Prioritas Tertinggi</option>
                            </select>
                        </div>

                        <div class="col-lg-1 col-md-4 col-12 d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm w-100" title="Terapkan Filter">
                                <i class="ri-filter-3-line"></i>
                            </button>
                            @if(request()->hasAny(['search', 'status', 'priority', 'category_id', 'sort']))
                                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Judul Tugas</th>
                                <th>Kategori</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Tenggat Waktu</th>
                                <th>Dibuat</th>
                                <th class="text-center" style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $index => $task)
                                <tr class="{{ $task->is_overdue ? 'table-danger bg-opacity-25' : '' }}">
                                    <td class="text-muted fw-semibold">
                                        {{ $tasks->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-body fw-bold text-truncate" style="max-width: 280px;">
                                                {{ $task->title }}
                                            </a>
                                            @if($task->description)
                                                <small class="text-muted text-truncate" style="max-width: 280px;">
                                                    {{ $task->description }}
                                                </small>
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
                                        <!-- Quick Status Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle {{ $task->status_badge_class }} py-0 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                                            <span class="fs-12 {{ $task->is_overdue ? 'text-danger fw-bold' : 'text-body' }}">
                                                {{ $task->due_date->translatedFormat('d M Y') }}
                                            </span>
                                            @if($task->is_overdue)
                                                <span class="badge bg-danger-subtle text-danger d-block fs-10 mt-1" style="width: fit-content;">
                                                    Terlewat ({{ $task->due_date->diffForHumans() }})
                                                </span>
                                            @else
                                                <small class="text-muted d-block fs-11">{{ $task->due_date->diffForHumans() }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $task->created_at->translatedFormat('d M Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info" title="Detail">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-warning" title="Ubah">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $task->id }}" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content text-start">
                                                    <div class="modal-body text-center p-4">
                                                        <i class="ri-alert-line text-danger fs-48 d-block mb-2"></i>
                                                        <h5 class="modal-title mb-2">Hapus Tugas?</h5>
                                                        <p class="text-muted fs-13 mb-3">Tindakan ini permanen. Tugas <strong>"{{ $task->title }}"</strong> akan dihapus dari sistem.</p>
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
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ri-inbox-line fs-24 d-block mb-1"></i>
                                        Tidak ada data tugas yang sesuai dengan filter.
                                    </td>
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
