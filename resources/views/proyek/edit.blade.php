<x-layout>
    <x-slot:title>{{ $title ?? 'Edit Proyek' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-warning text-dark">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title m-0">
                            <i class="bi bi-pencil-square me-2"></i> Edit Proyek
                        </h3>
                        <p class="text-dark mb-0 mt-1 small">
                            <i class="bi bi-info-circle me-1"></i>
                            Perbarui informasi proyek dengan data yang terbaru
                        </p>
                    </div>
                    <div class="badge bg-light text-warning fs-6 p-2">
                        <i class="bi bi-clock me-1"></i>
                        Dibuat: {{ $proyek->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <h6 class="alert-heading mb-1">Terjadi Kesalahan dalam Pengisian Form</h6>
                        </div>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Info Proyek Saat Ini -->
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <h6 class="alert-heading mb-2">
                        <i class="bi bi-clock-history me-2"></i>Informasi Proyek Saat Ini
                    </h6>
                    <div class="row small">
                        <div class="col-md-4">
                            <strong>Kantor:</strong> {{ $proyek->kantor->nama_kantor }}
                        </div>
                        <div class="col-md-4">
                            <strong>Nama Proyek:</strong> {{ $proyek->nama_proyek }}
                        </div>
                        <div class="col-md-4">
                            <strong>Terakhir Update:</strong> {{ $proyek->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Form Edit Proyek -->
                <form action="{{ route('proyek.update', $proyek->id) }}" method="POST" enctype="multipart/form-data" id="proyekForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Informasi Kantor & Proyek -->
                        <div class="col-12">
                            <div class="section-header bg-light rounded-3 p-3 mb-3">
                                <h5 class="mb-0 text-warning">
                                    <i class="bi bi-building me-2"></i>Informasi Kantor & Proyek
                                </h5>
                                <small class="text-muted">Data utama yang mengidentifikasi proyek</small>
                            </div>
                        </div>

                        <!-- Pilih Kantor -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Kantor Penanggung Jawab <span class="text-danger">*</span>
                                <i class="bi bi-question-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="Pilih kantor yang bertanggung jawab atas proyek ini"></i>
                            </label>
                            <select name="kantor_id" class="form-select select2" required id="kantorSelect">
                                <option value="">-- Cari atau Pilih Kantor --</option>
                                @foreach($kantors as $k)
                                    <option value="{{ $k->id }}"
                                        {{ $k->id == $proyek->kantor_id ? 'selected' : '' }}
                                        data-alamat="{{ $k->alamat }}">
                                        {{ $k->nama_kantor }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="bi bi-lightbulb me-1"></i> Gunakan pencarian untuk menemukan kantor dengan cepat
                            </div>

                            <!-- Info Kantor Terpilih -->
                            <div class="card mt-2 bg-light border-0" id="kantorInfo">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2">
                                        <i class="bi bi-info-circle me-1 text-warning"></i>Informasi Kantor Terpilih
                                    </h6>
                                    <div class="row small g-2">
                                        <div class="col-12">
                                            <strong>Alamat:</strong> <span id="infoAlamat">{{ $proyek->kantor->alamat ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Proyek -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Nama Proyek <span class="text-danger">*</span>
                                <i class="bi bi-question-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="Nama resmi proyek yang akan dikerjakan"></i>
                            </label>
                            <input type="text" name="nama_proyek" value="{{ old('nama_proyek', $proyek->nama_proyek) }}"
                                   class="form-control"
                                   placeholder="Contoh: Pembangunan Gedung Kantor Pusat, Renovasi Ruang Meeting, dll."
                                   required
                                   id="namaProyekInput">
                            <div class="form-text">
                                <span id="namaProyekCount">{{ strlen(old('nama_proyek', $proyek->nama_proyek)) }}</span>/255 karakter
                            </div>
                        </div>

                        <!-- Informasi Izin & Dokumen -->
                        <div class="col-12 mt-4">
                            <div class="section-header bg-light rounded-3 p-3 mb-3">
                                <h5 class="mb-0 text-warning">
                                    <i class="bi bi-file-earmark-text me-2"></i>Informasi Izin & Dokumen
                                </h5>
                                <small class="text-muted">Dokumen pendukung dan perizinan proyek</small>
                            </div>
                        </div>

                        <!-- Izin Prinsip -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Nomor Surat Izin Prinsip
                                <span class="text-danger">*</span>
                                <i class="bi bi-question-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="Nomor surat izin prinsip jika sudah tersedia"></i>
                            </label>
                            <input type="text" name="izin_prinsip" value="{{ old('izin_prinsip', $proyek->izin_prinsip) }}"
                                   class="form-control"
                                   placeholder="Contoh: SDI-"
                                   id="izinPrinsipInput" required>
                            <div class="form-text">
                                Format: Nomor surat izin resmi dari instansi terkait
                            </div>
                        </div>

                        <!-- Upload File Surat Izin -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                File Surat Izin Prinsip
                                <span class="text-muted small">(Opsional)</span>
                                <i class="bi bi-question-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="Upload file PDF surat izin prinsip baru untuk mengganti file saat ini"></i>
                            </label>
                            <div class="file-upload-area">
                                <input type="file" name="izin_prinsip_file"
                                       accept="application/pdf"
                                       class="form-control"
                                       id="izinFileInput">

                                <!-- File Saat Ini -->
                                @if($proyek->izin_prinsip_file)
                                <div class="current-file mt-2">
                                    <div class="alert alert-success border-0 rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i>
                                            <div class="flex-grow-1">
                                                <strong>File Saat Ini:</strong>
                                                <a href="{{ route('proyek.download-izin', $proyek->id) }}"
                                                target="_blank" class="text-decoration-none ms-1 btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>Lihat Dokumen
                                                </a>
                                                <span class="text-muted small ms-2">
                                                    ({{ \Carbon\Carbon::parse($proyek->updated_at)->format('d/m/Y H:i') }})
                                                </span>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="hapus_file" id="hapusFile" value="1">
                                                <label class="form-check-label small text-danger" for="hapusFile">
                                                    <i class="bi bi-trash me-1"></i>Hapus file
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning border-0 rounded-3 mt-2">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Belum ada file surat izin</strong>
                                </div>
                                @endif

                                <!-- File Preview Baru -->
                                <div class="file-preview mt-2 d-none" id="filePreview">
                                    <div class="alert alert-info border-0 rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-pdf text-primary me-2 fs-5"></i>
                                            <div>
                                                <strong>File Baru:</strong>
                                                <span class="fw-semibold ms-1" id="fileName">-</span>
                                                <br>
                                                <small class="text-muted" id="fileSize">-</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="removeFile">
                                                <i class="bi bi-x"></i> Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                @if($proyek->izin_prinsip_file)
                                    Biarkan kosong untuk mempertahankan file saat ini
                                @else
                                    Upload file PDF surat izin prinsip (maksimal 2MB)
                                @endif
                            </div>
                        </div>

                        <!-- Deskripsi Proyek -->
                        <div class="col-12 mt-2">
                            <label class="form-label fw-semibold">
                                Deskripsi Proyek
                                <span class="text-muted small">(Opsional)</span>
                                <i class="bi bi-question-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="Penjelasan detail tentang proyek yang akan dikerjakan"></i>
                            </label>
                            <textarea name="deskripsi" class="form-control" rows="5"
                                      placeholder="Jelaskan secara detail tentang proyek ini, termasuk tujuan, ruang lingkup, dan informasi penting lainnya..."
                                      id="deskripsiInput">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                            <div class="form-text">
                                <span id="deskripsiCount">{{ strlen(old('deskripsi', $proyek->deskripsi)) }}</span>/1000 karakter
                            </div>
                        </div>

                        <!-- Preview Informasi -->
                        <div class="col-12 mt-4">
                            <div class="section-header bg-light rounded-3 p-3 mb-3">
                                <h5 class="mb-0 text-warning">
                                    <i class="bi bi-eye me-2"></i>Preview Perubahan
                                </h5>
                                <small class="text-muted">Ringkasan data proyek yang akan diupdate</small>
                            </div>

                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="row g-3" id="previewInfo">
                                        <div class="col-md-6">
                                            <strong>Kantor:</strong> <span id="previewKantor" class="text-success fw-semibold">{{ $proyek->kantor->nama_kantor }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Nama Proyek:</strong> <span id="previewNama" class="text-success fw-semibold">{{ $proyek->nama_proyek }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Izin Prinsip:</strong> <span id="previewIzin" class="{{ $proyek->izin_prinsip ? 'text-success fw-semibold' : 'text-muted' }}">{{ $proyek->izin_prinsip ?: '-' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>File Izin:</strong> <span id="previewFile" class="{{ $proyek->izin_prinsip_file ? 'text-success fw-semibold' : 'text-muted' }}">
                                                {{ $proyek->izin_prinsip_file ? 'File tersedia' : 'Tidak ada' }}
                                            </span>
                                        </div>
                                        <div class="col-12">
                                            <strong>Deskripsi:</strong>
                                            <div id="previewDeskripsi" class="{{ $proyek->deskripsi ? 'text-success small' : 'text-muted small' }} mt-1">
                                                {{ $proyek->deskripsi ?: '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('proyek.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Daftar
                        </a>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Perubahan
                            </button>
                            <button type="submit" class="btn btn-warning btn-lg px-4" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i> Update Proyek
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- jQuery + Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Elements
            const kantorSelect = document.getElementById("kantorSelect");
            const namaProyekInput = document.getElementById("namaProyekInput");
            const izinPrinsipInput = document.getElementById("izinPrinsipInput");
            const izinFileInput = document.getElementById("izinFileInput");
            const deskripsiInput = document.getElementById("deskripsiInput");
            const filePreview = document.getElementById("filePreview");
            const removeFileBtn = document.getElementById("removeFile");
            const hapusFileCheckbox = document.getElementById("hapusFile");

            // Initialize Select2
            $('.select2').select2({
                placeholder: "-- Cari atau Pilih Kantor --",
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5'
            });

            // Update kantor info
            function updateKantorInfo() {
                const selectedValue = kantorSelect.value;

                if (selectedValue) {
                    const selectedOption = kantorSelect.querySelector(`option[value="${selectedValue}"]`);

                    if (selectedOption) {
                        const alamat = selectedOption.getAttribute('data-alamat');
                        const kantorNama = selectedOption.textContent;

                        // Update info display
                        document.getElementById('infoAlamat').textContent = alamat || '-';

                        // Update preview
                        document.getElementById('previewKantor').textContent = kantorNama;
                        document.getElementById('previewKantor').className = 'text-success fw-semibold';
                    }
                } else {
                    document.getElementById('previewKantor').textContent = 'Belum dipilih';
                    document.getElementById('previewKantor').className = 'text-muted';
                }
            }

            // Character counters and preview
            function updateCharacterCounts() {
                // Nama proyek counter
                const namaCount = namaProyekInput.value.length;
                document.getElementById('namaProyekCount').textContent = namaCount;
                document.getElementById('previewNama').textContent = namaProyekInput.value || 'Belum diisi';
                document.getElementById('previewNama').className = namaProyekInput.value ? 'text-success fw-semibold' : 'text-muted';

                // Deskripsi counter
                const deskripsiCount = deskripsiInput.value.length;
                document.getElementById('deskripsiCount').textContent = deskripsiCount;
                document.getElementById('previewDeskripsi').textContent = deskripsiInput.value || '-';
                document.getElementById('previewDeskripsi').className = deskripsiInput.value ? 'text-success small' : 'text-muted small';

                // Izin prinsip preview
                document.getElementById('previewIzin').textContent = izinPrinsipInput.value || '-';
                document.getElementById('previewIzin').className = izinPrinsipInput.value ? 'text-success fw-semibold' : 'text-muted';
            }

            // File upload handling
            function handleFileUpload(file) {
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);

                    document.getElementById('fileName').textContent = fileName;
                    document.getElementById('fileSize').textContent = fileSize + ' MB';
                    filePreview.classList.remove('d-none');

                    document.getElementById('previewFile').textContent = fileName + ' (baru)';
                    document.getElementById('previewFile').className = 'text-primary fw-semibold';

                    // Nonaktifkan checkbox hapus file jika ada file baru
                    if (hapusFileCheckbox) {
                        hapusFileCheckbox.checked = false;
                        hapusFileCheckbox.disabled = true;
                    }
                }
            }

            // Event Listeners
            $(document).on('change', '#kantorSelect', function() {
                updateKantorInfo();
            });

            if (kantorSelect) {
                kantorSelect.addEventListener("change", updateKantorInfo);
            }

            if (namaProyekInput) {
                namaProyekInput.addEventListener("input", updateCharacterCounts);
            }

            if (izinPrinsipInput) {
                izinPrinsipInput.addEventListener("input", updateCharacterCounts);
            }

            if (deskripsiInput) {
                deskripsiInput.addEventListener("input", updateCharacterCounts);
            }

            if (izinFileInput) {
                izinFileInput.addEventListener("change", function(e) {
                    if (this.files.length > 0) {
                        handleFileUpload(this.files[0]);
                    } else {
                        filePreview.classList.add('d-none');
                        // Reset preview file ke kondisi semula
                        if ({{ $proyek->izin_prinsip_file ? 'true' : 'false' }}) {
                            document.getElementById('previewFile').textContent = 'File tersedia';
                            document.getElementById('previewFile').className = 'text-success fw-semibold';
                        } else {
                            document.getElementById('previewFile').textContent = 'Tidak ada';
                            document.getElementById('previewFile').className = 'text-muted';
                        }

                        // Aktifkan kembali checkbox hapus file
                        if (hapusFileCheckbox) {
                            hapusFileCheckbox.disabled = false;
                        }
                    }
                });
            }

            if (removeFileBtn) {
                removeFileBtn.addEventListener("click", function() {
                    izinFileInput.value = '';
                    filePreview.classList.add('d-none');

                    // Reset preview file ke kondisi semula
                    if ({{ $proyek->izin_prinsip_file ? 'true' : 'false' }}) {
                        document.getElementById('previewFile').textContent = 'File tersedia';
                        document.getElementById('previewFile').className = 'text-success fw-semibold';
                    } else {
                        document.getElementById('previewFile').textContent = 'Tidak ada';
                        document.getElementById('previewFile').className = 'text-muted';
                    }

                    // Aktifkan kembali checkbox hapus file
                    if (hapusFileCheckbox) {
                        hapusFileCheckbox.disabled = false;
                    }
                });
            }

            if (hapusFileCheckbox) {
                hapusFileCheckbox.addEventListener("change", function() {
                    if (this.checked) {
                        document.getElementById('previewFile').textContent = 'File akan dihapus';
                        document.getElementById('previewFile').className = 'text-danger fw-semibold';

                        // Nonaktifkan input file jika dicentang hapus
                        izinFileInput.disabled = true;
                    } else {
                        // Reset ke kondisi file saat ini
                        if ({{ $proyek->izin_prinsip_file ? 'true' : 'false' }}) {
                            document.getElementById('previewFile').textContent = 'File tersedia';
                            document.getElementById('previewFile').className = 'text-success fw-semibold';
                        } else {
                            document.getElementById('previewFile').textContent = 'Tidak ada';
                            document.getElementById('previewFile').className = 'text-muted';
                        }

                        // Aktifkan kembali input file
                        izinFileInput.disabled = false;
                    }
                });
            }

            // Form submission loading state
            document.getElementById('proyekForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memperbarui...';
                submitBtn.disabled = true;
            });

            // Reset form handler
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                setTimeout(() => {
                    $('.select2').val('{{ $proyek->kantor_id }}').trigger('change');
                    updateKantorInfo();
                    updateCharacterCounts();
                    filePreview.classList.add('d-none');

                    // Reset file input dan checkbox
                    izinFileInput.value = '';
                    izinFileInput.disabled = false;

                    if (hapusFileCheckbox) {
                        hapusFileCheckbox.checked = false;
                        hapusFileCheckbox.disabled = false;
                    }

                    // Reset preview file
                    if ({{ $proyek->izin_prinsip_file ? 'true' : 'false' }}) {
                        document.getElementById('previewFile').textContent = 'File tersedia';
                        document.getElementById('previewFile').className = 'text-success fw-semibold';
                    } else {
                        document.getElementById('previewFile').textContent = 'Tidak ada';
                        document.getElementById('previewFile').className = 'text-muted';
                    }
                }, 100);
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize on load
            setTimeout(() => {
                updateKantorInfo();
                updateCharacterCounts();
            }, 500);
        });
    </script>

    <style>
        /* Section Headers */
        .section-header {
            border-left: 4px solid #ffc107;
        }

        /* Select2 Styling */
        .select2-container .select2-selection--single {
            height: 50px;
            padding: 8px 16px;
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .select2-container .select2-selection--single:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
            font-weight: 500;
            font-size: 1rem;
            line-height: 1.5;
        }

        .select2-dropdown {
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #dee2e6;
        }

        .select2-results__option--highlighted {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        /* File Upload Area */
        .file-upload-area {
            position: relative;
        }

        .file-preview {
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 12px;
        }

        .current-file .alert {
            border-radius: 0.5rem;
        }

        /* Form Styling */
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .form-label {
            color: #495057;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .btn-lg {
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 0.75rem;
        }

        .alert {
            border-radius: 0.75rem;
        }

        /* Preview Card */
        .card.bg-light {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 4px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .progress-steps {
                flex-direction: column;
                gap: 1rem;
            }

            .progress-steps::before {
                display: none;
            }

            .step {
                flex-direction: row;
                gap: 1rem;
            }

            .step-number {
                margin-bottom: 0;
            }
        }
    </style>
</x-layout>
