@extends('layouts.app')

@section('title', 'Tambah Tugas Baru')
@section('page_title', 'Tambah Tugas Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Daftar Tugas</a></li>
    <li class="breadcrumb-item active">Tambah Tugas</li>
@endsection

@section('content')
<div class="row">
    
    <div class="col-xl-8 col-lg-7">
        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" id="taskCreateForm">
            @csrf

            
            <div class="card mb-3 shadow-sm border">
                <div class="card-header bg-transparent border-bottom d-flex align-items-center">
                    <div class="avatar-xs bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                        <i class="ri-edit-2-line fs-16"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fs-15">Informasi Tugas</h5>
                        <small class="text-muted">Tuliskan judul dan rincian aktivitas yang akan dikerjakan.</small>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="title" class="form-label fw-semibold mb-0">
                                Judul Tugas <span class="text-danger">*</span>
                            </label>
                            <span class="text-muted fs-11" id="titleCharCount">0 / 255 karakter</span>
                        </div>
                        <input type="text" 
                               class="form-control form-control-lg fs-14 @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               placeholder="Contoh: Menyusun laporan keuangan triwulan & analisis anggaran..." 
                               maxlength="255"
                               required 
                               autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="description" class="form-label fw-semibold mb-0">Deskripsi & Catatan Detail</label>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-xs btn-light border py-0 px-1 text-muted" onclick="insertTemplate('checklist')" title="Sisipkan checklist">
                                    <i class="ri-list-check fs-12 me-1"></i>+ Checklist
                                </button>
                                <button type="button" class="btn btn-xs btn-light border py-0 px-1 text-muted" onclick="insertTemplate('goal')" title="Sisipkan target">
                                    <i class="ri-focus-2-line fs-12 me-1"></i>+ Target
                                </button>
                            </div>
                        </div>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="5" 
                                  placeholder="Jelaskan langkah kerja, target output, atau instruksi pelaksanaan tugas ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="form-text fs-12 text-muted">Gunakan tombol template di atas untuk menyisipkan format cepat.</div>
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
                        <small class="text-muted">Tentukan tingkat urgensi, tahap pengerjaan, dan batas waktu.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        
                        <div class="col-md-6">
                            <label for="priority" class="form-label fw-semibold">
                                <i class="ri-flag-line text-warning me-1"></i> Prioritas <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="rendah" {{ old('priority') == 'rendah' ? 'selected' : '' }}>🟢 Rendah (Dapat ditunda)</option>
                                <option value="sedang" {{ old('priority', 'sedang') == 'sedang' ? 'selected' : '' }}>🟡 Sedang (Standar)</option>
                                <option value="tinggi" {{ old('priority') == 'tinggi' ? 'selected' : '' }}>🔴 Tinggi (Mendesak & Kritis)</option>
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
                                <option value="belum dimulai" {{ old('status', 'belum dimulai') == 'belum dimulai' ? 'selected' : '' }}>⚪ Belum Dimulai (Antrean / To Do)</option>
                                <option value="dikerjakan" {{ old('status') == 'dikerjakan' ? 'selected' : '' }}>🔵 Sedang Dikerjakan (In Progress)</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>🟢 Selesai (Completed)</option>
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
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                            
                            
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
                        <small class="text-muted">Sertakan berkas pendukung seperti instruksi kerja, SOP, atau lembar data.</small>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="border border-2 border-dashed rounded p-3 text-center bg-light" id="dropArea" style="cursor: pointer;">
                        <i class="ri-upload-cloud-2-line fs-36 text-primary mb-1 d-block"></i>
                        <h6 class="fw-semibold mb-1">Klik untuk memilih berkas atau seret ke area ini</h6>
                        <p class="text-muted fs-12 mb-2">Mendukung file dokumen PDF, Word, Excel, PowerPoint, ZIP/RAR, Gambar, dan Teks (Maks. 10 MB)</p>
                        <button type="button" class="btn btn-sm btn-outline-primary px-3" onclick="document.getElementById('attachment').click()">
                            <i class="ri-folder-open-line me-1"></i> Telusuri Komputer
                        </button>
                        <input type="file" 
                               class="d-none" 
                               id="attachment" 
                               name="attachment" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.jpg,.jpeg,.png,.webp,.txt,.csv"
                               onchange="handleFileSelect(this)">
                    </div>

                    
                    <div id="fileInfoBox" class="card border mt-3 mb-0 d-none bg-white">
                        <div class="card-body p-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-primary-subtle text-primary rounded d-flex align-items-center justify-content-center">
                                    <i id="fileIcon" class="ri-file-line fs-20"></i>
                                </div>
                                <div>
                                    <h6 id="fileName" class="my-0 fs-13 text-dark fw-bold text-truncate" style="max-width: 320px;">file_name.pdf</h6>
                                    <small id="fileSize" class="text-muted">0 KB</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSelectedFile()" title="Batalkan Berkas">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </div>

                    @error('attachment')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            
            <div class="card mb-4 shadow-sm border">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <a href="{{ route('tasks.index') }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Batal
                    </a>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-secondary" onclick="resetLivePreview()">
                            <i class="ri-refresh-line me-1"></i> Reset Form
                        </button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="ri-save-line me-1"></i> Simpan Tugas Baru
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    
    <div class="col-xl-4 col-lg-5">
        
        
        <div class="card shadow-sm border mb-3">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fs-14">
                    <i class="ri-eye-line text-primary me-1"></i> Pratinjau Tampilan Tugas
                </h5>
                <span class="badge bg-primary-subtle text-primary fs-11">Live Preview</span>
            </div>
            <div class="card-body">
                <div class="border rounded p-3 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <span id="previewCategory" class="badge bg-secondary-subtle text-secondary fs-11">
                            Tanpa Kategori
                        </span>
                        <div class="d-flex gap-1">
                            <span id="previewPriority" class="badge bg-warning-subtle text-warning border border-warning-subtle fs-11">
                                Sedang
                            </span>
                            <span id="previewStatus" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-11">
                                Belum Dimulai
                            </span>
                        </div>
                    </div>

                    <h5 id="previewTitle" class="text-dark fw-bold mb-2 fs-15">
                        Judul tugas akan muncul di sini...
                    </h5>

                    <p id="previewDesc" class="text-muted fs-13 mb-3 lh-sm text-truncate-2" style="min-height: 38px;">
                        Deskripsi atau catatan detail tugas akan ditampilkan di area ini.
                    </p>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top fs-12 text-muted">
                        <div>
                            <i class="ri-user-line me-1"></i> {{ Auth::user()->name }}
                        </div>
                        <div id="previewDeadline">
                            <i class="ri-calendar-line me-1"></i> Tanpa Deadline
                        </div>
                    </div>
                </div>
                <small class="text-muted d-block text-center mt-2 fs-12">
                    <i class="ri-information-line me-1"></i> Tampilan di atas mencerminkan bagaimana tugas Anda terlihat di daftar tugas.
                </small>
            </div>
        </div>

        
        <div class="card shadow-sm border mb-3">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0 fs-14">
                    <i class="ri-lightbulb-line text-warning me-1"></i> Tips Pengelolaan Tugas Efektif
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 fs-13">
                    <li class="d-flex align-items-start mb-2">
                        <i class="ri-checkbox-circle-fill text-success fs-16 me-2 mt-1"></i>
                        <div><strong>Gunakan Judul Spesifik:</strong> Mulai dengan kata kerja operasional (contoh: <em>Menyusun, Meninjau, Mengirim</em>).</div>
                    </li>
                    <li class="d-flex align-items-start mb-2">
                        <i class="ri-checkbox-circle-fill text-success fs-16 me-2 mt-1"></i>
                        <div><strong>Tetapkan Prioritas Tepat:</strong> Prioritas tinggi khusus untuk tugas mendesak yang memiliki dampak langsung.</div>
                    </li>
                    <li class="d-flex align-items-start mb-2">
                        <i class="ri-checkbox-circle-fill text-success fs-16 me-2 mt-1"></i>
                        <div><strong>Atur Tenggat Realistis:</strong> Berikan batas waktu yang jelas untuk menghindari status terlewat (overdue).</div>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="ri-checkbox-circle-fill text-success fs-16 me-2 mt-1"></i>
                        <div><strong>Lampirkan Referensi:</strong> Unggah file PDF/dokumen acuan agar proses pengerjaan lebih terstruktur.</div>
                    </li>
                </ul>
            </div>
        </div>

        
        <div class="card shadow-sm border">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fs-13 text-muted text-uppercase my-0">Beban Kerja Anda Saat Ini</h6>
                    <span class="badge bg-primary-subtle text-primary">{{ Auth::user()->tasks()->count() }} Total Tugas</span>
                </div>
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="p-2 border rounded bg-light">
                            <h4 class="my-0 text-secondary fw-bold">{{ Auth::user()->tasks()->where('status', 'belum dimulai')->count() }}</h4>
                            <small class="text-muted fs-11">Antrean</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 border rounded bg-light">
                            <h4 class="my-0 text-info fw-bold">{{ Auth::user()->tasks()->where('status', 'dikerjakan')->count() }}</h4>
                            <small class="text-muted fs-11">Dikerjakan</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 border rounded bg-light">
                            <h4 class="my-0 text-success fw-bold">{{ Auth::user()->tasks()->where('status', 'selesai')->count() }}</h4>
                            <small class="text-muted fs-11">Selesai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live Preview & Input Handlers
    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('description');
    const prioritySelect = document.getElementById('priority');
    const statusSelect = document.getElementById('task_status');
    const categorySelect = document.getElementById('category_id');
    const dueDateInput = document.getElementById('due_date');

    const previewTitle = document.getElementById('previewTitle');
    const previewDesc = document.getElementById('previewDesc');
    const previewPriority = document.getElementById('previewPriority');
    const previewStatus = document.getElementById('previewStatus');
    const previewCategory = document.getElementById('previewCategory');
    const previewDeadline = document.getElementById('previewDeadline');
    const titleCharCount = document.getElementById('titleCharCount');

    function updateLivePreview() {
        // Title
        const titleVal = titleInput.value.trim();
        previewTitle.textContent = titleVal || 'Judul tugas akan muncul di sini...';
        titleCharCount.textContent = `${titleInput.value.length} / 255 karakter`;

        // Description
        const descVal = descInput.value.trim();
        previewDesc.textContent = descVal || 'Deskripsi atau catatan detail tugas akan ditampilkan di area ini.';

        // Priority
        const priorityVal = prioritySelect.value;
        if (priorityVal === 'tinggi') {
            previewPriority.className = 'badge bg-danger-subtle text-danger border border-danger-subtle fs-11';
            previewPriority.textContent = 'Tinggi';
        } else if (priorityVal === 'rendah') {
            previewPriority.className = 'badge bg-info-subtle text-info border border-info-subtle fs-11';
            previewPriority.textContent = 'Rendah';
        } else {
            previewPriority.className = 'badge bg-warning-subtle text-warning border border-warning-subtle fs-11';
            previewPriority.textContent = 'Sedang';
        }

        // Status
        const statusVal = statusSelect.value;
        if (statusVal === 'selesai') {
            previewStatus.className = 'badge bg-success-subtle text-success border border-success-subtle fs-11';
            previewStatus.textContent = 'Selesai';
        } else if (statusVal === 'dikerjakan') {
            previewStatus.className = 'badge bg-primary-subtle text-primary border border-primary-subtle fs-11';
            previewStatus.textContent = 'Sedang Dikerjakan';
        } else {
            previewStatus.className = 'badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-11';
            previewStatus.textContent = 'Belum Dimulai';
        }

        // Category
        const selectedCatOption = categorySelect.options[categorySelect.selectedIndex];
        if (selectedCatOption && categorySelect.value) {
            previewCategory.textContent = selectedCatOption.textContent.trim();
            previewCategory.className = 'badge bg-primary-subtle text-primary fs-11';
        } else {
            previewCategory.textContent = 'Tanpa Kategori';
            previewCategory.className = 'badge bg-secondary-subtle text-secondary fs-11';
        }

        // Deadline
        const dateVal = dueDateInput.value;
        if (dateVal) {
            const parts = dateVal.split('-');
            if (parts.length === 3) {
                previewDeadline.innerHTML = `<i class="ri-calendar-check-line text-danger me-1"></i> ${parts[2]}/${parts[1]}/${parts[0]}`;
            }
        } else {
            previewDeadline.innerHTML = '<i class="ri-calendar-line me-1"></i> Tanpa Deadline';
        }
    }

    titleInput.addEventListener('input', updateLivePreview);
    descInput.addEventListener('input', updateLivePreview);
    prioritySelect.addEventListener('change', updateLivePreview);
    statusSelect.addEventListener('change', updateLivePreview);
    categorySelect.addEventListener('change', updateLivePreview);
    dueDateInput.addEventListener('change', updateLivePreview);

    // Initial run
    updateLivePreview();

    // Quick Date Picker Helper
    function setQuickDate(daysToAdd) {
        const d = new Date();
        d.setDate(d.getDate() + daysToAdd);
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        dueDateInput.value = `${yyyy}-${mm}-${dd}`;
        updateLivePreview();
    }

    // Template Inserter Helper
    function insertTemplate(type) {
        let textToInsert = '';
        if (type === 'checklist') {
            textToInsert = "\n\n📌 Checklist Pekerjaan:\n[ ] Langkah 1:\n[ ] Langkah 2:\n[ ] Verifikasi hasil:";
        } else if (type === 'goal') {
            textToInsert = "\n\n🎯 Target & Output:\n- Hasil yang diharapkan:\n- Dokumen terkait:";
        }
        descInput.value = (descInput.value + textToInsert).trim();
        updateLivePreview();
        descInput.focus();
    }

    function resetLivePreview() {
        setTimeout(updateLivePreview, 50);
    }

    // File Upload Drag & Drop & Display
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('attachment');
    const fileInfoBox = document.getElementById('fileInfoBox');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileIcon = document.getElementById('fileIcon');

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('border-primary', 'bg-primary-subtle');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('border-primary', 'bg-primary-subtle');
        }, false);
    });

    dropArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(fileInput);
        }
    });

    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            fileName.textContent = file.name;
            
            // Format size
            const sizeInBytes = file.size;
            if (sizeInBytes >= 1048576) {
                fileSize.textContent = (sizeInBytes / 1048576).toFixed(1) + ' MB';
            } else {
                fileSize.textContent = Math.round(sizeInBytes / 1024) + ' KB';
            }

            // Icon detection
            const ext = file.name.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext)) {
                fileIcon.className = 'ri-image-fill text-info fs-20';
            } else if (['pdf'].includes(ext)) {
                fileIcon.className = 'ri-file-pdf-fill text-danger fs-20';
            } else if (['doc', 'docx'].includes(ext)) {
                fileIcon.className = 'ri-file-word-fill text-primary fs-20';
            } else if (['xls', 'xlsx', 'csv'].includes(ext)) {
                fileIcon.className = 'ri-file-excel-fill text-success fs-20';
            } else if (['zip', 'rar', '7z'].includes(ext)) {
                fileIcon.className = 'ri-file-zip-fill text-warning fs-20';
            } else {
                fileIcon.className = 'ri-file-3-fill text-secondary fs-20';
            }

            fileInfoBox.classList.remove('d-none');
        }
    }

    function clearSelectedFile() {
        fileInput.value = '';
        fileInfoBox.classList.add('d-none');
    }
</script>
@endpush
