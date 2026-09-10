@extends('layouts.app')

@section('title', 'Ubah Tugas: ' . $task->title)
@section('page_title', 'Ubah Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Daftar Tugas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.show', $task) }}">{{ Str::limit($task->title, 20) }}</a></li>
    <li class="breadcrumb-item active">Ubah</li>
@endsection

@section('content')
<div class="row">
    
    <div class="col-xl-8 col-lg-7">
        <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data" id="taskEditForm">
            @csrf
            @method('PUT')

            
            <div class="card mb-3 shadow-sm border">
                <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar-xs bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-2">
                            <i class="ri-edit-box-line fs-16"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fs-15">Perbarui Rincian Tugas</h5>
                            <small class="text-muted">Sesuaikan informasi judul, progres catatan, dan target penyelesaian.</small>
                        </div>
                    </div>
                    <span class="{{ $task->status_badge_class }} fs-12 px-2 py-1">
                        {{ ucfirst($task->status) }}
                    </span>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="title" class="form-label fw-semibold mb-0">
                                Judul Tugas <span class="text-danger">*</span>
                            </label>
                            <span class="text-muted fs-11" id="titleCharCount">{{ strlen($task->title) }} / 255 karakter</span>
                        </div>
                        <input type="text" 
                               class="form-control form-control-lg fs-14 @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $task->title) }}" 
                               maxlength="255"
                               required 
                               autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="description" class="form-label fw-semibold mb-0">Deskripsi & Catatan Perkembangan</label>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-xs btn-light border py-0 px-1 text-muted" onclick="insertTemplate('update')" title="Sisipkan update progress">
                                    <i class="ri-history-line fs-12 me-1"></i>+ Catatan Progress
                                </button>
                                <button type="button" class="btn btn-xs btn-light border py-0 px-1 text-muted" onclick="insertTemplate('checklist')" title="Sisipkan checklist">
                                    <i class="ri-list-check fs-12 me-1"></i>+ Checklist
                                </button>
                            </div>
                        </div>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="5" 
                                  placeholder="Catat rincian atau update progress tugas...">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            
            <div class="card mb-3 shadow-sm border">
                <div class="card-header bg-transparent border-bottom d-flex align-items-center">
                    <div class="avatar-xs bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center me-2">
                        <i class="ri-equalizer-line fs-16"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fs-15">Klasifikasi & Penjadwalan</h5>
                        <small class="text-muted">Perbarui tingkat prioritas, status pengerjaan, dan tenggat waktu.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        
                        <div class="col-md-6">
                            <label for="priority" class="form-label fw-semibold">
                                <i class="ri-flag-line text-warning me-1"></i> Prioritas <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="rendah" {{ old('priority', $task->priority) == 'rendah' ? 'selected' : '' }}>🟢 Rendah (Dapat ditunda)</option>
                                <option value="sedang" {{ old('priority', $task->priority) == 'sedang' ? 'selected' : '' }}>🟡 Sedang (Standar)</option>
                                <option value="tinggi" {{ old('priority', $task->priority) == 'tinggi' ? 'selected' : '' }}>🔴 Tinggi (Mendesak & Kritis)</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label for="task_status" class="form-label fw-semibold">
                                <i class="ri-progress-3-line text-primary me-1"></i> Status Tugas <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" id="task_status" name="status" required>
                                <option value="belum dimulai" {{ old('status', $task->status) == 'belum dimulai' ? 'selected' : '' }}>⚪ Belum Dimulai (To Do)</option>
                                <option value="dikerjakan" {{ old('status', $task->status) == 'dikerjakan' ? 'selected' : '' }}>🔵 Sedang Dikerjakan (In Progress)</option>
                                <option value="selesai" {{ old('status', $task->status) == 'selesai' ? 'selected' : '' }}>🟢 Selesai (Completed)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">
                                <i class="ri-price-tag-3-line text-success me-1"></i> Kategori Tugas
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">-- Pilih Kategori (Opsional) --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $task->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label for="due_date" class="form-label fw-semibold">
                                <i class="ri-calendar-event-line text-danger me-1"></i> Tenggat Waktu (Deadline)
                            </label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                            
                            
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 fs-11" onclick="setQuickDate(0)">Hari Ini</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 fs-11" onclick="setQuickDate(1)">Besok</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 fs-11" onclick="setQuickDate(3)">+3 Hari</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 fs-11" onclick="setQuickDate(7)">Minggu Depan</button>
                            </div>
                            @error('due_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-3 shadow-sm border">
                <div class="card-header bg-transparent border-bottom d-flex align-items-center">
                    <div class="avatar-xs bg-secondary-subtle text-secondary rounded-circle d-flex align-items-center justify-content-center me-2">
                        <i class="ri-attachment-line fs-16"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fs-15">Dokumen & Lampiran Berkas</h5>
                        <small class="text-muted">Kelola berkas pendukung tugas ini.</small>
                    </div>
                </div>
                <div class="card-body">
                    @if($task->has_attachment)
                        <div class="card bg-light border mb-3">
                            <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-white rounded shadow-sm d-flex align-items-center justify-content-center border">
                                        <i class="{{ $task->attachment_icon_class }} fs-24"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold fs-13 d-block text-dark">{{ $task->attachment_name }}</span>
                                        <small class="text-muted">{{ $task->attachment_size_formatted }} &bull; Format: {{ strtoupper($task->attachment_extension) }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ $task->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Buka File">
                                        <i class="ri-external-link-line me-1"></i> Buka
                                    </a>
                                    <a href="{{ $task->attachment_url }}" download="{{ $task->attachment_name }}" class="btn btn-sm btn-primary" title="Unduh File">
                                        <i class="ri-download-2-line me-1"></i> Unduh
                                    </a>
                                    <div class="form-check ms-2 border-start ps-3">
                                        <input class="form-check-input" type="checkbox" name="remove_attachment" value="1" id="remove_attachment">
                                        <label class="form-check-label text-danger fs-12 fw-bold" for="remove_attachment">
                                            Hapus Berkas Ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <label class="form-label fw-semibold fs-13 mb-1">
                        {{ $task->has_attachment ? 'Ganti dengan Berkas Baru (Opsional):' : 'Unggah Berkas Lampiran (Opsional):' }}
                    </label>
                    <input type="file" 
                           class="form-control @error('attachment') is-invalid @enderror" 
                           id="attachment" 
                           name="attachment" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.jpg,.jpeg,.png,.webp,.txt,.csv">
                    @error('attachment')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @else
                        <div class="form-text fs-12 text-muted mt-1">
                            Mendukung PDF, Word, Excel, PowerPoint, ZIP/RAR, Gambar, dan TXT (Maks. 10 MB).
                        </div>
                    @enderror
                </div>
            </div>

            
            <div class="card mb-4 shadow-sm border">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4">
                        <i class="ri-check-line me-1"></i> Simpan Perubahan Tugas
                    </button>
                </div>
            </div>

        </form>
    </div>

    
    <div class="col-xl-4 col-lg-5">
        
        
        <div class="card shadow-sm border mb-3">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0 fs-14">
                    <i class="ri-information-line text-primary me-1"></i> Informasi Riwayat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom fs-13">
                    <span class="text-muted">ID Tugas:</span>
                    <span class="fw-bold text-dark">#{{ str_pad($task->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom fs-13">
                    <span class="text-muted">Pemilik:</span>
                    <span class="fw-semibold text-dark">{{ $task->user->name ?? 'Saya' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom fs-13">
                    <span class="text-muted">Dibuat Pada:</span>
                    <span class="text-dark">{{ $task->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 fs-13">
                    <span class="text-muted">Terakhir Diperbarui:</span>
                    <span class="text-dark">{{ $task->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        
        <div class="card shadow-sm border">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0 fs-14">
                    <i class="ri-question-line text-info me-1"></i> Bantuan Cepat
                </h5>
            </div>
            <div class="card-body fs-13 text-muted">
                <p class="mb-2">Jika tugas telah diselesaikan sepenuhnya, ubah status menjadi <strong class="text-success">Selesai</strong> untuk memperbarui statistik pencapaian dashboard Anda.</p>
                <p class="mb-0">File lampiran yang dihapus atau diganti akan secara otomatis dibersihkan dari server.</p>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('description');
    const titleCharCount = document.getElementById('titleCharCount');
    const dueDateInput = document.getElementById('due_date');

    titleInput.addEventListener('input', () => {
        titleCharCount.textContent = `${titleInput.value.length} / 255 karakter`;
    });

    function setQuickDate(daysToAdd) {
        const d = new Date();
        d.setDate(d.getDate() + daysToAdd);
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        dueDateInput.value = `${yyyy}-${mm}-${dd}`;
    }

    function insertTemplate(type) {
        let textToInsert = '';
        if (type === 'checklist') {
            textToInsert = "\n\n📌 Checklist Tambahan:\n[ ] Langkah:";
        } else if (type === 'update') {
            const now = new Date();
            const timeStr = `${now.toLocaleDateString('id-ID')} ${now.getHours()}:${String(now.getMinutes()).padStart(2, '0')}`;
            textToInsert = `\n\n📝 Catatan Update [${timeStr}]:\n- Progres terbaru:`;
        }
        descInput.value = (descInput.value + textToInsert).trim();
        descInput.focus();
    }
</script>
@endpush
