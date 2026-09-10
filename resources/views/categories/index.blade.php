@extends('layouts.app')

@section('title', 'Kategori Tugas')
@section('page_title', 'Kelola Kategori Tugas')

@push('styles')
    
    <link href="/assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
<div class="row g-4">
    
    <div class="col-lg-4">
        <div class="card mb-0">
            <div class="card-header border-bottom">
                <h4 class="header-title mb-0">Tambah Kategori</h4>
                <p class="text-muted fs-13 mb-0">Buat label kategori baru untuk mengelompokkan tugas.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Pekerjaan, Pribadi, Proyek" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">Warna Label</label>
                        <select class="form-select @error('color') is-invalid @enderror" id="color" name="color">
                            <option value="primary" {{ old('color') == 'primary' ? 'selected' : '' }}>Biru (Primary)</option>
                            <option value="success" {{ old('color') == 'success' ? 'selected' : '' }}>Hijau (Success)</option>
                            <option value="info" {{ old('color') == 'info' ? 'selected' : '' }}>Biru Muda (Info)</option>
                            <option value="warning" {{ old('color') == 'warning' ? 'selected' : '' }}>Kuning (Warning)</option>
                            <option value="danger" {{ old('color') == 'danger' ? 'selected' : '' }}>Merah (Danger)</option>
                            <option value="secondary" {{ old('color') == 'secondary' ? 'selected' : '' }}>Abu-abu (Secondary)</option>
                        </select>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Keterangan singkat kategori...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-add-line me-1"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="card mb-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">Daftar Kategori</h4>
                <span class="badge bg-primary-subtle text-primary">{{ $categories->count() }} Kategori</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="categories-datatable" class="table table-hover table-centered mb-0 align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th>Tugas Terkait</th>
                                <th>Deskripsi</th>
                                <th class="text-center" style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $index => $cat)
                                <tr>
                                    <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-{{ $cat->color }}-subtle text-{{ $cat->color }} border border-{{ $cat->color }}-subtle fs-12 px-2 py-1">
                                            <i class="ri-price-tag-3-fill me-1"></i>{{ $cat->name }}
                                        </span>
                                    </td>
                                    <td><code>{{ $cat->slug }}</code></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $cat->tasks_count }} Tugas
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $cat->description ?: '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $cat->id }}" title="Ubah">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCatModal{{ $cat->id }}" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>

                                        
                                        <div class="modal fade text-start" id="editModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ubah Kategori</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('categories.update', $cat) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="name" value="{{ $cat->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Warna Label</label>
                                                                <select class="form-select" name="color">
                                                                    <option value="primary" {{ $cat->color == 'primary' ? 'selected' : '' }}>Biru (Primary)</option>
                                                                    <option value="success" {{ $cat->color == 'success' ? 'selected' : '' }}>Hijau (Success)</option>
                                                                    <option value="info" {{ $cat->color == 'info' ? 'selected' : '' }}>Biru Muda (Info)</option>
                                                                    <option value="warning" {{ $cat->color == 'warning' ? 'selected' : '' }}>Kuning (Warning)</option>
                                                                    <option value="danger" {{ $cat->color == 'danger' ? 'selected' : '' }}>Merah (Danger)</option>
                                                                    <option value="secondary" {{ $cat->color == 'secondary' ? 'selected' : '' }}>Abu-abu (Secondary)</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Deskripsi</label>
                                                                <textarea class="form-control" name="description" rows="3">{{ $cat->description }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="modal fade text-start" id="deleteCatModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content text-center p-4">
                                                    <i class="ri-alert-line text-danger fs-48 d-block mb-2"></i>
                                                    <h5 class="modal-title mb-2">Hapus Kategori?</h5>
                                                    <p class="text-muted fs-13 mb-3">Kategori <strong>"{{ $cat->name }}"</strong> akan dihapus. Tugas di dalamnya akan menjadi tanpa kategori.</p>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('categories.destroy', $cat) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada kategori yang dibuat.</td>
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
    
    <script src="/assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#categories-datatable').DataTable({
                language: {
                    search: "Cari Kategori:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ kategori",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    zeroRecords: "Tidak ada kategori yang cocok",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                },
                pageLength: 10,
                responsive: true
            });
        });
    </script>
@endpush
