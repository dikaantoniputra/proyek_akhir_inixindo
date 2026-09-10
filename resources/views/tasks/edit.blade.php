@extends('layouts.app')

@section('title', 'Ubah Tugas')
@section('page_title', 'Ubah Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Daftar Tugas</a></li>
    <li class="breadcrumb-item active">Ubah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card mb-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="header-title mb-0">Formulir Ubah Tugas</h4>
                    <p class="text-muted fs-13 mb-0">Perbarui rincian tugas sesuai perkembangan.</p>
                </div>
                <span class="{{ $task->status_badge_class }} fs-12 px-2 py-1">
                    {{ ucfirst($task->status) }}
                </span>
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Judul Tugas -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $task->title) }}" required autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi Tugas -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi / Catatan Tugas</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Prioritas -->
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="rendah" {{ old('priority', $task->priority) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="sedang" {{ old('priority', $task->priority) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="tinggi" {{ old('priority', $task->priority) == 'tinggi' ? 'selected' : '' }}>Tinggi (Mendesak)</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status Tugas <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="belum dimulai" {{ old('status', $task->status) == 'belum dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                                <option value="dikerjakan" {{ old('status', $task->status) == 'dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="selesai" {{ old('status', $task->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Kategori Tugas</label>
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

                        <!-- Tenggat Waktu -->
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Tenggat Waktu (Deadline)</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-light">
                            <i class="ri-arrow-left-line me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="ri-check-line me-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
