@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Row 1: KPI Cards (Attex Flat Widgets) -->
<div class="row g-3 mb-4">
    <!-- Total Tugas -->
    <div class="col-xxl-3 col-md-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-3 fs-20">
                            <i class="ri-file-list-3-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase" title="Total Tugas Saya">Total Tugas</h5>
                <h2 class="my-2 fw-bold text-dark">{{ $totalTasks }}</h2>
                <p class="mb-0 text-muted fs-12">
                    <span class="text-primary me-1"><i class="ri-checkbox-circle-line"></i> Semua tugas</span>
                    <span class="text-nowrap">terdaftar</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Belum Dimulai -->
    <div class="col-xxl-3 col-md-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-secondary-subtle text-secondary rounded-3 fs-20">
                            <i class="ri-time-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase" title="Belum Dimulai">Belum Dimulai</h5>
                <h2 class="my-2 fw-bold text-secondary">{{ $pendingTasks }}</h2>
                <p class="mb-0 text-muted fs-12">
                    <span class="badge bg-secondary-subtle text-secondary">{{ $totalTasks > 0 ? round(($pendingTasks / $totalTasks) * 100) : 0 }}%</span>
                    <span class="text-nowrap ms-1">dari total tugas</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Sedang Dikerjakan -->
    <div class="col-xxl-3 col-md-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-info-subtle text-info rounded-3 fs-20">
                            <i class="ri-loader-2-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase" title="Sedang Dikerjakan">Sedang Dikerjakan</h5>
                <h2 class="my-2 fw-bold text-info">{{ $inProgressTasks }}</h2>
                <p class="mb-0 text-muted fs-12">
                    <span class="badge bg-info-subtle text-info">{{ $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100) : 0 }}%</span>
                    <span class="text-nowrap ms-1">dalam proses</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Selesai -->
    <div class="col-xxl-3 col-md-6">
        <div class="card widget-flat h-100 mb-0">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-success-subtle text-success rounded-3 fs-20">
                            <i class="ri-check-double-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="text-muted fw-normal mt-0 fs-13 text-uppercase" title="Tugas Selesai">Tugas Selesai</h5>
                <h2 class="my-2 fw-bold text-success">{{ $completedTasks }}</h2>
                <p class="mb-0 text-muted fs-12">
                    <span class="badge bg-success-subtle text-success">{{ $completionRate }}%</span>
                    <span class="text-nowrap ms-1">tingkat keberhasilan</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Overdue Alert (if any) -->
@if($overdueTasks > 0)
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="ri-alarm-warning-fill fs-20 me-2"></i>
        <div class="flex-grow-1">
            <strong>Peringatan!</strong> Ada <strong>{{ $overdueTasks }}</strong> tugas yang telah melewati batas waktu tenggat.
        </div>
        <a href="{{ route('tasks.index', ['status' => 'overdue']) }}" class="btn btn-sm btn-danger me-2">
            Lihat Tugas Terlewat
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Row 3: Progress Breakdown & Upcoming Tasks -->
<div class="row g-4">
    <!-- Progress & Overview -->
    <div class="col-xl-4 col-lg-5">
        <div class="card h-100 mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">Progres Penyelesaian</h4>
                <span class="badge bg-success-subtle text-success fs-12 px-2 py-1">{{ $completionRate }}% Selesai</span>
            </div>
            <div class="card-body">
                <!-- Stacked Progress -->
                <div class="progress mb-4" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%" title="Selesai"></div>
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalTasks > 0 ? ($inProgressTasks / $totalTasks) * 100 : 0 }}%" title="Dikerjakan"></div>
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $totalTasks > 0 ? ($pendingTasks / $totalTasks) * 100 : 0 }}%" title="Belum Dimulai"></div>
                </div>

                <!-- Breakdown Items -->
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success p-1"><i class="ri-check-line fs-14"></i></span>
                            <span class="fs-13 fw-semibold">Selesai</span>
                        </div>
                        <span class="fs-13 fw-bold">{{ $completedTasks }} <small class="text-muted">({{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%)</small></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info-subtle text-info p-1"><i class="ri-loader-2-line fs-14"></i></span>
                            <span class="fs-13 fw-semibold">Sedang Dikerjakan</span>
                        </div>
                        <span class="fs-13 fw-bold">{{ $inProgressTasks }} <small class="text-muted">({{ $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100) : 0 }}%)</small></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary-subtle text-secondary p-1"><i class="ri-time-line fs-14"></i></span>
                            <span class="fs-13 fw-semibold">Belum Dimulai</span>
                        </div>
                        <span class="fs-13 fw-bold">{{ $pendingTasks }} <small class="text-muted">({{ $totalTasks > 0 ? round(($pendingTasks / $totalTasks) * 100) : 0 }}%)</small></span>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Tambah Tugas Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Tasks Table -->
    <div class="col-xl-8 col-lg-7">
        <div class="card h-100 mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">Tugas Mendekati Tenggat (7 Hari)</h4>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none">
                    Lihat Semua <i class="ri-arrow-right-s-line align-middle"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Tugas</th>
                                <th>Kategori</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Tenggat</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingTasks as $task)
                                <tr>
                                    <td>
                                        <a href="{{ route('tasks.show', $task) }}" class="text-body fw-semibold d-block text-truncate" style="max-width: 220px;">
                                            {{ $task->title }}
                                        </a>
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
                                            <span class="fs-12 text-warning fw-semibold">
                                                {{ $task->due_date->translatedFormat('d M Y') }}
                                            </span>
                                            <small class="text-muted d-block fs-11">({{ $task->due_date->diffForHumans() }})</small>
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
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="ri-checkbox-circle-line fs-24 text-success d-block mb-1"></i>
                                        Tidak ada tugas mendesak dalam 7 hari ke depan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Recent Tasks -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">Tugas Terbaru Ditambahkan</h4>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none">
                    Semua Tugas <i class="ri-arrow-right-s-line align-middle"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Tugas</th>
                                <th>Kategori</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Dibuat Pada</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTasks as $task)
                                <tr>
                                    <td>
                                        <a href="{{ route('tasks.show', $task) }}" class="text-body fw-semibold d-block text-truncate" style="max-width: 300px;">
                                            {{ $task->title }}
                                        </a>
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
                                        <span class="{{ $task->status_badge_class }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $task->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-info p-1 px-2" title="Detail">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada tugas yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
