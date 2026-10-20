<x-layout>
    <x-slot:title>{{ $title ?? 'Upload Dokumen Proyek' }}</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <!-- Header Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-gradient-primary bg-opacity-10 border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-file-upload text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 text-gradient text-primary">Upload Dokumen Proyek</h4>
                                    <p class="text-sm text-muted mb-0">
                                        <i class="fas fa-project-diagram me-1"></i>
                                        {{ $proyek->nama_proyek }}
                                    </p>
                                    <p class="text-xs text-muted mb-0">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $proyek->kantor->nama_kantor }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('dokumen.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Form Upload -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-upload text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark">Form Upload Dokumen</h6>
                                        <small class="text-muted">Lengkapi form berikut untuk mengupload dokumen</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('proyek.dokumen.store', $proyek) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                                    @csrf

                                    <!-- Kontrak Selection -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kontrak_id" class="form-label fw-semibold">
                                                    <i class="fas fa-file-contract me-1 text-primary"></i>
                                                    Pilih Kontrak/Bagian <span class="text-danger">*</span>
                                                </label>
                                                <select name="kontrak_id" id="kontrak_id" class="form-select form-select-lg" required>
                                                    <option value="">-- Pilih Kontrak --</option>
                                                    @foreach($proyek->kontraks as $kontrak)
                                                        <option value="{{ $kontrak->id }}" {{ old('kontrak_id') == $kontrak->id ? 'selected' : '' }}>
                                                            {{ $kontrak->bagian }} - {{ $kontrak->mitra }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('kontrak_id')
                                                    <div class="text-danger text-sm mt-2">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jenis_dokumen" class="form-label fw-semibold">
                                                    <i class="fas fa-tag me-1 text-primary"></i>
                                                    Jenis Dokumen <span class="text-danger">*</span>
                                                </label>
                                                <select name="jenis_dokumen" id="jenis_dokumen" class="form-select form-select-lg" required>
                                                    <option value="">-- Pilih Jenis Dokumen --</option>
                                                    <option value="bast_i" {{ old('jenis_dokumen') == 'bast_i' ? 'selected' : '' }}>
                                                        📄 BAST I (Berita Acara Serah Terima I)
                                                    </option>
                                                    <option value="bast_ii" {{ old('jenis_dokumen') == 'bast_ii' ? 'selected' : '' }}>
                                                        📑 BAST II (Berita Acara Serah Terima II)
                                                    </option>
                                                    <option value="kwitansi" {{ old('jenis_dokumen') == 'kwitansi' ? 'selected' : '' }}>
                                                        🧾 Kwitansi Pembayaran
                                                    </option>
                                                    <option value="spp" {{ old('jenis_dokumen') == 'spp' ? 'selected' : '' }}>
                                                        📋 SPP (Surat Permohonan Pembayaran)
                                                    </option>
                                                </select>
                                                @error('jenis_dokumen')
                                                    <div class="text-danger text-sm mt-2">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Upload -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-file me-1 text-primary"></i>
                                            File Dokumen <span class="text-danger">*</span>
                                        </label>
                                        <div class="file-upload-area border-2 border-dashed rounded-3 p-5 text-center bg-light">
                                            <div class="file-upload-placeholder">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted mb-2">Drag & Drop file di sini</h5>
                                                <p class="text-muted small mb-3">atau</p>
                                                <input type="file" name="dokumen" id="dokumen" class="d-none"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <label for="dokumen" class="btn btn-primary px-4">
                                                    <i class="fas fa-folder-open me-2"></i>Pilih File
                                                </label>
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Format: PDF, JPG, PNG | Maksimal: 10MB
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="file-upload-preview d-none">
                                                <div class="d-flex align-items-center justify-content-between bg-white p-3 rounded border">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-pdf text-danger fa-2x me-3"></i>
                                                        <div class="text-start">
                                                            <h6 class="mb-1 text-dark" id="fileName">Nama file akan muncul di sini</h6>
                                                            <small class="text-muted" id="fileSize">-</small>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @error('dokumen')
                                            <div class="text-danger text-sm mt-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="form-group mb-4">
                                        <label for="keterangan" class="form-label fw-semibold">
                                            <i class="fas fa-sticky-note me-1 text-primary"></i>
                                            Keterangan <span class="text-muted small">(Opsional)</span>
                                        </label>
                                        <textarea name="keterangan" id="keterangan" class="form-control" rows="4"
                                                  placeholder="Tambahkan keterangan atau catatan penting mengenai dokumen ini...">{{ old('keterangan') }}</textarea>
                                        <div class="form-text">
                                            <i class="fas fa-lightbulb text-warning me-1"></i>
                                            Keterangan akan membantu dalam identifikasi dokumen
                                        </div>
                                        @error('keterangan')
                                            <div class="text-danger text-sm mt-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Confirmation & Submit -->
                                    <div class="card bg-light border-0 mt-4">
                                        <div class="card-body">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="confirmUpload" required>
                                                <label class="form-check-label fw-semibold" for="confirmUpload">
                                                    <i class="fas fa-shield-check me-2 text-success"></i>
                                                    Saya telah memeriksa dan menyatakan bahwa dokumen yang diupload sudah benar
                                                </label>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-times me-2"></i>Batal
                                                </a>
                                                <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" disabled>
                                                    <i class="fas fa-upload me-2"></i> Upload Dokumen
                                                    <span class="spinner-border spinner-border-sm d-none ms-2" id="loadingSpinner"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Information -->
                    <div class="col-lg-4">
                        <!-- Status Dokumen -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-clipboard-check text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark">Status Dokumen Proyek</h6>
                                        <small class="text-muted">Kelengkapan dokumen saat ini</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @php
                                    $dokumenStatus = $proyek->dokumen_status;
                                    $totalDokumen = count($dokumenStatus);
                                    $dokumenUploaded = count(array_filter($dokumenStatus));
                                    $progressPercentage = ($dokumenUploaded/$totalDokumen)*100;
                                @endphp

                                <!-- Progress Bar -->
                                <div class="progress-wrapper mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-sm fw-semibold">Progress Kelengkapan</span>
                                        <span class="text-sm fw-bold text-primary">{{ $dokumenUploaded }}/{{ $totalDokumen }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-gradient-info" role="progressbar"
                                             style="width: {{ $progressPercentage }}%;"
                                             aria-valuenow="{{ $progressPercentage }}"
                                             aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">{{ number_format($progressPercentage, 1) }}% lengkap</small>
                                    </div>
                                </div>

                                <!-- Status List -->
                                <div class="status-list">
                                    @foreach($dokumenStatus as $jenis => $status)
                                        <div class="status-item d-flex justify-content-between align-items-center p-3 border rounded mb-2 {{ $status ? 'border-success bg-success bg-opacity-5' : 'border-warning bg-warning bg-opacity-5' }}">
                                            <div class="d-flex align-items-center">
                                                <div class="status-icon me-3">
                                                    @if($jenis == 'bast_i')
                                                        <i class="fas fa-file-signature {{ $status ? 'text-success' : 'text-warning' }}"></i>
                                                    @elseif($jenis == 'bast_ii')
                                                        <i class="fas fa-file-contract {{ $status ? 'text-success' : 'text-warning' }}"></i>
                                                    @elseif($jenis == 'kwitansi')
                                                        <i class="fas fa-receipt {{ $status ? 'text-success' : 'text-warning' }}"></i>
                                                    @elseif($jenis == 'spp')
                                                        <i class="fas fa-envelope-open-text {{ $status ? 'text-success' : 'text-warning' }}"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="fw-semibold d-block">
                                                        @if($jenis == 'bast_i') BAST I
                                                        @elseif($jenis == 'bast_ii') BAST II
                                                        @elseif($jenis == 'kwitansi') Kwitansi
                                                        @elseif($jenis == 'spp') SPP
                                                        @endif
                                                    </span>
                                                    <small class="text-muted">Dokumen {{ $status ? 'tersedia' : 'belum diupload' }}</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-{{ $status ? 'success' : 'warning' }} rounded-pill">
                                                <i class="fas {{ $status ? 'fa-check' : 'fa-clock' }} me-1"></i>
                                                {{ $status ? 'Lengkap' : 'Menunggu' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                @if($proyek->dokumen_lengkap)
                                    <div class="alert alert-success border-0 mt-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle fa-lg me-3"></i>
                                            <div>
                                                <h6 class="alert-heading mb-1">Semua Dokumen Lengkap!</h6>
                                                <small class="mb-0">Proyek telah memenuhi semua persyaratan dokumen.</small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info border-0 mt-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle fa-lg me-3"></i>
                                            <div>
                                                <h6 class="alert-heading mb-1">Perhatian</h6>
                                                <small class="mb-0">Pastikan semua dokumen diupload untuk kelengkapan administrasi proyek.</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tips & Guidelines -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-lightbulb text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark">Panduan Upload</h6>
                                        <small class="text-muted">Tips untuk upload yang optimal</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tips-list">
                                    <div class="tip-item d-flex align-items-start mb-3">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                        <div>
                                            <small class="fw-semibold d-block">Format File</small>
                                            <small class="text-muted">Gunakan format PDF untuk dokumen resmi</small>
                                        </div>
                                    </div>
                                    <div class="tip-item d-flex align-items-start mb-3">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                        <div>
                                            <small class="fw-semibold d-block">Kualitas File</small>
                                            <small class="text-muted">Pastikan file jelas dan mudah dibaca</small>
                                        </div>
                                    </div>
                                    <div class="tip-item d-flex align-items-start mb-3">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                        <div>
                                            <small class="fw-semibold d-block">Ukuran File</small>
                                            <small class="text-muted">Maksimal 10MB per file</small>
                                        </div>
                                    </div>
                                    <div class="tip-item d-flex align-items-start">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                        <div>
                                            <small class="fw-semibold d-block">Verifikasi</small>
                                            <small class="text-muted">Periksa kembali sebelum mengupload</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('dokumen');
            const fileUploadArea = document.querySelector('.file-upload-area');
            const fileUploadPlaceholder = document.querySelector('.file-upload-placeholder');
            const fileUploadPreview = document.querySelector('.file-upload-preview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const confirmCheckbox = document.getElementById('confirmUpload');
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Drag and drop functionality
            fileUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-primary', 'bg-primary bg-opacity-5');
            });

            fileUploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-primary', 'bg-primary bg-opacity-5');
            });

            fileUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-primary', 'bg-primary bg-opacity-5');

                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelection(e.dataTransfer.files[0]);
                }
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                if (this.files.length) {
                    handleFileSelection(this.files[0]);
                }
            });

            // Handle file selection
            function handleFileSelection(file) {
                if (file) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

                    fileName.textContent = file.name;
                    fileSize.textContent = `${fileSizeMB} MB`;

                    fileUploadPlaceholder.classList.add('d-none');
                    fileUploadPreview.classList.remove('d-none');
                }
            }

            // Clear file selection
            window.clearFile = function() {
                fileInput.value = '';
                fileUploadPlaceholder.classList.remove('d-none');
                fileUploadPreview.classList.add('d-none');
            }

            // Enable/disable submit button
            confirmCheckbox.addEventListener('change', function() {
                submitBtn.disabled = !this.checked;
            });

            // Form submission
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                const file = fileInput.files[0];

                if (file) {
                    const fileSizeMB = file.size / (1024 * 1024);
                    if (fileSizeMB > 10) {
                        e.preventDefault();
                        showAlert('error', 'Ukuran file melebihi 10MB. Silakan pilih file yang lebih kecil.');
                        return false;
                    }
                }

                // Show loading
                submitBtn.disabled = true;
                loadingSpinner.classList.remove('d-none');
            });

            function showAlert(type, message) {
                // Simple alert implementation - you can replace with SweetAlert or similar
                alert(message);
            }
        });
    </script>

    <style>
        .card {
            border-radius: 0.75rem;
        }

        .card-header {
            border-bottom: 1px solid #e3e6f0;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .file-upload-area {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: #007bff !important;
            background-color: #f8f9fa !important;
        }

        .status-item {
            transition: all 0.3s ease;
        }

        .status-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .form-select, .form-control {
            border-radius: 0.5rem;
            border: 1px solid #d1d3e2;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .progress {
            border-radius: 0.5rem;
        }

        .progress-bar {
            border-radius: 0.5rem;
        }

        .text-gradient {
            background: linear-gradient(45deg, #007bff, #0056b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 768px) {
            .file-upload-area {
                padding: 2rem 1rem !important;
            }

            .card-body {
                padding: 1rem;
            }

            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
        }

        .bg-opacity-5 {
            background-color: rgba(var(--bs-primary-rgb), 0.05) !important;
        }

        .bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }
    </style>
</x-layout>
