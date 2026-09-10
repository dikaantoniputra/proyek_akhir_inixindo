@extends('layouts.app')

@section('title', 'Rekap Penggunaan')
@section('page_title', 'Rekap Penggunaan & Beban Kerja')

@push('styles')
    
    <link href="/assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Rekap Penggunaan</li>
@endsection

@section('content')

<div class="row">
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-3 fs-20">
                            <i class="ri-team-line"></i>
                        </span>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase mt-0" title="Total Pegawai Terdaftar">Total Pegawai</h6>
                <h2 class="my-2 text-dark">{{ $summaryStats['total_users'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-primary me-1"><i class="ri-user-follow-line"></i> Akun aktif</span>
                    <span class="text-nowrap">dalam sistem</span>
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
                            <i class="ri-task-line"></i>
                        </span>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase mt-0" title="Total Seluruh Tugas">Total Tugas</h6>
                <h2 class="my-2 text-info">{{ $summaryStats['total_tasks'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-info me-1"><i class="ri-stack-line"></i> Terdistribusi</span>
                    <span class="text-nowrap">ke pegawai</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-warning-subtle text-warning rounded-3 fs-20">
                            <i class="ri-pie-chart-line"></i>
                        </span>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase mt-0" title="Rata-rata Tugas per Pegawai">Rata-rata Beban</h6>
                <h2 class="my-2 text-warning">{{ $summaryStats['avg_tasks'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-warning me-1"><i class="ri-calculator-line"></i> Tugas</span>
                    <span class="text-nowrap">per akun</span>
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
                <h6 class="text-muted text-uppercase mt-0" title="Tingkat Keberhasilan Global">Tingkat Penyelesaian</h6>
                <h2 class="my-2 text-success">{{ $summaryStats['completion_rate'] }}%</h2>
                <p class="mb-0 text-muted">
                    <span class="badge bg-success-subtle text-success">Global</span>
                    <span class="text-nowrap ms-1">seluruh tugas tuntas</span>
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
                    <div class="col-sm-8">
                        <h4 class="header-title mb-1">Rekapitulasi Penggunaan & Beban Kerja Pegawai</h4>
                        <p class="text-muted fs-14 mb-0">
                            Ringkasan kuantitatif seluruh pengguna terdaftar, jumlah beban tugas, rincian status pengerjaan, dan tingkat penyelesaian tugas masing-masing pegawai.
                        </p>
                    </div>
                    <div class="col-sm-4 text-sm-end mt-2 mt-sm-0">
                        <a href="{{ route('admin.users.export') }}" class="btn btn-outline-success btn-sm" title="Unduh rekapitulasi ke file CSV/Excel">
                            <i class="ri-file-excel-line me-1"></i> Ekspor CSV
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>Pegawai</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Total Tugas</th>
                                <th class="text-center">Belum Mulai</th>
                                <th class="text-center">Dikerjakan</th>
                                <th class="text-center">Selesai</th>
                                <th class="text-center">Overdue</th>
                                <th style="min-width: 150px;">Progres Selesai</th>
                                <th class="text-center" style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $u)
                                @php
                                    $userCompletionRate = $u->tasks_count > 0 ? round(($u->completed_tasks_count / $u->tasks_count) * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2 flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold fs-12">
                                                    {{ $u->initials }}
                                                </span>
                                            </div>
                                            <span class="fw-semibold text-dark fs-13">{{ $u->name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="text-muted fs-13">{{ $u->email }}</span></td>
                                    <td>
                                        <span class="badge {{ $u->role === 'admin' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }} text-capitalize">
                                            {{ $u->role }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold fs-14">{{ $u->tasks_count }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $u->pending_tasks_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-subtle text-info">{{ $u->in_progress_tasks_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success">{{ $u->completed_tasks_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($u->overdue_tasks_count > 0)
                                            <span class="badge bg-danger">{{ $u->overdue_tasks_count }}</span>
                                        @else
                                            <span class="text-muted fs-12">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $userCompletionRate }}%"></div>
                                            </div>
                                            <span class="fw-semibold fs-12 text-dark">{{ $userCompletionRate }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.tasks', ['user_id' => $u->id]) }}" class="btn btn-sm btn-info py-0 px-2 fs-12" title="Lihat Tugas Pegawai Ini">
                                            <i class="ri-eye-line me-1"></i> Tugas
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    
    <script src="/assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#basic-datatable').DataTable({
                keys: true,
                language: {
                    paginate: {
                        previous: "<i class='ri-arrow-left-s-line'>",
                        next: "<i class='ri-arrow-right-s-line'>"
                    },
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data pegawai...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ pegawai",
                    infoEmpty: "Menampilkan 0 pegawai",
                    zeroRecords: "Tidak ditemukan data pegawai yang cocok",
                    infoFiltered: "(disaring dari _MAX_ total pegawai)"
                },
                pageLength: 10,
                responsive: true,
                order: [[4, 'desc']],
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                    $(".dataTables_length select").addClass("form-select form-select-sm");
                    $(".dataTables_filter input").addClass("form-control form-control-sm");
                }
            });
        });
    </script>
@endpush
