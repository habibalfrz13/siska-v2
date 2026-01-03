@extends('dashboard.main')

@section('title', 'Kelola Modul - ' . $kelas->judul)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Kelas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kelas.show', $kelas->id) }}">{{ Str::limit($kelas->judul, 30) }}</a></li>
            <li class="breadcrumb-item active">Modul</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Modul</h4>
            <p class="text-muted mb-0">{{ $kelas->judul }}</p>
        </div>
        <a href="{{ route('admin.modules.create', $kelas->id) }}" class="btn btn-gradient-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Modul
        </a>
    </div>

    <!-- Modules List -->
    <div class="modern-card">
        <div class="card-body">
            @if($modules->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Modul</h5>
                    <p class="text-muted">Klik tombol "Tambah Modul" untuk membuat modul pertama.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">Urutan</th>
                                <th>Judul Modul</th>
                                <th width="100">Materi</th>
                                <th width="100">Status</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $module->order }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $module->title }}</div>
                                    @if($module->description)
                                        <small class="text-muted">{{ Str::limit($module->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $module->materials->count() }} materi</span>
                                </td>
                                <td>
                                    @if($module->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.materials.index', $module->id) }}" 
                                           class="btn btn-sm btn-soft-primary" title="Kelola Materi">
                                            <i class="bi bi-collection"></i>
                                        </a>
                                        <a href="{{ route('admin.modules.edit', $module->id) }}" 
                                           class="btn btn-sm btn-soft-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-soft-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $module->id }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $module->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda yakin ingin menghapus modul <strong>{{ $module->title }}</strong>?</p>
                                            <p class="text-danger small">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Semua materi di dalam modul ini juga akan dihapus.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.modules.destroy', $module->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
