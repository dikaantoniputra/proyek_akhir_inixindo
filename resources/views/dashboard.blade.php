@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

@if(Auth::user()->isAdmin())
    <div class="card bg-primary-subtle border-primary-subtle border mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary text-white rounded-3 fs-20">
                            <i class="ri-shield-star-line"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="my-0 fw-bold text-primary">Mode Administrator Aktif</h5>
                        <p class="mb-0 text-muted fs-13">Anda memiliki hak akses penuh untuk memantau tugas seluruh pegawai dan rekap beban kerja organisasi.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.tasks') }}" class="btn btn-primary btn-sm">
                        <i class="ri-file-list-3-line me-1"></i> Semua Tugas Pegawai
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm bg-white">
                        <i class="ri-user-star-line me-1"></i> Rekap Penggunaan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row g-3 mb-4">
    
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

<div class="row g-4 mb-4">
    
    <div class="col-lg-6">
        <div class="card h-100 mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">
                    <i class="ri-pie-chart-2-line me-1 text-primary"></i> Distribusi Status Tugas
                </h4>
                <span class="badge bg-primary-subtle text-primary fs-12 px-2 py-1">{{ $totalTasks }} Total Tugas</span>
            </div>
            <div class="card-body">
                @if($totalTasks > 0)
                    <div id="status-donut-chart" class="apex-charts" style="min-height: 260px;"></div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="ri-pie-chart-line fs-36 text-secondary d-block mb-2"></i>
                        Belum ada data tugas untuk ditampilkan pada grafik.
                    </div>
                @endif
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card h-100 mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">
                    <i class="ri-bar-chart-2-line me-1 text-warning"></i> Distribusi Prioritas Tugas
                </h4>
                <span class="badge bg-warning-subtle text-warning fs-12 px-2 py-1">Tingkat Urgensi</span>
            </div>
            <div class="card-body">
                @if($totalTasks > 0)
                    <div id="priority-bar-chart" class="apex-charts" style="min-height: 260px;"></div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="ri-bar-chart-line fs-36 text-secondary d-block mb-2"></i>
                        Belum ada data prioritas tugas untuk ditampilkan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-xl-4 col-lg-5">
        <div class="card h-100 mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">Ringkasan Progres</h4>
                <span class="badge bg-success-subtle text-success fs-12 px-2 py-1">{{ $completionRate }}% Selesai</span>
            </div>
            <div class="card-body">
                
                <div class="progress mb-4" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%" title="Selesai"></div>
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalTasks > 0 ? ($inProgressTasks / $totalTasks) * 100 : 0 }}%" title="Dikerjakan"></div>
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $totalTasks > 0 ? ($pendingTasks / $totalTasks) * 100 : 0 }}%" title="Belum Dimulai"></div>
                </div>

                
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

@push('scripts')
<script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($totalTasks > 0)
            // 1. Donut Chart Status
            var statusChartOptions = {
                chart: {
                    type: 'donut',
                    height: 260,
                    fontFamily: 'inherit'
                },
                series: [{{ $completedTasks }}, {{ $inProgressTasks }}, {{ $pendingTasks }}],
                labels: ['Selesai', 'Sedang Dikerjakan', 'Belum Dimulai'],
                colors: ['#0acf97', '#39afd1', '#6c757d'],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '13px'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex];
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + " Tugas";
                        }
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Tugas',
                                    fontSize: '13px',
                                    color: '#6c757d',
                                    formatter: function () {
                                        return "{{ $totalTasks }}";
                                    }
                                }
                            }
                        }
                    }
                }
            };
            var statusChart = new ApexCharts(document.querySelector("#status-donut-chart"), statusChartOptions);
            statusChart.render();

            // 2. Bar Chart Priority
            var priorityChartOptions = {
                chart: {
                    type: 'bar',
                    height: 260,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        horizontal: false,
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: { fontSize: '12px', fontWeight: 'bold' }
                },
                series: [{
                    name: 'Jumlah Tugas',
                    data: [{{ $priorityHighCount }}, {{ $priorityMediumCount }}, {{ $priorityLowCount }}]
                }],
                xaxis: {
                    categories: ['Tinggi', 'Sedang', 'Rendah'],
                    labels: { style: { fontSize: '12px' } }
                },
                yaxis: {
                    show: false
                },
                colors: ['#fa5c7c', '#ffbc00', '#39afd1'],
                legend: {
                    show: false
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + " Tugas";
                        }
                    }
                }
            };
            var priorityChart = new ApexCharts(document.querySelector("#priority-bar-chart"), priorityChartOptions);
            priorityChart.render();
        @endif
    });
</script>
@endpush
