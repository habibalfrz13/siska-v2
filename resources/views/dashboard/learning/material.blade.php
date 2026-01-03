@extends('dashboard.main')

@section('title', $material->title)

@section('content')
<div class="material-page">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3 order-lg-2 d-none d-lg-block">
            <div class="material-sidebar">
                <div class="sidebar-header">
                    <a href="{{ route('learn.course', $kelas->id) }}" class="back-link">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Kelas
                    </a>
                </div>
                <div class="sidebar-course-info">
                    <h6 class="course-title">{{ Str::limit($kelas->judul, 40) }}</h6>
                </div>
                <div class="sidebar-modules">
                    @foreach($modulesWithProgress as $moduleData)
                    <div class="sidebar-module">
                        <div class="module-header-mini">
                            {{ $moduleData['module']->title }}
                        </div>
                        <div class="module-materials">
                            @foreach($moduleData['materials'] as $matData)
                            <a href="{{ route('learn.material', $matData['material']->id) }}" 
                               class="sidebar-material {{ $matData['material']->id == $material->id ? 'active' : '' }} {{ $matData['is_completed'] ? 'completed' : '' }}">
                                <i class="bi {{ $matData['material']->type_icon }}"></i>
                                <span>{{ Str::limit($matData['material']->title, 25) }}</span>
                                @if($matData['is_completed'])
                                    <i class="bi bi-check-circle-fill text-success ms-auto"></i>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 order-lg-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('learn.index') }}">Kelas Saya</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('learn.course', $kelas->id) }}">{{ Str::limit($kelas->judul, 20) }}</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($material->title, 30) }}</li>
                </ol>
            </nav>

            <!-- Material Card -->
            <div class="material-content-card">
                <!-- Material Header -->
                <div class="material-header">
                    <div class="material-type-badge">
                        <i class="bi {{ $material->type_icon }} me-1"></i>{{ $material->type_label }}
                    </div>
                    <h4 class="material-title">{{ $material->title }}</h4>
                    @if($material->duration)
                        <span class="material-duration"><i class="bi bi-clock me-1"></i>{{ $material->formatted_duration }}</span>
                    @endif
                </div>

                <!-- Material Content -->
                <div class="material-body">
                    @if($material->type === 'video')
                        <!-- Video Player -->
                        <div class="video-wrapper mb-4">
                            @if(str_contains($material->video_url, 'youtube') || str_contains($material->video_url, 'youtu.be'))
                                @php
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $material->video_url, $matches);
                                    $youtubeId = $matches[1] ?? '';
                                @endphp
                                @if($youtubeId)
                                    <div class="ratio ratio-16x9">
                                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                                title="{{ $material->title }}"
                                                allowfullscreen></iframe>
                                    </div>
                                @endif
                            @else
                                <video controls class="w-100 rounded">
                                    <source src="{{ $material->video_url }}" type="video/mp4">
                                    Browser Anda tidak mendukung video.
                                </video>
                            @endif
                        </div>
                    @endif

                    @if($material->type === 'text' || $material->content)
                        <!-- Text Content -->
                        <div class="text-content">
                            {!! $material->content !!}
                        </div>
                    @endif

                    @if($material->type === 'file' && $material->file_path)
                        <!-- File Download -->
                        <div class="file-download-box">
                            <div class="file-icon">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                            </div>
                            <div class="file-info">
                                <span class="file-name">{{ basename($material->file_path) }}</span>
                                <span class="file-desc">Klik untuk mengunduh file materi</span>
                            </div>
                            <a href="{{ asset('storage/' . $material->file_path) }}" 
                               class="btn btn-primary" download>
                                <i class="bi bi-download me-2"></i>Download
                            </a>
                        </div>
                    @endif

                    @if($material->type === 'link' && $material->video_url)
                        <!-- External Link -->
                        <div class="link-box">
                            <i class="bi bi-link-45deg"></i>
                            <a href="{{ $material->video_url }}" target="_blank" rel="noopener">
                                {{ $material->video_url }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Material Footer -->
                <div class="material-footer">
                    <div class="navigation-buttons">
                        @if($prevMaterial)
                            <a href="{{ route('learn.material', $prevMaterial->id) }}" class="btn btn-soft-secondary">
                                <i class="bi bi-chevron-left me-1"></i>Sebelumnya
                            </a>
                        @else
                            <span></span>
                        @endif

                        <button type="button" 
                                id="mark-complete-btn"
                                class="btn {{ $isCompleted ? 'btn-success' : 'btn-primary' }}"
                                data-material-id="{{ $material->id }}"
                                data-complete-url="{{ route('learn.complete', $material->id) }}"
                                {{ $isCompleted ? 'disabled' : '' }}>
                            @if($isCompleted)
                                <i class="bi bi-check-circle me-2"></i>Sudah Selesai
                            @else
                                <i class="bi bi-check2 me-2"></i>Tandai Selesai
                            @endif
                        </button>

                        @if($nextMaterial)
                            <a href="{{ route('learn.material', $nextMaterial->id) }}" class="btn btn-gradient-primary">
                                Selanjutnya<i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('learn.course', $kelas->id) }}" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Selesai
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.material-page {
    padding-bottom: 2rem;
}

.material-sidebar {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
}

.sidebar-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color, #e9ecef);
}

.back-link {
    color: var(--text-secondary, #5a6169);
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.2s;
}

.back-link:hover {
    color: var(--primary, #4154f1);
}

.sidebar-course-info {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color, #f0f0f0);
}

.sidebar-course-info .course-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin: 0;
    color: var(--text-primary, #1e293b);
}

.sidebar-modules {
    padding: 0.5rem 0;
}

.module-header-mini {
    padding: 0.75rem 1.25rem;
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sidebar-material {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.25rem;
    text-decoration: none;
    color: var(--text-secondary, #5a6169);
    font-size: 0.8rem;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.sidebar-material:hover {
    background: var(--bg-light, #f8f9fa);
    color: var(--primary, #4154f1);
}

.sidebar-material.active {
    background: rgba(65, 84, 241, 0.08);
    color: var(--primary, #4154f1);
    border-left-color: var(--primary, #4154f1);
}

.sidebar-material.completed {
    color: var(--text-muted, #999);
}

.material-content-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.material-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color, #e9ecef);
}

.material-type-badge {
    display: inline-flex;
    align-items: center;
    background: var(--primary, #4154f1);
    color: #fff;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.material-header .material-title {
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    margin-bottom: 0.5rem;
}

.material-header .material-duration {
    color: var(--text-muted, #6c757d);
    font-size: 0.875rem;
}

.material-body {
    padding: 1.5rem;
    min-height: 300px;
}

.video-wrapper {
    border-radius: 12px;
    overflow: hidden;
    background: #000;
}

.text-content {
    color: var(--text-secondary, #5a6169);
    line-height: 1.8;
    font-size: 1rem;
}

.text-content h1, .text-content h2, .text-content h3 {
    color: var(--text-primary, #1e293b);
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.text-content img {
    max-width: 100%;
    border-radius: 8px;
    margin: 1rem 0;
}

.text-content pre {
    background: var(--bg-light, #f8f9fa);
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
}

.file-download-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--bg-light, #f8f9fa);
    border-radius: 12px;
    padding: 1.5rem;
}

.file-download-box .file-icon {
    width: 56px;
    height: 56px;
    background: var(--primary, #4154f1);
    color: #fff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.file-download-box .file-info {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.file-download-box .file-name {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
}

.file-download-box .file-desc {
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

.link-box {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--bg-light, #f8f9fa);
    border-radius: 12px;
    padding: 1rem 1.25rem;
}

.link-box i {
    font-size: 1.25rem;
    color: var(--primary, #4154f1);
}

.link-box a {
    word-break: break-all;
}

.material-footer {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid var(--border-color, #e9ecef);
    background: var(--bg-light, #f8f9fa);
}

.navigation-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .navigation-buttons {
        flex-direction: column;
    }
    
    .navigation-buttons .btn {
        width: 100%;
    }
}

/* Dark mode */
[data-theme="dark"] .material-sidebar,
[data-theme="dark"] .material-content-card {
    background: var(--bg-white);
}

[data-theme="dark"] .file-download-box,
[data-theme="dark"] .link-box,
[data-theme="dark"] .material-footer {
    background: rgba(255,255,255,0.03);
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const markCompleteBtn = document.getElementById('mark-complete-btn');
    
    if (markCompleteBtn && !markCompleteBtn.disabled) {
        markCompleteBtn.addEventListener('click', function() {
            const materialId = this.dataset.materialId;
            const completeUrl = this.dataset.completeUrl;
            const btn = this;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            
            fetch(completeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                    btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Sudah Selesai';
                    
                    // Update sidebar if exists
                    const sidebarItem = document.querySelector(`.sidebar-material[href*="${materialId}"]`);
                    if (sidebarItem) {
                        sidebarItem.classList.add('completed');
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Tandai Selesai';
                    alert(data.message || 'Gagal menandai selesai');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Tandai Selesai';
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
    }
});
</script>
@endpush
