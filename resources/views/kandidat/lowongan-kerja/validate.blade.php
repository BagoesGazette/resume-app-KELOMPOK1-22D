@extends('layouts.app')

@push('custom-css')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary-color: #667eea;
        --secondary-color: #764ba2;
        --text-dark: #34395e;
        --text-muted: #6c757d;
        --border-color: #e9ecef;
        --shadow-sm: 0 2px 10px rgba(0,0,0,0.05);
        --shadow-md: 0 5px 25px rgba(0,0,0,0.08);
        --shadow-lg: 0 10px 40px rgba(0,0,0,0.1);
    }

    body {
        background-color: #f8f9fc;
    }

    .validation-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Section */
    .validation-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .validation-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .validation-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .validation-header p {
        font-size: 15px;
        line-height: 1.6;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .validation-header .job-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        margin-top: 10px;
    }

    /* Form Section */
    .form-section {
        background: #fff;
        border-radius: 16px;
        padding: 35px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .form-section:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #f4f6f9;
    }

    .section-header i {
        font-size: 24px;
        margin-right: 12px;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .section-header h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        font-size: 20px;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        font-weight: 600;
        color: #555;
        margin-bottom: 10px;
        font-size: 14px;
        display: block;
    }

    .form-group label .required {
        color: #e74c3c;
        margin-left: 3px;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid var(--border-color);
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: #fafbfc;
    }

    .form-control:hover {
        border-color: #d1d5db;
        background-color: #fff;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background-color: #fff;
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-size: 13px;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Help Text */
    .form-text {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: var(--text-muted);
    }

    .form-text i {
        margin-right: 5px;
    }

    /* Input Icons */
    .input-group-icon {
        position: relative;
    }

    .input-group-icon i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .input-group-icon .form-control {
        padding-left: 45px;
    }

    /* Buttons */
    .submit-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f4f6f9;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    /* Skills Input */
    .skills-input-wrapper {
        position: relative;
    }

    .skills-badge-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        min-height: 40px;
        padding: 10px;
        background: #f8f9fc;
        border-radius: 8px;
    }

    .skill-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .validation-container {
            padding: 15px;
        }

        .validation-header {
            padding: 25px;
            margin-bottom: 25px;
        }

        .validation-header h2 {
            font-size: 22px;
        }

        .validation-header p {
            font-size: 14px;
        }

        .form-section {
            padding: 25px 20px;
        }

        .section-header h5 {
            font-size: 18px;
        }

        .submit-buttons {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
        }
    }

    /* Loading State */
    .btn-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spinner 0.6s linear infinite;
    }

    @keyframes spinner {
        to { transform: rotate(360deg); }
    }

    /* Character Counter */
    .char-counter {
        font-size: 12px;
        color: var(--text-muted);
        float: right;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-body">
        <div class="validation-container">
            <!-- Header -->
            <div class="validation-header">
                <h2>
                    <i class="fas fa-user-check"></i>
                    Konfirmasi & Validasi Lamaran
                </h2>
                <p class="mb-3">
                    Harap periksa kembali data yang diekstrak dari CV Anda. Anda dapat mengedit informasi di bawah ini sebelum mengirimkan lamaran.
                </p>
                <div class="job-badge">
                    <i class="fas fa-briefcase"></i>
                    {{ $jobOpening->posisi }}
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('lamaran.submit', $application->id) }}" method="POST" id="applicationForm">
                @csrf

                <!-- Rangkuman Profil -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-user-tie"></i>
                        <h5>Rangkuman Profil</h5>
                    </div>
                    <div class="form-group">
                        <label for="rangkuman_profil">
                            Profil Singkat
                            <span class="text-muted">(Ceritakan tentang diri Anda)</span>
                        </label>
                        <textarea 
                            name="rangkuman_profil" 
                            id="rangkuman_profil" 
                            class="form-control" 
                            rows="4"
                            placeholder="Contoh: Saya adalah profesional IT dengan pengalaman 5 tahun di bidang web development..."
                            maxlength="1000">{{ old('rangkuman_profil', $application->rangkuman_profil) }}</textarea>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Tuliskan ringkasan singkat tentang latar belakang dan keahlian Anda
                        </small>
                    </div>
                </div>

                <!-- Pendidikan -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-graduation-cap"></i>
                        <h5>Pendidikan</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="pendidikan_terakhir">
                                    Pendidikan Terakhir
                                </label>
                                <input 
                                    type="text" 
                                    name="pendidikan_terakhir" 
                                    id="pendidikan_terakhir" 
                                    class="form-control" 
                                    value="{{ old('pendidikan_terakhir', $application->pendidikan_terakhir) }}"
                                    placeholder="Contoh: S1 Teknik Informatika - Universitas Indonesia">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ipk_nilai_akhir">
                                    IPK / Nilai Akhir
                                </label>
                                <input 
                                    type="text" 
                                    name="ipk_nilai_akhir" 
                                    id="ipk_nilai_akhir" 
                                    class="form-control" 
                                    value="{{ old('ipk_nilai_akhir', $application->ipk_nilai_akhir) }}"
                                    placeholder="Contoh: 3.75">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rangkuman_pendidikan">
                            Rangkuman Pendidikan
                            <span class="text-muted">(Opsional)</span>
                        </label>
                        <textarea 
                            name="rangkuman_pendidikan" 
                            id="rangkuman_pendidikan" 
                            class="form-control" 
                            rows="3"
                            placeholder="Contoh: Lulus dengan predikat cum laude, aktif dalam organisasi kampus..."
                            maxlength="500">{{ old('rangkuman_pendidikan', $application->rangkuman_pendidikan) }}</textarea>
                    </div>
                </div>

                <!-- Pengalaman Kerja -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-briefcase"></i>
                        <h5>Pengalaman Kerja</h5>
                    </div>
                    <div class="form-group">
                        <label for="pengalaman_kerja_terakhir">
                            Pengalaman Kerja Terakhir
                        </label>
                        <input 
                            type="text" 
                            name="pengalaman_kerja_terakhir" 
                            id="pengalaman_kerja_terakhir" 
                            class="form-control" 
                            value="{{ old('pengalaman_kerja_terakhir', $application->pengalaman_kerja_terakhir) }}"
                            placeholder="Contoh: Senior Web Developer di PT. Tech Indonesia (2020-2024)">
                    </div>
                    <div class="form-group">
                        <label for="rangkuman_pengalaman_kerja">
                            Rangkuman Pengalaman Kerja
                        </label>
                        <textarea 
                            name="rangkuman_pengalaman_kerja" 
                            id="rangkuman_pengalaman_kerja" 
                            class="form-control" 
                            rows="4"
                            placeholder="Contoh: Bertanggung jawab dalam pengembangan aplikasi web, mengelola tim developer..."
                            maxlength="1000">{{ old('rangkuman_pengalaman_kerja', $application->rangkuman_pengalaman_kerja) }}</textarea>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Jelaskan tanggung jawab dan pencapaian Anda di posisi terakhir
                        </small>
                    </div>
                </div>

                <!-- Skills -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-cogs"></i>
                        <h5>Keahlian (Skills)</h5>
                    </div>
                    <small class="form-text mb-3">
                        <i class="fas fa-lightbulb"></i>
                        Pisahkan setiap keahlian dengan koma (,)
                    </small>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hardskills">
                                    <i class="fas fa-code text-primary"></i>
                                    Hard Skills
                                </label>
                                <input 
                                    type="text" 
                                    name="hardskills" 
                                    id="hardskills" 
                                    class="form-control" 
                                    value="{{ old('hardskills', is_array($application->hardskills) ? implode(', ', $application->hardskills) : $application->hardskills) }}"
                                    placeholder="Contoh: PHP, Laravel, MySQL, JavaScript">
                                <small class="form-text">
                                    Keahlian teknis dan spesifik
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="softskills">
                                    <i class="fas fa-users text-primary"></i>
                                    Soft Skills
                                </label>
                                <input 
                                    type="text" 
                                    name="softskills" 
                                    id="softskills" 
                                    class="form-control" 
                                    value="{{ old('softskills', is_array($application->softskills) ? implode(', ', $application->softskills) : $application->softskills) }}"
                                    placeholder="Contoh: Komunikasi, Teamwork, Problem Solving">
                                <small class="form-text">
                                    Kemampuan interpersonal dan non-teknis
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-plus-circle"></i>
                        <h5>Informasi Tambahan</h5>
                    </div>
                    <div class="form-group">
                        <label for="cover_letter">
                            Cover Letter
                            <span class="text-muted">(Opsional)</span>
                        </label>
                        <textarea 
                            name="cover_letter" 
                            id="cover_letter" 
                            class="form-control" 
                            rows="5"
                            placeholder="Tuliskan surat pengantar singkat yang menjelaskan motivasi Anda melamar posisi ini..."
                            maxlength="2000">{{ old('cover_letter', $application->cover_letter) }}</textarea>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Jelaskan mengapa Anda tertarik dengan posisi ini dan apa yang membuat Anda kandidat yang tepat
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="expected_salary">
                            Ekspektasi Gaji
                            <span class="text-muted">(Opsional)</span>
                        </label>
                        <div class="input-group-icon">
                            <i class="fas fa-money-bill-wave"></i>
                            <input 
                                type="text" 
                                name="expected_salary" 
                                id="expected_salary" 
                                class="form-control" 
                                value="{{ old('expected_salary', $application->expected_salary) }}"
                                placeholder="Contoh: Rp 10.000.000">
                        </div>
                        <small class="form-text">
                            Masukkan range gaji yang Anda harapkan (nominal per bulan)
                        </small>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="submit-buttons">
                    <a href="{{ route('lowongan-kerja.show', $jobOpening->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Lamaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('custom-js')
<script>
$(document).ready(function() {
    let formHasChanged = false;
    const form = $('#applicationForm');
    const submitBtn = $('#submitBtn');

    // Tandai bahwa form telah berubah jika ada input yang diubah
    form.find('input, textarea').on('change keyup', function() {
        formHasChanged = true;
    });

    // Handle form submission
    form.on('submit', function(e) {
        // Hapus peringatan beforeunload
        $(window).off('beforeunload');
        
        // Tambahkan loading state ke button
        submitBtn.addClass('btn-loading');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span>Mengirim...</span>');
    });
    
    // Hapus peringatan saat tombol Batal di-klik
    $('.btn-secondary').on('click', function(e) {
        if (formHasChanged) {
            const confirmed = confirm('Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin membatalkan?');
            if (!confirmed) {
                e.preventDefault();
                return false;
            }
        }
        $(window).off('beforeunload');
    });

    // Tampilkan peringatan jika pengguna mencoba meninggalkan halaman
    $(window).on('beforeunload', function(e) {
        if (formHasChanged) {
            const message = 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
            e.returnValue = message;
            return message;
        }
    });

    // Auto-resize textarea
    $('textarea').each(function() {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Skills preview (optional enhancement)
    function updateSkillsPreview(inputId, previewId) {
        const input = $('#' + inputId);
        const preview = $('#' + previewId);
        
        input.on('input', function() {
            const skills = $(this).val().split(',').map(s => s.trim()).filter(s => s);
            preview.empty();
            
            if (skills.length > 0) {
                skills.forEach(skill => {
                    preview.append(`<span class="skill-badge">${skill}</span>`);
                });
                preview.show();
            } else {
                preview.hide();
            }
        });
    }

    // Smooth scroll to first error (if validation errors exist)
    @if($errors->any())
        $('html, body').animate({
            scrollTop: $('.is-invalid:first').offset().top - 100
        }, 500);
    @endif
});
</script>
@endpush