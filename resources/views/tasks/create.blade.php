@extends('layouts.app')

@section('title', 'Tambah Tugas Baru')
@section('page_title', 'Tambah Tugas Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Daftar Tugas</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card mb-0">
            <div class="card-header border-bottom">
                <h4 class="header-title mb-0">Formulir Tambah Tugas</h4>
                <p class="text-muted fs-13 mb-0">Masukkan informasi rincian tugas yang ingin dikerjakan.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <!-- Judul Tugas -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Menyelesaikan laporan keuangan triwulan" required autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="form-text">Maksimal 255 karakter.</div>
                        @enderror
                    </div>

                    <!-- Deskripsi Tugas -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi / Catatan Tugas</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Tuliskan catatan detail atau instruksi pelaksanaan tugas...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Prioritas -->
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="" disabled {{ old('priority') ? '' : 'selected' }}>-- Pilih Prioritas --</option>
                                <option value="rendah" {{ old('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="sedang" {{ old('priority', 'sedang') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="tinggi" {{ old('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi (Mendesak)</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status Awal <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="belum dimulai" {{ old('status', 'belum dimulai') == 'belum dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                                <option value="dikerjakan" {{ old('status') == 'dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('tasks.index') }}" class="btn btn-light">
                            <i class="ri-arrow-left-line me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Simpan Tugas
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
