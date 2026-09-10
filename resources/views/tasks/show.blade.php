@extends('layouts.app')

@section('title', 'Detail Tugas: ' . $task->title)
@section('page_title', 'Detail Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Daftar Tugas</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-11">

        @if($task->is_overdue)
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                <i class="ri-alarm-warning-fill fs-20 me-2"></i>
                <div class="flex-grow-1">
                    <strong>Perhatian!</strong> Tugas ini telah melewati tenggat waktu yang ditentukan ({{ $task->due_date->translatedFormat('d F Y') }}).
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-0">
            <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="{{ $task->status_badge_class }} fs-12 px-2 py-1">
                        Status: {{ ucfirst($task->status) }}
                    </span>
                    <span class="{{ $task->priority_badge_class }} fs-12 px-2 py-1">
                        Prioritas: {{ ucfirst($task->priority) }}
                    </span>
                    @if($task->category)
                        <span class="badge bg-{{ $task->category->color }}-subtle text-{{ $task->category->color }} border border-{{ $task->category->color }}-subtle fs-12 px-2 py-1">
                            {{ $task->category->name }}
                        </span>
                    @endif
                </div>

                <div class="btn-group btn-group-sm">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning text-dark fw-semibold">
                        <i class="ri-edit-line me-1"></i> Ubah
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="ri-delete-bin-line me-1"></i> Hapus
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Title -->
                <h3 class="mt-0 mb-3 text-dark fw-bold">{{ $task->title }}</h3>

                <!-- Metadata Grid -->
                <div class="row g-3 p-3 bg-light rounded mb-4">
                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted d-block fs-11 text-uppercase">Pemilik Tugas</small>
                        <span class="fw-semibold text-body fs-13">
                            <i class="ri-user-line text-muted me-1"></i>{{ $task->user->name ?? 'Saya' }}
                        </span>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted d-block fs-11 text-uppercase">Tenggat Waktu</small>
                        @if($task->due_date)
                            <span class="fw-semibold fs-13 {{ $task->is_overdue ? 'text-danger' : 'text-body' }}">
                                <i class="ri-calendar-event-line me-1"></i>
                                {{ $task->due_date->translatedFormat('d F Y') }}
                            </span>
                            <small class="d-block text-muted fs-11">({{ $task->due_date->diffForHumans() }})</small>
                        @else
                            <span class="text-muted fs-13">Tidak ada tenggat</span>
                        @endif
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted d-block fs-11 text-uppercase">Dibuat Pada</small>
                        <span class="fw-semibold text-body fs-13">
                            <i class="ri-time-line text-muted me-1"></i>{{ $task->created_at->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <small class="text-muted d-block fs-11 text-uppercase">Terakhir Diperbarui</small>
                        <span class="fw-semibold text-body fs-13">
                            <i class="ri-history-line text-muted me-1"></i>{{ $task->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <h5 class="fs-14 fw-bold mb-2">Deskripsi / Catatan Tugas:</h5>
                <div class="p-3 border rounded mb-4 bg-white">
                    @if($task->description)
                        <p class="mb-0 text-body lh-base" style="white-space: pre-line;">{{ $task->description }}</p>
                    @else
                        <p class="text-muted fst-italic mb-0">Tidak ada deskripsi tambahan.</p>
                    @endif
                </div>

                <!-- Quick Status Bar -->
                <div class="p-3 bg-light rounded d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h6 class="my-0 fw-semibold fs-13">Ubah Status Cepat:</h6>
                        <small class="text-muted">Klik tombol di samping untuk memperbarui status pengerjaan secara langsung.</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @if($task->status != 'belum dimulai')
                            <form action="{{ route('tasks.status', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="belum dimulai">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="ri-time-line me-1"></i> Belum Dimulai
                                </button>
                            </form>
                        @endif

                        @if($task->status != 'dikerjakan')
                            <form action="{{ route('tasks.status', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="dikerjakan">
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-loader-2-line me-1"></i> Kerjakan
                                </button>
                            </form>
                        @endif

                        @if($task->status != 'selesai')
                            <form action="{{ route('tasks.status', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ri-checkbox-circle-line me-1"></i> Selesai
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                    <a href="{{ route('tasks.index') }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Kembali ke Daftar
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-start">
            <div class="modal-body text-center p-4">
                <i class="ri-alert-line text-danger fs-48 d-block mb-2"></i>
                <h5 class="modal-title mb-2">Hapus Tugas?</h5>
                <p class="text-muted fs-13 mb-3">Apakah Anda yakin ingin menghapus <strong>"{{ $task->title }}"</strong>?</p>
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
@endsection
