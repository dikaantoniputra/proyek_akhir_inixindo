@extends('layouts.app')

@section('title', 'Papan Kanban Tugas')
@section('page_title', 'Papan Kanban Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tugas</a></li>
    <li class="breadcrumb-item active">Papan Kanban</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-body p-2">
        <form method="GET" action="{{ route('tasks.kanban') }}" class="row g-2 align-items-center">
            <div class="col-lg-4 col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari tugas..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-2 col-6">
                <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Prioritas</option>
                    <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="sedang" {{ request('priority') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="rendah" {{ request('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 d-flex gap-1">
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm flex-fill" title="Tampilan Tabel">
                    <i class="ri-table-line me-1"></i> Tabel
                </a>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm flex-fill" title="Tambah Tugas">
                    <i class="ri-add-line me-1"></i> Tambah
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row">

    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card h-100 mb-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                <h5 class="header-title my-0 fs-14 text-uppercase">
                    Belum Dimulai ({{ $todoTasks->count() }})
                </h5>
                <a href="{{ route('tasks.create', ['status' => 'belum dimulai']) }}" class="text-muted" title="Tambah Tugas di Kolom Ini">
                    <i class="ri-add-line fs-18"></i>
                </a>
            </div>
            <div class="card-body p-2 kanban-dropzone" data-status="belum dimulai" style="min-height: 450px; max-height: 75vh; overflow-y: auto;">
                @forelse($todoTasks as $task)
                    @include('tasks.partials.kanban-card', ['task' => $task])
                @empty
                    <div class="text-center text-muted py-5 empty-placeholder">
                        <i class="ri-inbox-line fs-32 d-block mb-1 text-secondary opacity-50"></i>
                        <p class="fs-13 mb-0">Belum ada tugas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card h-100 mb-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                <h5 class="header-title my-0 fs-14 text-uppercase text-primary">
                    Sedang Dikerjakan ({{ $inProgressTasks->count() }})
                </h5>
                <a href="{{ route('tasks.create', ['status' => 'dikerjakan']) }}" class="text-muted" title="Tambah Tugas di Kolom Ini">
                    <i class="ri-add-line fs-18"></i>
                </a>
            </div>
            <div class="card-body p-2 kanban-dropzone" data-status="dikerjakan" style="min-height: 450px; max-height: 75vh; overflow-y: auto;">
                @forelse($inProgressTasks as $task)
                    @include('tasks.partials.kanban-card', ['task' => $task])
                @empty
                    <div class="text-center text-muted py-5 empty-placeholder">
                        <i class="ri-loader-2-line fs-32 d-block mb-1 text-primary opacity-50"></i>
                        <p class="fs-13 mb-0">Tidak ada tugas aktif</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card h-100 mb-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                <h5 class="header-title my-0 fs-14 text-uppercase text-success">
                    Selesai ({{ $doneTasks->count() }})
                </h5>
            </div>
            <div class="card-body p-2 kanban-dropzone" data-status="selesai" style="min-height: 450px; max-height: 75vh; overflow-y: auto;">
                @forelse($doneTasks as $task)
                    @include('tasks.partials.kanban-card', ['task' => $task])
                @empty
                    <div class="text-center text-muted py-5 empty-placeholder">
                        <i class="ri-checkbox-circle-line fs-32 d-block mb-1 text-success opacity-50"></i>
                        <p class="fs-13 mb-0">Belum ada tugas selesai</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.kanban-card-item');
        const dropzones = document.querySelectorAll('.kanban-dropzone');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let draggedCard = null;

        cards.forEach(card => {
            card.addEventListener('dragstart', (e) => {
                draggedCard = card;
                card.classList.add('opacity-50');
                e.dataTransfer.setData('text/plain', card.dataset.taskId);
            });

            card.addEventListener('dragend', () => {
                draggedCard = null;
                card.classList.remove('opacity-50');
                dropzones.forEach(zone => zone.classList.remove('bg-primary-subtle'));
            });
        });

        dropzones.forEach(zone => {
            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.classList.add('bg-primary-subtle');
            });

            zone.addEventListener('dragleave', () => {
                zone.classList.remove('bg-primary-subtle');
            });

            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.classList.remove('bg-primary-subtle');

                if (!draggedCard) return;

                const targetStatus = zone.dataset.status;
                const taskId = draggedCard.dataset.taskId;

                const placeholder = zone.querySelector('.empty-placeholder');
                if (placeholder) placeholder.style.display = 'none';

                zone.appendChild(draggedCard);

                fetch(`/tasks/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: targetStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error('Gagal memindahkan kartu:', err);
                    window.location.reload();
                });
            });
        });
    });
</script>
@endpush
