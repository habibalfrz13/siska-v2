@extends('dashboard.main')

@section('title', 'Edit Materi - ' . $material->title)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Kelas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.modules.index', $kelas->id) }}">{{ Str::limit($kelas->judul, 15) }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.materials.index', $module->id) }}">{{ Str::limit($module->title, 15) }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-pencil me-2 text-warning"></i>Edit Materi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Materi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $material->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe Materi <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="video" {{ old('type', $material->type) == 'video' ? 'selected' : '' }}>Video (YouTube/URL)</option>
                                <option value="text" {{ old('type', $material->type) == 'text' ? 'selected' : '' }}>Teks/Artikel</option>
                                <option value="file" {{ old('type', $material->type) == 'file' ? 'selected' : '' }}>File Download</option>
                                <option value="link" {{ old('type', $material->type) == 'link' ? 'selected' : '' }}>Link Eksternal</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dynamic Content Fields -->
                        <div id="video-fields" class="type-field d-none">
                            <div class="mb-3">
                                <label for="video_url" class="form-label">URL Video</label>
                                <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                       id="video_url" name="video_url" value="{{ old('video_url', $material->video_url) }}"
                                       placeholder="https://youtube.com/watch?v=...">
                                <small class="text-muted">Mendukung YouTube dan URL video langsung</small>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="text-fields" class="type-field d-none">
                            <div class="mb-3">
                                <label for="content" class="form-label">Konten Materi</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="10">{{ old('content', $material->content) }}</textarea>
                                <small class="text-muted">Mendukung HTML untuk formatting</small>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="file-fields" class="type-field d-none">
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File Baru</label>
                                @if($material->file_path)
                                    <div class="alert alert-info mb-2">
                                        <i class="bi bi-file-earmark me-2"></i>
                                        File saat ini: <strong>{{ basename($material->file_path) }}</strong>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                       id="file" name="file">
                                <small class="text-muted">Maksimal 50MB. Kosongkan jika tidak ingin mengubah file</small>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="link-fields" class="type-field d-none">
                            <div class="mb-3">
                                <label for="video_url_link" class="form-label">URL Link</label>
                                <input type="url" class="form-control" 
                                       id="video_url_link" name="video_url" value="{{ old('video_url', $material->video_url) }}"
                                       placeholder="https://example.com/resource">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="duration" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" name="duration" value="{{ old('duration', $material->duration) }}" min="0">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="order" class="form-label">Urutan</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', $material->order) }}" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_published" 
                                           name="is_published" value="1" {{ old('is_published', $material->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">Publish</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.materials.index', $module->id) }}" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const typeFields = document.querySelectorAll('.type-field');
    
    function showTypeFields() {
        const selectedType = typeSelect.value;
        
        typeFields.forEach(field => {
            field.classList.add('d-none');
        });
        
        if (selectedType === 'video') {
            document.getElementById('video-fields').classList.remove('d-none');
        } else if (selectedType === 'text') {
            document.getElementById('text-fields').classList.remove('d-none');
        } else if (selectedType === 'file') {
            document.getElementById('file-fields').classList.remove('d-none');
        } else if (selectedType === 'link') {
            document.getElementById('link-fields').classList.remove('d-none');
        }
    }
    
    typeSelect.addEventListener('change', showTypeFields);
    showTypeFields(); // Initial check
});
</script>
@endpush
