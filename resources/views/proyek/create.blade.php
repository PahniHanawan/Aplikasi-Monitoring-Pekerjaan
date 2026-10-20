<x-layout>
    <x-slot:title>{{ $title ?? 'Tambah Proyek Baru' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title m-0">
                            <i class="bi bi-building-add me-2"></i> Tambah Proyek Baru
                        </h3>
                        <p class="text-white-50 mb-0 mt-1 small">
                            <i class="bi bi-info-circle me-1"></i>
                            Lengkapi informasi proyek untuk memulai pengelolaan yang terstruktur
                        </p>
                    </div>
                    <div class="badge bg-light text-primary fs-6 p-2">
                        <i class="bi bi-plus-circle me-1"></i> Data Baru
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Progress Steps -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="progress-steps">
                            <div class="step active">
                                <div class="step-number">1</div>
                                <div class="step-label">Informasi Dasar</div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-label">Dokumen & Izin</div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-label">Konfirmasi</div>
                            </div>
                        </div>
                    </div>
                </div>

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

                <!-- Form Tambah Proyek -->
                <form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data" id="proyekForm">
                    @csrf

                    <div class="row g-4">
                        <!-- Informasi Kantor & Proyek -->
                        <div class="col-12">
                            <div class="section-header bg-light rounded-3 p-3 mb-3">
                                <h5 class="mb-0 text-primary">
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
                                    <option value="{{ $k->id }}" {{ old('kantor_id') == $k->id ? 'selected' : '' }}
                                        data-alamat="{{ $k->alamat }}">
                                        {{ $k->nama_kantor }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="bi bi-lightbulb me-1"></i> Gunakan pencarian untuk menemukan kantor dengan cepat
                            </div>

                            <!-- Info Kantor Terpilih -->
                            <div class="card mt-2 bg-light border-0 d-none" id="kantorInfo">
                                <div class="card-body p-3">
                                    <div class="row small g-2">
                                        <div class="col-12">
                                            <strong>Alamat:</strong> <span id="infoAlamat">-</span>
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
                            <input type="text" name="nama_proyek" value="{{ old('nama_proyek') }}"
                                   class="form-control"
                                   placeholder="Contoh: Pembangunan Gedung Kantor Pusat, Renovasi Ruang Meeting, dll."
                                   required
                                   id="namaProyekInput">
                            <div class="form-text">
                                <span id="namaProyekCount">0</span>/255 karakter
                            </div>
                        </div>

                        <!-- Informasi Izin & Dokumen -->
                        <div class="col-12 mt-4">
                            <div class="section-header bg-light rounded-3 p-3 mb-3">
                                <h5 class="mb-0 text-primary">
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
                            <input type="text" name="izin_prinsip" value="{{ old('izin_prinsip') }}"
                                   class="form-control"
                                   placeholder="Contoh: SDI-"
                                   id="izinPrinsipInput">
                            <div class="form-text">
                                Format: Nomor surat izin resmi dari instansi terkait
                            </div>
                        </div>

                        <!-- Upload File Surat Izin -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                File Surat Izin Prinsip
                                <span class="text-danger">*</span>
                                <i class="bi bi-question-circle text-muted ms-1"
                                   data-bs-toggle="tooltip"
                                   title="Upload file PDF surat izin prinsip"></i>
                            </label>
                            <div class="file-upload-area">
                                <input type="file" name="izin_prinsip_file"
                                       accept="application/pdf"
                                       class="form-control"
                                       id="izinFileInput" required>
                                <div class="file-preview mt-2 d-none" id="filePreview">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i>
                                        <div>
                                            <small class="fw-semibold" id="fileName">-</small>
                                            <br>
                                            <small class="text-muted" id="fileSize">-</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="removeFile">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i> Format: PDF, maksimal 2MB
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
                                      id="deskripsiInput">{{ old('deskripsi') }}</textarea>
                            <div class="form-text">
                                <span id="deskripsiCount">0</span>/1000 karakter
                            </div>
                        </div>

                        <!-- Preview Informasi -->
                        <div class="col-12 mt-4">
                            <div class="section-header bg-light rounded-3 p-3 mb-3">
                                <h5 class="mb-0 text-primary">
                                    <i class="bi bi-eye me-2"></i>Preview Informasi
                                </h5>
                                <small class="text-muted">Ringkasan data proyek yang akan dibuat</small>
                            </div>

                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="row g-3" id="previewInfo">
                                        <div class="col-md-6">
                                            <strong>Kantor:</strong> <span id="previewKantor" class="text-muted">Belum dipilih</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Nama Proyek:</strong> <span id="previewNama" class="text-muted">Belum diisi</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Izin Prinsip:</strong> <span id="previewIzin" class="text-muted">-</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>File Izin:</strong> <span id="previewFile" class="text-muted">Tidak ada</span>
                                        </div>
                                        <div class="col-12">
                                            <strong>Deskripsi:</strong>
                                            <div id="previewDeskripsi" class="text-muted small mt-1">-</div>
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
                                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Form
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i> Simpan Proyek
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        const kantorInfo = document.getElementById("kantorInfo");

        // Initialize Select2
        $('.select2').select2({
            placeholder: "-- Cari atau Pilih Kantor --",
            allowClear: true,
            width: '100%',
            theme: 'bootstrap-5'
        });

        // Update kantor info - FIXED untuk Select2
        function updateKantorInfo() {
            const selectedValue = kantorSelect.value;
            console.log('Selected Kantor Value:', selectedValue); // Debug

            if (selectedValue) {
                // Cari option yang dipilih
                const selectedOption = kantorSelect.querySelector(`option[value="${selectedValue}"]`);
                console.log('Selected Option:', selectedOption); // Debug

                if (selectedOption) {
                    const alamat = selectedOption.getAttribute('data-alamat');
                    const kantorNama = selectedOption.textContent;

                    console.log('Kantor Data:', { kantorNama, alamat }); // Debug

                    // Update info display
                    document.getElementById('infoAlamat').textContent = alamat || '-';
                    kantorInfo.classList.remove('d-none');

                    // Update preview - FIXED
                    document.getElementById('previewKantor').textContent = kantorNama;
                    document.getElementById('previewKantor').className = 'text-success fw-semibold';
                }
            } else {
                kantorInfo.classList.add('d-none');
                document.getElementById('previewKantor').textContent = 'Belum dipilih';
                document.getElementById('previewKantor').className = 'text-muted';
            }
        }

        // Character counters
        function updateCharacterCounts() {
            // Nama proyek counter
            const namaCount = namaProyekInput.value.length;
            document.getElementById('namaProyekCount').textContent = namaCount;
            if (namaCount > 0) {
                document.getElementById('previewNama').textContent = namaProyekInput.value;
                document.getElementById('previewNama').className = 'text-success fw-semibold';
            } else {
                document.getElementById('previewNama').textContent = 'Belum diisi';
                document.getElementById('previewNama').className = 'text-muted';
            }

            // Deskripsi counter
            const deskripsiCount = deskripsiInput.value.length;
            document.getElementById('deskripsiCount').textContent = deskripsiCount;
            if (deskripsiCount > 0) {
                document.getElementById('previewDeskripsi').textContent = deskripsiInput.value;
                document.getElementById('previewDeskripsi').className = 'text-success small';
            } else {
                document.getElementById('previewDeskripsi').textContent = '-';
                document.getElementById('previewDeskripsi').className = 'text-muted small';
            }

            // Izin prinsip preview
            if (izinPrinsipInput.value) {
                document.getElementById('previewIzin').textContent = izinPrinsipInput.value;
                document.getElementById('previewIzin').className = 'text-success fw-semibold';
            } else {
                document.getElementById('previewIzin').textContent = '-';
                document.getElementById('previewIzin').className = 'text-muted';
            }
        }

        // File upload handling
        function handleFileUpload(file) {
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB

                document.getElementById('fileName').textContent = fileName;
                document.getElementById('fileSize').textContent = fileSize + ' MB';
                filePreview.classList.remove('d-none');

                document.getElementById('previewFile').textContent = fileName;
                document.getElementById('previewFile').className = 'text-success fw-semibold';
            }
        }

        // Event Listeners - FIXED untuk Select2
        $(document).on('change', '#kantorSelect', function() {
            console.log('Select2 Change Event Triggered'); // Debug
            updateKantorInfo();
        });

        // Juga tambah event listener native untuk backup
        if (kantorSelect) {
            kantorSelect.addEventListener("change", function() {
                console.log('Native Change Event Triggered'); // Debug
                updateKantorInfo();
            });
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
                }
            });
        }

        if (removeFileBtn) {
            removeFileBtn.addEventListener("click", function() {
                izinFileInput.value = '';
                filePreview.classList.add('d-none');
                document.getElementById('previewFile').textContent = 'Tidak ada';
                document.getElementById('previewFile').className = 'text-muted';
            });
        }

        // Form validation - FIXED untuk Select2
        document.getElementById('proyekForm').addEventListener('submit', function(e) {
            const kantorId = kantorSelect.value;
            const namaProyek = namaProyekInput.value.trim();

            console.log('Form Submit - Kantor ID:', kantorId); // Debug

            if (!kantorId) {
                e.preventDefault();
                alert('Silakan pilih kantor penanggung jawab terlebih dahulu');
                $('.select2').select2('open');
                return;
            }

            if (!namaProyek) {
                e.preventDefault();
                alert('Silakan isi nama proyek terlebih dahulu');
                namaProyekInput.focus();
                return;
            }

            // File validation
            if (izinFileInput.files.length > 0) {
                const file = izinFileInput.files[0];
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    e.preventDefault();
                    alert('Ukuran file maksimal 2MB');
                    return;
                }
                if (file.type !== 'application/pdf') {
                    e.preventDefault();
                    alert('Hanya file PDF yang diizinkan');
                    return;
                }
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });

        // Reset form handler - FIXED untuk Select2
        document.querySelector('button[type="reset"]').addEventListener('click', function() {
            setTimeout(() => {
                // Reset Select2
                $('.select2').val(null).trigger('change');
                updateKantorInfo();
                updateCharacterCounts();
                filePreview.classList.add('d-none');
            }, 100);
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize on load - FIXED untuk Select2
        setTimeout(() => {
            console.log('Initializing...'); // Debug
            updateKantorInfo();
            updateCharacterCounts();

            // Jika ada nilai old, update preview
            const oldKantorId = "{{ old('kantor_id') }}";
            if (oldKantorId) {
                console.log('Found old kantor_id:', oldKantorId); // Debug
                $('.select2').val(oldKantorId).trigger('change');
            }
        }, 500); // Delay sedikit untuk memastikan Select2 sudah terinisialisasi
    });
</script>

    <style>
        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 0.5rem;
            border: 3px solid white;
        }

        .step.active .step-number {
            background-color: #0d6efd;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 500;
        }

        .step.active .step-label {
            color: #0d6efd;
            font-weight: 600;
        }

        /* Section Headers */
        .section-header {
            border-left: 4px solid #0d6efd;
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
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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

        /* Form Styling */
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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
