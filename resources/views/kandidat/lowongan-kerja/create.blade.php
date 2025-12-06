@extends('layouts.app')

@push('custom-css')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    --info-gradient: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -80px;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.page-header::after {
    content: '';
    position: absolute;
    bottom: -100px;
    right: 150px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.page-header h2 {
    font-weight: 700;
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
}

.page-header p {
    opacity: 0.9;
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

.page-header-icon {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 6rem;
    opacity: 0.15;
}

/* Upload Card */
.upload-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.upload-card .card-header {
    background: transparent;
    border-bottom: 2px solid #f4f6f9;
    padding: 25px 30px;
}

.upload-card .card-header h4 {
    margin: 0;
    font-weight: 700;
    color: #34395e;
    display: flex;
    align-items: center;
    gap: 12px;
}

.upload-card .card-header h4 i {
    color: #667eea;
    font-size: 1.3rem;
}

.upload-card .card-body {
    padding: 30px;
}

/* Dropzone */
.dropzone-wrapper {
    position: relative;
}

.dropzone {
    border: 3px dashed #d0d0e8;
    border-radius: 20px;
    padding: 60px 40px;
    text-align: center;
    background: linear-gradient(135deg, #fafbff 0%, #f5f6ff 100%);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.dropzone::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.dropzone:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, #f0f2ff 0%, #e8ebff 100%);
}

.dropzone.dragover {
    border-color: #667eea;
    border-style: solid;
    background: linear-gradient(135deg, #e8ebff 0%, #dde1ff 100%);
    transform: scale(1.02);
}

.dropzone.dragover::before {
    opacity: 0.05;
}

.dropzone-content {
    position: relative;
    z-index: 1;
}

.dropzone-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 25px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.dropzone:hover .dropzone-icon {
    transform: scale(1.1);
    box-shadow: 0 20px 45px rgba(102, 126, 234, 0.4);
}

.dropzone-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #34395e;
    margin-bottom: 10px;
}

.dropzone-subtitle {
    color: #6c757d;
    margin-bottom: 20px;
}

.dropzone-browse {
    display: inline-block;
    padding: 12px 30px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.dropzone-browse:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.dropzone-info {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px dashed #d0d0e8;
}

.dropzone-info-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0 15px;
    font-size: 0.85rem;
    color: #6c757d;
}

.dropzone-info-item i {
    color: #667eea;
}

/* File Input Hidden */
.file-input {
    display: none;
}

/* File Preview */
.file-preview {
    display: none;
    margin-top: 25px;
}

.file-preview.show {
    display: block;
    animation: fadeInUp 0.4s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.preview-card {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    border: 2px solid #e0e4ff;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.preview-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.preview-icon.pdf {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%);
    color: white;
}

.preview-icon.doc {
    background: linear-gradient(135deg, #4dabf7 0%, #339af0 100%);
    color: white;
}

.preview-icon.image {
    background: linear-gradient(135deg, #69db7c 0%, #51cf66 100%);
    color: white;
}

.preview-info {
    flex: 1;
    min-width: 0;
}

.preview-info h6 {
    margin: 0 0 5px 0;
    font-weight: 700;
    color: #34395e;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-info small {
    color: #6c757d;
}

.preview-status {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #28a745;
    font-weight: 600;
    font-size: 0.9rem;
}

.preview-status i {
    font-size: 1.2rem;
}

.preview-actions {
    display: flex;
    gap: 10px;
}

.preview-actions .btn {
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 0.85rem;
}

/* Progress Bar */
.upload-progress {
    display: none;
    margin-top: 20px;
}

.upload-progress.show {
    display: block;
}

.progress-wrapper {
    background: #e9ecef;
    border-radius: 10px;
    height: 12px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-bar-custom {
    height: 100%;
    background: var(--primary-gradient);
    border-radius: 10px;
    transition: width 0.3s ease;
    position: relative;
    overflow: hidden;
}

.progress-bar-custom::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255,255,255,0.3),
        transparent
    );
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.progress-text {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: #6c757d;
}

/* Tips Card */
.tips-card {
    background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
    border: 2px solid #ffd699;
    border-radius: 15px;
    padding: 25px;
}

.tips-card h5 {
    color: #e67700;
    font-weight: 700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.tips-card ul {
    margin: 0;
    padding-left: 20px;
}

.tips-card li {
    color: #666;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.tips-card li:last-child {
    margin-bottom: 0;
}

/* Current CV */
.current-cv-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    padding: 25px;
    margin-bottom: 25px;
}

.current-cv-card h6 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.current-cv-card h6 i {
    color: #28a745;
}

.current-cv-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
}

.current-cv-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.current-cv-info {
    flex: 1;
}

.current-cv-info h6 {
    margin: 0 0 3px 0;
    font-size: 0.95rem;
}

.current-cv-info small {
    color: #6c757d;
}

.current-cv-actions a {
    color: #667eea;
    margin-left: 10px;
    font-size: 1.1rem;
    transition: all 0.2s ease;
}

.current-cv-actions a:hover {
    color: #764ba2;
    transform: scale(1.1);
}

/* Submit Section */
.submit-section {
    background: white;
    border-radius: 15px;
    padding: 25px 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.submit-info {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #6c757d;
    font-size: 0.9rem;
}

.submit-info i {
    color: #667eea;
    font-size: 1.2rem;
}

.submit-buttons {
    display: flex;
    gap: 12px;
}

.btn-submit {
    padding: 12px 35px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-submit.btn-primary {
    background: var(--primary-gradient);
    border: none;
}

.btn-submit.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.btn-submit.btn-secondary {
    background: #f0f0f0;
    border: none;
    color: #666;
}

.btn-submit.btn-secondary:hover {
    background: #e0e0e0;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 25px;
        text-align: center;
    }

    .page-header-icon {
        display: none;
    }

    .dropzone {
        padding: 40px 20px;
    }

    .dropzone-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }

    .dropzone-title {
        font-size: 1.2rem;
    }

    .dropzone-info-item {
        display: block;
        margin: 10px 0;
    }

    .preview-card {
        flex-direction: column;
        text-align: center;
    }

    .preview-actions {
        margin-top: 15px;
    }

    .submit-section {
        flex-direction: column;
        text-align: center;
    }

    .submit-buttons {
        width: 100%;
        flex-direction: column;
    }

    .btn-submit {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Upload CV</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
            <div class="breadcrumb-item">Upload CV</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Page Header -->
        <div class="page-header">
            <i class="fas fa-file-upload page-header-icon"></i>
            <h2><i class="fas fa-cloud-upload-alt mr-2"></i> Upload Curriculum Vitae</h2>
            <p>Upload CV terbaru Anda untuk melamar pekerjaan. Pastikan CV Anda up-to-date dan profesional.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Upload Card -->
                <div class="upload-card">
                    <div class="card-header">
                        <h4><i class="fas fa-upload"></i> Form Upload</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cv.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            
                            <!-- Dropzone -->
                            <div class="dropzone-wrapper">
                                <div class="dropzone" id="dropzone">
                                    <div class="dropzone-content">
                                        <div class="dropzone-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <h4 class="dropzone-title">Drag & Drop File CV Anda</h4>
                                        <p class="dropzone-subtitle">atau klik untuk memilih file dari komputer</p>
                                        <span class="dropzone-browse">
                                            <i class="fas fa-folder-open mr-2"></i> Pilih File
                                        </span>
                                        <div class="dropzone-info">
                                            <span class="dropzone-info-item">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </span>
                                            <span class="dropzone-info-item">
                                                <i class="fas fa-file-word"></i> DOC/DOCX
                                            </span>
                                            <span class="dropzone-info-item">
                                                <i class="fas fa-weight-hanging"></i> Maks. 5MB
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="cv" id="fileInput" class="file-input" accept=".pdf,.doc,.docx">
                            </div>

                            <!-- File Preview -->
                            <div class="file-preview" id="filePreview">
                                <div class="preview-card">
                                    <div class="preview-icon pdf" id="previewIcon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="preview-info">
                                        <h6 id="fileName">document.pdf</h6>
                                        <small id="fileSize">2.5 MB</small>
                                    </div>
                                    <div class="preview-status">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Siap diupload</span>
                                    </div>
                                    <div class="preview-actions">
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="removeFile">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Progress -->
                            <div class="upload-progress" id="uploadProgress">
                                <div class="progress-wrapper">
                                    <div class="progress-bar-custom" id="progressBar" style="width: 0%"></div>
                                </div>
                                <div class="progress-text">
                                    <span>Mengupload...</span>
                                    <span id="progressPercent">0%</span>
                                </div>
                            </div>

                            @error('cv')
                                <div class="alert alert-danger mt-3">
                                    <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
                                </div>
                            @enderror
                        </form>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="submit-section">
                    <div class="submit-info">
                        <i class="fas fa-info-circle"></i>
                        <span>CV Anda akan digunakan untuk semua lamaran pekerjaan</span>
                    </div>
                    <div class="submit-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-submit btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" form="uploadForm" class="btn btn-submit btn-primary" id="submitBtn" disabled>
                            <i class="fas fa-cloud-upload-alt mr-2"></i> Upload CV
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Current CV -->
                @if(isset($currentCV))
                <div class="current-cv-card">
                    <h6><i class="fas fa-file-check"></i> CV Saat Ini</h6>
                    <div class="current-cv-item">
                        <div class="current-cv-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="current-cv-info">
                            <h6>{{ $currentCV->filename ?? 'CV_John_Doe.pdf' }}</h6>
                            <small>Diupload: {{ $currentCV->created_at ?? '01 Des 2025' }}</small>
                        </div>
                        <div class="current-cv-actions">
                            <a href="#" title="Download"><i class="fas fa-download"></i></a>
                            <a href="#" title="Lihat"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                </div>
                @else
                <div class="current-cv-card">
                    <h6><i class="fas fa-file-alt text-warning"></i> CV Saat Ini</h6>
                    <div class="text-center py-4">
                        <i class="fas fa-file-excel text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Anda belum mengupload CV</p>
                    </div>
                </div>
                @endif

                <!-- Tips Card -->
                <div class="tips-card">
                    <h5><i class="fas fa-lightbulb"></i> Tips Membuat CV</h5>
                    <ul>
                        <li>Gunakan format PDF untuk hasil terbaik</li>
                        <li>Pastikan informasi kontak Anda up-to-date</li>
                        <li>Sertakan pengalaman kerja yang relevan</li>
                        <li>Tambahkan skill dan sertifikasi yang dimiliki</li>
                        <li>Gunakan desain yang profesional dan mudah dibaca</li>
                        <li>Periksa kembali ejaan dan tata bahasa</li>
                        <li>Idealnya CV tidak lebih dari 2 halaman</li>
                    </ul>
                </div>
                <br>

                <!-- Accepted Formats -->
                <div class="tips-card" style="background: linear-gradient(135deg, #e8f4fd 0%, #d1e9ff 100%); border-color: #90cdf4;">
                    <h5 style="color: #2b6cb0;"><i class="fas fa-file-alt"></i> Format yang Diterima</h5>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="badge badge-light p-2" style="font-size: 0.85rem;">
                            <i class="fas fa-file-pdf text-danger mr-1"></i> PDF
                        </span>
                        <span class="badge badge-light p-2" style="font-size: 0.85rem;">
                            <i class="fas fa-file-word text-primary mr-1"></i> DOC
                        </span>
                        <span class="badge badge-light p-2" style="font-size: 0.85rem;">
                            <i class="fas fa-file-word text-primary mr-1"></i> DOCX
                        </span>
                    </div>
                    <p class="mt-3 mb-0 text-muted" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle mr-1"></i> Ukuran maksimal file: <strong>5 MB</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom-js')
<script>
$(document).ready(function() {
    const dropzone = $('#dropzone');
    const fileInput = $('#fileInput');
    const filePreview = $('#filePreview');
    const submitBtn = $('#submitBtn');
    const uploadProgress = $('#uploadProgress');
    const progressBar = $('#progressBar');
    const progressPercent = $('#progressPercent');

    // Click to browse
    dropzone.on('click', function() {
        fileInput.click();
    });

    // Drag and drop events
    dropzone.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    dropzone.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    dropzone.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // File input change
    fileInput.on('change', function() {
        if (this.files.length > 0) {
            handleFile(this.files[0]);
        }
    });

    // Handle file
    function handleFile(file) {
        // Validate file type
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format Tidak Didukung',
                text: 'Silakan upload file PDF, DOC, atau DOCX',
            });
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal adalah 5 MB',
            });
            return;
        }

        // Update preview
        $('#fileName').text(file.name);
        $('#fileSize').text(formatFileSize(file.size));

        // Update icon based on file type
        const previewIcon = $('#previewIcon');
        previewIcon.removeClass('pdf doc');
        if (file.type === 'application/pdf') {
            previewIcon.addClass('pdf').html('<i class="fas fa-file-pdf"></i>');
        } else {
            previewIcon.addClass('doc').html('<i class="fas fa-file-word"></i>');
        }

        // Show preview and enable submit
        filePreview.addClass('show');
        submitBtn.prop('disabled', false);

        // Hide dropzone icon animation
        dropzone.find('.dropzone-icon').css('transform', 'scale(0.9)');
    }

    // Remove file
    $('#removeFile').on('click', function(e) {
        e.stopPropagation();
        fileInput.val('');
        filePreview.removeClass('show');
        submitBtn.prop('disabled', true);
        dropzone.find('.dropzone-icon').css('transform', 'scale(1)');
    });

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form submit with progress
    $('#uploadForm').on('submit', function(e) {
        // Show progress bar
        uploadProgress.addClass('show');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Mengupload...');

        // Simulate progress (for demo - remove in production with real AJAX)
        let progress = 0;
        const interval = setInterval(function() {
            progress += Math.random() * 30;
            if (progress > 100) progress = 100;
            
            progressBar.css('width', progress + '%');
            progressPercent.text(Math.round(progress) + '%');

            if (progress >= 100) {
                clearInterval(interval);
            }
        }, 200);
    });
});
</script>
@endpush