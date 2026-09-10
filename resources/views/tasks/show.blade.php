@extends('layouts.app')

@section('title', 'Detail Tugas: ' . $task->title)
@section('page_title', 'Detail Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    @if(Auth::user()->isAdmin() && $task->user_id !== Auth::id())
        <li class="breadcrumb-item"><a href="{{ route('admin.tasks') }}">Semua Tugas (Admin)</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Daftar Tugas</a></li>
    @endif
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<style>
    @media print {
        .leftside-menu, .navbar-custom, .footer, .btn, .btn-group, .alert, .breadcrumb, .page-title-box, .no-print {
            display: none !important;
        }
        .content-page {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .content {
            padding: 0 !important;
        }
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
        .print-header {
            display: block !important;
        }
        .print-signature {
            display: flex !important;
        }
        body {
            background-color: #fff !important;
            color: #000 !important;
            font-size: 12pt !important;
        }
    }
    .print-header, .print-signature {
        display: none;
    }
</style>
@endpush

@section('content')

<div class="print-header mb-4 border-bottom pb-3">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-bold mb-1">TASKAPP - LEMBAR TUGAS PEGAWAI</h2>
            <p class="text-muted mb-0">Sistem Informasi Manajemen Tugas Terpadu</p>
        </div>
        <div class="text-end">
            <p class="mb-0 fw-semibold">ID Tugas: #{{ str_pad($task->id, 5, '0', STR_PAD_LEFT) }}</p>
            <small class="text-muted">Dicetak pada: {{ date('d/m/Y H:i') }}</small>
        </div>
    </div>
</div>

@if($task->is_overdue)
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3 no-print" role="alert">
        <i class="ri-alarm-warning-fill fs-20 me-2"></i>
        <div class="flex-grow-1">
            <strong>Perhatian!</strong> Tugas ini telah melewati tenggat waktu yang ditentukan ({{ $task->due_date->translatedFormat('d F Y') }}).
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    
    <div class="col-xl-8 col-lg-7">
        
        <div class="card d-block">
            <div class="card-body">
                
                <div class="dropdown float-end no-print">
                    <a href="#" class="dropdown-toggle arrow-none text-muted" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Opsi">
                        <i class="ri-more-fill fs-18"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm border">
                        <a href="javascript:void(0);" onclick="window.print()" class="dropdown-item">
                            <i class="ri-printer-line me-1"></i> Cetak Lembar Tugas
                        </a>
                        <a href="{{ route('tasks.edit', $task) }}" class="dropdown-item">
                            <i class="ri-edit-box-line me-1"></i> Edit Tugas
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0);" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="ri-delete-bin-line me-1"></i> Hapus Tugas
                        </a>
                    </div>
                </div>

                
                <div class="form-check float-start no-print">
                    <form action="{{ route('tasks.status', $task) }}" method="POST" id="toggleTaskCompleteForm">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $task->status === 'selesai' ? 'dikerjakan' : 'selesai' }}">
                        <input type="checkbox" class="form-check-input" id="completedCheck" onchange="document.getElementById('toggleTaskCompleteForm').submit()" {{ $task->status === 'selesai' ? 'checked' : '' }} />
                        <label class="form-check-label fw-semibold fs-13" for="completedCheck">
                            {{ $task->status === 'selesai' ? 'Tandai Belum Selesai' : 'Tandai Sudah Selesai' }}
                        </label>
                    </form>
                </div>
                <div class="clearfix"></div>

                
                <h4 class="mt-3 mb-2 fw-bold text-dark">{{ $task->title }}</h4>

                <div class="row g-2 my-2 py-2 bg-light-subtle rounded border border-light-subtle">
                    <div class="col-sm-4">
                        <p class="mb-0 text-muted fs-11 text-uppercase fw-semibold">Status Pengerjaan</p>
                        <span class="{{ $task->status_badge_class }} rounded-pill fs-11 px-2 py-0 mt-1 d-inline-block">
                            <i class="{{ $task->status_icon }} me-1"></i>{{ ucfirst($task->status) }}
                        </span>
                    </div>
                    <div class="col-sm-4">
                        <p class="mb-0 text-muted fs-11 text-uppercase fw-semibold">Prioritas</p>
                        <span class="{{ $task->priority_badge_class }} rounded-pill fs-11 px-2 py-0 mt-1 d-inline-block">
                            <i class="{{ $task->priority_icon }} me-1"></i>{{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    <div class="col-sm-4">
                        <p class="mb-0 text-muted fs-11 text-uppercase fw-semibold">Kategori</p>
                        @if($task->category)
                            <span class="badge bg-{{ $task->category->color }}-subtle text-{{ $task->category->color }} rounded-pill fs-11 px-2 py-0 mt-1 d-inline-block">
                                {{ $task->category->name }}
                            </span>
                        @else
                            <span class="text-muted fs-12 mt-1 d-inline-block">-</span>
                        @endif
                    </div>
                </div>

                
                <h5 class="mt-3 fs-14 fw-bold">Deskripsi Tugas:</h5>
                <div class="text-muted mb-4 fs-13 lh-base" style="white-space: pre-line;">
                    {{ $task->description ?: 'Tidak ada deskripsi tambahan untuk tugas ini.' }}
                </div>

                
                <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                    <h5 class="my-0 fs-14 fw-bold">
                        <i class="ri-checkbox-multiple-line text-primary me-1"></i> Sub-Tugas (Checklist)
                    </h5>
                    <span class="badge {{ $task->checklist_progress_percentage == 100 ? 'bg-success' : 'bg-primary' }} rounded-pill fs-11">
                        {{ $task->completed_checklists_count }}/{{ $task->total_checklists_count }} ({{ $task->checklist_progress_percentage }}%)
                    </span>
                </div>

                
                @if($task->total_checklists_count > 0)
                    <div class="progress progress-sm mb-3" style="height: 6px;">
                        <div class="progress-bar {{ $task->checklist_progress_percentage == 100 ? 'bg-success' : 'bg-primary' }}" 
                             role="progressbar" 
                             style="width: {{ $task->checklist_progress_percentage }}%;" 
                             aria-valuenow="{{ $task->checklist_progress_percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                @endif

                
                @forelse($task->checklists as $checklist)
                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light-subtle">
                        <div class="form-check my-1">
                            <form action="{{ route('tasks.checklists.toggle', $checklist) }}" method="POST" id="toggle-checklist-{{ $checklist->id }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" class="form-check-input" id="checklist-{{ $checklist->id }}" onchange="document.getElementById('toggle-checklist-{{ $checklist->id }}').submit()" {{ $checklist->is_completed ? 'checked' : '' }} />
                                <label class="form-check-label fs-13 {{ $checklist->is_completed ? 'text-decoration-line-through text-muted' : 'text-dark' }}" for="checklist-{{ $checklist->id }}">
                                    {{ $checklist->title }}
                                </label>
                            </form>
                        </div>
                        <form action="{{ route('tasks.checklists.destroy', $checklist) }}" method="POST" class="d-inline no-print" onsubmit="return confirm('Hapus sub-tugas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link btn-xs text-muted p-0" title="Hapus">
                                <i class="ri-delete-bin-line fs-14"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted fs-12 mb-3 fst-italic">Belum ada sub-tugas. Tambahkan rincian to-do di bawah ini agar progres tugas lebih terstruktur.</p>
                @endforelse

                
                <form action="{{ route('tasks.checklists.store', $task) }}" method="POST" class="mt-3 no-print">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="text" name="title" class="form-control" placeholder="Tambah rincian sub-tugas baru..." required>
                        <button class="btn btn-primary px-3" type="submit">
                            <i class="ri-add-line me-1"></i> Tambah
                        </button>
                    </div>
                </form>

            </div> 
        </div> 

        
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4 mt-0 fs-16 fw-bold">
                    Catatan Progres & Aktivitas ({{ $task->comments->count() }})
                </h4>

                
                @forelse($task->comments as $comment)
                    <div class="d-flex align-items-start pb-3 mb-3 border-bottom {{ $loop->last ? 'border-bottom-0 pb-0 mb-0' : '' }}">
                        <div class="avatar-sm me-2 flex-shrink-0">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-12 fw-bold">
                                {{ $comment->user->initials ?? 'US' }}
                            </span>
                        </div>
                        <div class="w-100">
                            <h5 class="mt-0 fs-13 mb-1">
                                {{ $comment->user->name ?? 'User' }}
                                @if($comment->user && $comment->user->isAdmin())
                                    <span class="badge bg-danger-subtle text-danger fs-10 px-1 py-0 ms-1">Admin</span>
                                @endif
                                <small class="text-muted float-end fs-11">
                                    <i class="ri-time-line me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                </small>
                            </h5>
                            <p class="text-muted fs-13 mb-1" style="white-space: pre-line;">{{ $comment->comment }}</p>

                            @if($comment->user_id === Auth::id() || Auth::user()->isAdmin())
                                <form action="{{ route('tasks.comments.destroy', $comment) }}" method="POST" class="d-inline no-print" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-xs text-danger p-0" title="Hapus Catatan">
                                        <i class="ri-delete-bin-line me-1"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3 fs-13">
                        <i class="ri-discuss-line fs-28 d-block mb-1 text-secondary opacity-50"></i>
                        Belum ada catatan progres pada tugas ini.
                    </div>
                @endforelse

                
                <div class="border rounded mt-4 no-print">
                    <form action="{{ route('tasks.comments.store', $task) }}" method="POST" class="comment-area-box">
                        @csrf
                        <textarea name="comment" rows="3" class="form-control border-0 resize-none p-2 fs-13" placeholder="Tulis catatan atau laporan progres tugas..." required></textarea>
                        <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted"><i class="ri-information-line me-1"></i>Catatan terlihat oleh supervisor.</small>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="ri-send-plane-2 me-1"></i> Kirim Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div> 
        </div> 

        
        <div class="print-signature row mt-5 pt-4 text-center">
            <div class="col-6">
                <p class="mb-5 text-muted">Pegawai Pelaksana,</p>
                <p class="fw-bold mb-0 text-decoration-underline">{{ $task->user->name ?? 'Pegawai' }}</p>
                <small class="text-muted">NIP / ID: {{ $task->user->id ?? '-' }}</small>
            </div>
            <div class="col-6">
                <p class="mb-5 text-muted">Supervisor / Administrator,</p>
                <p class="fw-bold mb-0 text-decoration-underline">( ........................................ )</p>
                <small class="text-muted">Tanda Tangan & Tanggal</small>
            </div>
        </div>

    </div>
    

    
    <div class="col-xl-4 col-lg-5">
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fs-14 text-uppercase mb-3">Informasi Tugas</h5>

                
                <p class="mt-2 mb-1 text-muted fs-11 text-uppercase fw-semibold">Pemilik Tugas</p>
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-2">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-11 fw-bold">
                            {{ strtoupper(substr($task->user->name ?? 'Saya', 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <h5 class="my-0 fs-13 fw-semibold">{{ $task->user->name ?? 'Saya' }}</h5>
                        <small class="text-muted fs-11">{{ $task->user->email ?? '-' }}</small>
                    </div>
                </div>

                
                <p class="mt-3 mb-1 text-muted fs-11 text-uppercase fw-semibold">Tenggat Waktu</p>
                <div class="d-flex align-items-center">
                    <i class="ri-calendar-todo-line fs-18 {{ $task->is_overdue ? 'text-danger' : 'text-success' }} me-2"></i>
                    <div>
                        <h5 class="my-0 fs-13 fw-semibold {{ $task->is_overdue ? 'text-danger' : '' }}">
                            {{ $task->due_date ? $task->due_date->translatedFormat('d F Y') : 'Tanpa batas tenggat' }}
                        </h5>
                        @if($task->due_date)
                            <small class="text-muted fs-11">({{ $task->due_date->diffForHumans() }})</small>
                        @endif
                    </div>
                </div>

                
                <p class="mt-3 mb-1 text-muted fs-11 text-uppercase fw-semibold">Waktu Pembuatan</p>
                <div class="d-flex align-items-center">
                    <i class="ri-time-line fs-18 text-muted me-2"></i>
                    <div>
                        <h5 class="my-0 fs-13 fw-semibold">{{ $task->created_at->translatedFormat('d M Y, H:i') }}</h5>
                        <small class="text-muted fs-11">Diperbarui: {{ $task->updated_at->diffForHumans() }}</small>
                    </div>
                </div>

                
                <hr class="my-3">
                <p class="mb-2 text-muted fs-11 text-uppercase fw-semibold">Ubah Status Cepat</p>
                <div class="d-grid gap-2 no-print">
                    @if($task->status !== 'belum dimulai')
                        <form action="{{ route('tasks.status', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="belum dimulai">
                            <button type="submit" class="btn btn-sm btn-outline-secondary w-100 text-start">
                                <i class="ri-time-line me-1"></i> Pindahkan ke Belum Dimulai
                            </button>
                        </form>
                    @endif
                    @if($task->status !== 'dikerjakan')
                        <form action="{{ route('tasks.status', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="dikerjakan">
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100 text-start">
                                <i class="ri-loader-2-line me-1"></i> Pindahkan ke Sedang Dikerjakan
                            </button>
                        </form>
                    @endif
                    @if($task->status !== 'selesai')
                        <form action="{{ route('tasks.status', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" class="btn btn-sm btn-success w-100 text-start">
                                <i class="ri-checkbox-circle-line me-1"></i> Selesaikan Tugas Ini
                            </button>
                        </form>
                    @endif
                </div>

            </div> 
        </div> 

        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fs-14 text-uppercase mb-3">Lampiran Berkas</h5>

                @if($task->has_attachment)
                    <div class="card mb-2 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded fs-13 fw-bold">
                                            {{ strtoupper($task->attachment_extension) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col ps-0">
                                    <a href="{{ $task->attachment_url }}" target="_blank" class="text-muted fw-bold fs-13 d-block text-truncate" style="max-width: 160px;" title="{{ $task->attachment_name }}">
                                        {{ $task->attachment_name }}
                                    </a>
                                    <p class="mb-0 fs-11 text-muted">{{ $task->attachment_size_formatted }}</p>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ $task->attachment_url }}" download="{{ $task->attachment_name }}" class="btn btn-link fs-16 text-muted p-1" title="Unduh Berkas">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($task->is_image_attachment)
                        <div class="text-center mt-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imagePreviewModal">
                                <img src="{{ $task->attachment_url }}" alt="{{ $task->attachment_name }}" class="img-fluid rounded border shadow-sm" style="max-height: 180px; object-fit: contain;">
                            </a>
                            <small class="d-block text-muted mt-1 fs-11">Klik gambar untuk memperbesar</small>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-3 fs-13">
                        <i class="ri-attachment-line fs-28 d-block mb-1 text-secondary opacity-50"></i>
                        Tidak ada berkas yang dilampirkan.
                    </div>
                @endif

                <div class="mt-3 text-center no-print">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="ri-upload-cloud-line me-1"></i> {{ $task->has_attachment ? 'Ganti Berkas Lampiran' : 'Unggah Berkas Lampiran' }}
                    </a>
                </div>
            </div> 
        </div> 

        
        <div class="card no-print">
            <div class="card-body p-2">
                <a href="{{ Auth::user()->isAdmin() && $task->user_id !== Auth::id() ? route('admin.tasks') : route('tasks.index') }}" class="btn btn-light btn-sm w-100">
                    <i class="ri-arrow-left-line me-1"></i> Kembali ke Daftar Tugas
                </a>
            </div>
        </div>

    </div>
    
</div>

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

@if($task->has_attachment && $task->is_image_attachment)

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-14 fw-bold">
                    <i class="ri-image-line me-1"></i> {{ $task->attachment_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3 bg-dark-subtle">
                <img src="{{ $task->attachment_url }}" alt="{{ $task->attachment_name }}" class="img-fluid rounded shadow">
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class="text-muted fs-12">{{ $task->attachment_size_formatted }}</span>
                <div>
                    <a href="{{ $task->attachment_url }}" download="{{ $task->attachment_name }}" class="btn btn-sm btn-primary">
                        <i class="ri-download-2-line me-1"></i> Unduh Gambar
                    </a>
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
