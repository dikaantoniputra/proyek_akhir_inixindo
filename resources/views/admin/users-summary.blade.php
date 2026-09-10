@extends('layouts.app')

@section('title', 'Rekap Pengguna')
@section('page_title', 'Rekapitulasi Tugas per Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Admin - Rekap Pengguna</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="header-title mb-0">Statistik Beban Kerja Pegawai</h4>
                    <p class="text-muted fs-13 mb-0">Ringkasan jumlah dan progres tugas untuk setiap akun pengguna.</p>
                </div>
                <span class="badge bg-primary-subtle text-primary">{{ $users->count() }} Pengguna</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Nama Pegawai</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Total Tugas</th>
                                <th class="text-center">Belum Dimulai</th>
                                <th class="text-center">Dikerjakan</th>
                                <th class="text-center">Selesai</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $u)
                                <tr>
                                    <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold fs-11">
                                                    {{ $u->initials }}
                                                </span>
                                            </div>
                                            <span class="fw-semibold text-body fs-13">{{ $u->name }}</span>
                                        </div>
                                    </td>
                                    <td><small class="text-muted">{{ $u->email }}</small></td>
                                    <td>
                                        <span class="badge {{ $u->role === 'admin' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }}">
                                            {{ ucfirst($u->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold">{{ $u->tasks_count }}</td>
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
                                        <a href="{{ route('admin.tasks', ['user_id' => $u->id]) }}" class="btn btn-sm btn-outline-primary" title="Lihat Semua Tugas">
                                            <i class="ri-eye-line me-1"></i> Tugas
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
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
