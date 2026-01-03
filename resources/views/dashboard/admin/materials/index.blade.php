@extends('dashboard.main')

@section('title', 'Kelola Materi - ' . $module->title)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Kelas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.modules.index', $kelas->id) }}">{{ Str::limit($kelas->judul, 20) }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($module->title, 20) }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Materi</h4>
            <p class="text-muted mb-0">Modul: {{ $module->title }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.modules.index', $kelas->id) }}" class="btn btn-soft-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('admin.materials.create', $module->id) }}" class="btn btn-gradient-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Materi
            </a>
        </div>
    </div>

    <!-- Materials List -->
    <div class="modern-card">
        <div class="card-body">
            @if($materials->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-collection-play display-1 text-muted mb-3 d-block"></i>
                    <h5>Belum Ada Materi</h5>
                    <p class="text-muted">Klik tombol "Tambah Materi" untuk membuat materi pertama.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Judul Materi</th>
                                <th width="100">Tipe</th>
                                <th width="100">Durasi</th>
                                <th width="100">Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $material->order }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="material-type-icon">
                                            <i class="bi {{ $material->type_icon }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $material->title }}</div>
                                            @if($material->type === 'video' && $material->video_url)
                                                <small class="text-muted">{{ Str::limit($material->video_url, 40) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $material->type_label }}</span>
                                </td>
                                <td>
                                    @if($material->duration)
                                        {{ $material->formatted_duration }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($material->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.materials.edit', $material->id) }}" 
                                           class="btn btn-sm btn-soft-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-soft-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $material->id }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $material->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda yakin ingin menghapus materi <strong>{{ $material->title }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" class="d-inline">
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

<style>
.material-type-icon {
    width: 36px;
    height: 36px;
    background: rgba(65, 84, 241, 0.1);
    color: var(--primary, #4154f1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
</style>
@endsection
