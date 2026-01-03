@extends('dashboard.main')

@section('title', 'Tambah Kelas Baru')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Kelas</a></li>
            <li class="breadcrumb-item active">Tambah Baru</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="modern-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Kelas Baru
                    </h5>
                    <p class="mb-0 opacity-75 small">Lengkapi informasi kelas di bawah ini</p>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>Terjadi Kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kelas.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul Kelas <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" id="judul" 
                                           class="form-control @error('judul') is-invalid @enderror" 
                                           placeholder="Contoh: Belajar Web Development"
                                           value="{{ old('judul') }}" required>
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select name="id_kategori" id="id_kategori" 
                                                class="form-select @error('id_kategori') is-invalid @enderror" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}" {{ old('id_kategori') == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_kategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id_vendor" class="form-label">Vendor <span class="text-danger">*</span></label>
                                        <select name="id_vendor" id="id_vendor" 
                                                class="form-select @error('id_vendor') is-invalid @enderror" required>
                                            <option value="">Pilih Vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->id }}" {{ old('id_vendor') == $vendor->id ? 'selected' : '' }}>
                                                    {{ $vendor->vendor }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_vendor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label for="kuota" class="form-label">Kuota Peserta <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                                            <input type="number" name="kuota" id="kuota" 
                                                   class="form-control @error('kuota') is-invalid @enderror" 
                                                   placeholder="50" min="1"
                                                   value="{{ old('kuota') }}" required>
                                        </div>
                                        @error('kuota')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pelaksanaan" class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                            <input type="date" name="pelaksanaan" id="pelaksanaan" 
                                                   class="form-control @error('pelaksanaan') is-invalid @enderror"
                                                   value="{{ old('pelaksanaan') }}" required>
                                        </div>
                                        @error('pelaksanaan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Kelas</label>
                                    <textarea name="deskripsi" id="deskripsi" rows="5" 
                                              class="form-control @error('deskripsi') is-invalid @enderror" 
                                              placeholder="Jelaskan tentang kelas ini, apa yang akan dipelajari, dll.">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-currency-dollar me-2"></i>Harga & Media
                                </h6>

                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga Kelas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="harga" id="harga" 
                                               class="form-control @error('harga') is-invalid @enderror" 
                                               placeholder="250000" min="0"
                                               value="{{ old('harga') }}" required>
                                    </div>
                                    @error('harga')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Masukkan 0 untuk kelas gratis</small>
                                </div>

                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto Cover <span class="text-danger">*</span></label>
                                    <div class="upload-area" onclick="document.getElementById('foto').click()">
                                        <input type="file" name="foto" id="foto" accept="image/*" 
                                               class="d-none @error('foto') is-invalid @enderror"
                                               onchange="previewImage(this)" required>
                                        <div class="upload-placeholder" id="upload-placeholder">
                                            <i class="bi bi-cloud-arrow-up display-4 text-muted"></i>
                                            <p class="mb-0 text-muted">Klik untuk upload gambar</p>
                                            <small class="text-muted">JPG, PNG, SVG (maks. 2MB)</small>
                                        </div>
                                        <img id="preview-image" class="d-none" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                                    </div>
                                    @error('foto')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Preview Card -->
                                <div class="preview-card mt-4">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-eye me-2"></i>Preview</h6>
                                    <div class="preview-content">
                                        <div class="preview-image">
                                            <img id="card-preview-image" src="https://via.placeholder.com/300x160?text=Preview" alt="Preview">
                                        </div>
                                        <div class="preview-body">
                                            <span class="preview-category" id="preview-category">Kategori</span>
                                            <h6 class="preview-title" id="preview-title">Judul Kelas</h6>
                                            <div class="preview-price" id="preview-price">Rp 0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="bi bi-check-lg me-2"></i>Simpan Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-header.bg-gradient-primary {
    background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
    padding: 1.5rem;
}

.upload-area {
    border: 2px dashed var(--border-color, #dee2e6);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: var(--bg-light, #f8f9fa);
}

.upload-area:hover {
    border-color: var(--primary, #4154f1);
    background: rgba(65, 84, 241, 0.05);
}

.preview-card {
    background: var(--bg-light, #f8f9fa);
    border-radius: 12px;
    padding: 1rem;
}

.preview-content {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.preview-image {
    height: 100px;
    overflow: hidden;
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-body {
    padding: 1rem;
}

.preview-category {
    font-size: 0.7rem;
    color: var(--primary, #4154f1);
    text-transform: uppercase;
    font-weight: 600;
}

.preview-title {
    font-size: 0.9rem;
    margin: 0.25rem 0;
    color: var(--text-primary, #1e293b);
}

.preview-price {
    font-weight: 700;
    color: var(--primary, #4154f1);
    font-size: 0.9rem;
}
</style>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-image').classList.remove('d-none');
            document.getElementById('upload-placeholder').classList.add('d-none');
            document.getElementById('card-preview-image').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Live preview updates
document.getElementById('judul').addEventListener('input', function() {
    document.getElementById('preview-title').textContent = this.value || 'Judul Kelas';
});

document.getElementById('harga').addEventListener('input', function() {
    const harga = parseInt(this.value) || 0;
    document.getElementById('preview-price').textContent = 'Rp ' + harga.toLocaleString('id-ID');
});

document.getElementById('id_kategori').addEventListener('change', function() {
    const selectedText = this.options[this.selectedIndex].text;
    document.getElementById('preview-category').textContent = selectedText || 'Kategori';
});
</script>
@endpush
