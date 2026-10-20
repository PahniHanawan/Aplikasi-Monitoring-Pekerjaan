<x-layout>
    <x-slot:title>{{ $title ?? 'Edit Realisasi' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-warning text-dark">
                <h3 class="card-title m-0">
                    <i class="bi bi-pencil-square me-2"></i> Edit Realisasi
                </h3>
                <p class="text-muted mb-0 mt-1 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Update progress dan informasi realisasi proyek
                </p>
            </div>

            <div class="card-body">
                <!-- Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <h6 class="alert-heading mb-1">Terjadi Kesalahan</h6>
                        </div>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Info Realisasi Saat Ini -->
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <h6 class="alert-heading mb-2">
                        <i class="bi bi-clock-history me-2"></i>Informasi Realisasi Saat Ini
                    </h6>
                    <div class="row small">
                        <div class="col-md-3">
                            <strong>Progress:</strong> {{ $realisasi->progress_persen }}%
                        </div>
                        <div class="col-md-3">
                            <strong>Nilai Realisasi:</strong> Rp {{ number_format($realisasi->nilai_realisasi, 0, ',', '.') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($realisasi->tanggal_realisasi)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Terakhir Update:</strong> {{ $realisasi->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Form Edit Realisasi -->
                <form action="{{ route('realisasi.update', $realisasi->id) }}" method="POST" id="editRealisasiForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Pilih Kontrak -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold"> Kontrak <span class="text-danger">*</span>
                                <i class="bi bi-question-circle text-muted ms-1" data-bs-toggle="tooltip" title="Pilih kontrak yang akan diupdate realisasinya"></i>
                            </label>
                            <select name="kontrak_id" class="form-select" required id="kontrakSelect">
                                <option value="">-- Pilih Kontrak --</option>
                                @foreach($kontraks as $k)
                                <option value="{{ $k->id }}"
                                    {{ $k->id == $realisasi->kontrak_id ? 'selected' : '' }}
                                    data-nilai="{{ $k->nilai_kontrak }}"
                                    data-proyek="{{ $k->proyek->nama_proyek ?? 'N/A' }}"
                                    data-mitra="{{ $k->mitra }}">
                                    {{ $k->nomor_spk }} - {{ $k->proyek->nama_proyek ?? 'N/A' }} - {{ $k->mitra }} - Rp {{ number_format($k->nilai_kontrak,0,',','.') }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="bi bi-lightbulb me-1"></i> Klik untuk melihat semua pilihan kontrak
                            </div>
                        </div>

                        <!-- Progress dengan Slider -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Progress (%) <span class="text-danger">*</span>
                            </label>

                            <!-- Input Number -->
                            <div class="input-group mb-2">
                                <input type="number" name="progress_persen"
                                       value="{{ old('progress_persen', $realisasi->progress_persen) }}"
                                       class="form-control progress-input"
                                       min="0" max="100" step="0.01"
                                       placeholder="0-100" required
                                       id="progressInput">
                                <span class="input-group-text">%</span>
                            </div>

                            <!-- Slider -->
                            <input type="range" class="form-range progress-slider"
                                   min="0" max="100" step="1"
                                   value="{{ old('progress_persen', $realisasi->progress_persen) }}"
                                   id="progressSlider">

                            <!-- Progress Bar Visual -->
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     style="width: {{ $realisasi->progress_persen }}%"
                                     aria-valuenow="{{ $realisasi->progress_persen }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100"
                                     id="progressBar">
                                </div>
                            </div>

                            <!-- Status Text -->
                            <div class="form-text text-center fw-semibold" id="progressStatus">
                                @php
                                    $progress = $realisasi->progress_persen;
                                    if ($progress == 0) {
                                        echo '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Belum Dimulai</span>';
                                    } elseif ($progress < 100) {
                                        echo '<span class="text-warning"><i class="bi bi-play-circle me-1"></i>Dalam Pengerjaan</span>';
                                    } else {
                                        echo '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Selesai</span>';
                                    }
                                @endphp
                            </div>
                        </div>

                        <!-- Nilai Realisasi -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nilai Realisasi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border">Rp</span>
                                <input type="text"
                                       class="form-control bg-light nilai-realisasi-display"
                                       readonly
                                       id="nilaiRealisasiDisplay"
                                       value="{{ number_format($realisasi->nilai_realisasi, 0, ',', '.') }}">
                                <input type="hidden" name="nilai_realisasi"
                                       value="{{ old('nilai_realisasi', $realisasi->nilai_realisasi) }}"
                                       id="nilaiRealisasiHidden">
                            </div>
                            <div class="form-text">
                                <i class="bi bi-calculator me-1"></i> Nilai otomatis dihitung dari progress dan nilai kontrak
                            </div>
                        </div>

                        <!-- Tanggal Realisasi - DIUBAH -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Tanggal Realisasi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calendar3"></i>
                                </span>
                                <input type="date" name="tanggal_realisasi"
                                       value="{{ old('tanggal_realisasi', \Carbon\Carbon::parse($realisasi->tanggal_realisasi)->format('Y-m-d')) }}"
                                       class="form-control tanggal-realisasi"
                                       required
                                       id="tanggalRealisasi">
                            </div>
                            <div class="form-text">
                                <i class="bi bi-clock me-1"></i> Pilih tanggal realisasi sesuai kebutuhan
                            </div>
                        </div>

                        <!-- Info Kontrak Terpilih -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Informasi Kontrak Terpilih</label>
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <div class="row small g-2" id="kontrakInfo">
                                        <div class="col-12">
                                            <strong>Proyek:</strong> <span id="infoProyek">
                                                @php
                                                    $selectedKontrak = $kontraks->firstWhere('id', $realisasi->kontrak_id);
                                                    echo $selectedKontrak->proyek->nama_proyek ?? 'N/A';
                                                @endphp
                                            </span>
                                        </div>
                                        <div class="col-12">
                                            <strong>Mitra:</strong> <span id="infoMitra">
                                                {{ $selectedKontrak->mitra ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="col-12">
                                            <strong>Nilai Kontrak:</strong> <span id="infoNilai">
                                                Rp {{ $selectedKontrak ? number_format($selectedKontrak->nilai_kontrak, 0, ',', '.') : '0' }}
                                            </span>
                                        </div>
                                        <div class="col-12">
                                            <strong>Progress Saat Ini:</strong> <span id="infoProgress">{{ $realisasi->progress_persen }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Keterangan
                                <span class="text-muted small">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" class="form-control"
                                      rows="3" placeholder="Tambahkan catatan atau keterangan mengenai realisasi ini..."
                                      id="keteranganInput">{{ old('keterangan', $realisasi->keterangan) }}</textarea>
                            <div class="form-text">
                                <span id="charCount">{{ strlen(old('keterangan', $realisasi->keterangan)) }}</span>/500 karakter
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-5 pt-3 border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('realisasi.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Daftar
                        </a>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-check-circle me-2"></i> Update Realisasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Elements
            const kontrakSelect = document.getElementById("kontrakSelect");
            const progressInput = document.getElementById("progressInput");
            const progressSlider = document.getElementById("progressSlider");
            const progressBar = document.getElementById("progressBar");
            const progressStatus = document.getElementById("progressStatus");
            const nilaiRealisasiDisplay = document.getElementById("nilaiRealisasiDisplay");
            const nilaiRealisasiHidden = document.getElementById("nilaiRealisasiHidden");
            const tanggalRealisasi = document.getElementById("tanggalRealisasi");
            const keteranganInput = document.getElementById("keteranganInput");
            const charCount = document.getElementById("charCount");

            console.log('=== DEBUG REALISASI EDIT ===');
            console.log('Kontrak Select Element:', kontrakSelect);
            console.log('Kontrak Value:', kontrakSelect ? kontrakSelect.value : 'null');

            // ✅ PERBAIKAN: Setup tanggal validation (hanya validasi kosong)
            function setupTanggalValidation() {
                if (!tanggalRealisasi) return;

                // Hapus max attribute yang membatasi hanya hari ini
                tanggalRealisasi.removeAttribute('max');

                // Validasi hanya untuk field kosong
                tanggalRealisasi.addEventListener('change', function() {
                    const tanggal = this.value;

                    if (!tanggal) {
                        this.classList.add('is-invalid');
                        this.nextElementSibling.innerHTML = '<div class="text-danger small mt-1">Tanggal harus diisi</div>';
                    } else {
                        this.classList.remove('is-invalid');
                        if (this.nextElementSibling) {
                            this.nextElementSibling.innerHTML = '';
                        }
                    }
                });
            }

            // Update kontrak info function
            function updateKontrakInfo() {
                console.log('updateKontrakInfo called');

                if (!kontrakSelect) {
                    console.log('Kontrak select not found');
                    return;
                }

                const selectedOption = kontrakSelect.options[kontrakSelect.selectedIndex];
                console.log('Selected Option:', selectedOption);

                if (selectedOption && kontrakSelect.value) {
                    const nilaiKontrak = selectedOption.getAttribute('data-nilai');
                    const proyek = selectedOption.getAttribute('data-proyek');
                    const mitra = selectedOption.getAttribute('data-mitra');

                    console.log('Data from option:', { nilaiKontrak, proyek, mitra });

                    // Update info display
                    const infoProyek = document.getElementById('infoProyek');
                    const infoMitra = document.getElementById('infoMitra');
                    const infoNilai = document.getElementById('infoNilai');

                    if (infoProyek) {
                        infoProyek.textContent = proyek || '-';
                        console.log('Updated infoProyek:', infoProyek.textContent);
                    }
                    if (infoMitra) {
                        infoMitra.textContent = mitra || '-';
                        console.log('Updated infoMitra:', infoMitra.textContent);
                    }
                    if (infoNilai) {
                        infoNilai.textContent = 'Rp ' + parseFloat(nilaiKontrak || 0).toLocaleString('id-ID');
                        console.log('Updated infoNilai:', infoNilai.textContent);
                    }

                    // Hitung ulang realisasi
                    hitungRealisasi();
                } else {
                    console.log('No option selected or no value');
                }
            }

            // Hitung nilai realisasi
            function hitungRealisasi() {
                console.log('hitungRealisasi called');

                if (!kontrakSelect || !kontrakSelect.value) {
                    console.log('No kontrak selected, skipping calculation');
                    return;
                }

                const selectedOption = kontrakSelect.options[kontrakSelect.selectedIndex];
                const progress = parseFloat(progressInput.value) || 0;

                console.log('Progress:', progress);
                console.log('Selected Option for calculation:', selectedOption);

                if (selectedOption) {
                    const nilaiKontrak = parseFloat(selectedOption.getAttribute('data-nilai') || 0);
                    const hasil = (progress / 100) * nilaiKontrak;
                    const nilaiRealisasi = Math.round(hasil);

                    console.log('Calculation:', {
                        nilaiKontrak,
                        progress,
                        hasil,
                        nilaiRealisasi
                    });

                    // Update nilai realisasi
                    if (nilaiRealisasiHidden) {
                        nilaiRealisasiHidden.value = nilaiRealisasi;
                        console.log('Updated nilaiRealisasiHidden:', nilaiRealisasiHidden.value);
                    }
                    if (nilaiRealisasiDisplay) {
                        nilaiRealisasiDisplay.value = nilaiRealisasi.toLocaleString('id-ID');
                        console.log('Updated nilaiRealisasiDisplay:', nilaiRealisasiDisplay.value);
                    }
                }
            }

            // Update progress visual
            function updateProgressVisual(progress) {
                progress = parseFloat(progress) || 0;
                console.log('updateProgressVisual:', progress);

                // Update progress bar
                if (progressBar) {
                    progressBar.style.width = progress + '%';
                    progressBar.setAttribute('aria-valuenow', progress);
                }

                // Update status text dan warna progress bar
                let statusText, statusClass, progressBarClass;
                if (progress == 0) {
                    statusText = '<i class="bi bi-x-circle me-1"></i>Belum Dimulai';
                    statusClass = 'text-danger';
                    progressBarClass = 'bg-danger';
                } else if (progress < 100) {
                    statusText = '<i class="bi bi-play-circle me-1"></i>Dalam Pengerjaan';
                    statusClass = 'text-warning';
                    progressBarClass = 'bg-warning';
                } else {
                    statusText = '<i class="bi bi-check-circle me-1"></i>Selesai';
                    statusClass = 'text-success';
                    progressBarClass = 'bg-success';
                }

                // Update progress bar class
                if (progressBar) {
                    progressBar.className = `progress-bar progress-bar-striped progress-bar-animated ${progressBarClass}`;
                }
                if (progressStatus) {
                    progressStatus.innerHTML = `<span class="${statusClass}">${statusText}</span>`;
                }
            }

            // Character counter for keterangan
            function updateCharCount() {
                if (!keteranganInput || !charCount) return;

                const count = keteranganInput.value.length;
                charCount.textContent = count;

                if (count > 500) {
                    charCount.classList.add('text-danger');
                    charCount.innerHTML = `<span class="text-danger">${count}</span>/500 karakter - Melebihi batas!`;
                } else {
                    charCount.classList.remove('text-danger');
                    charCount.innerHTML = `${count}/500 karakter`;
                }
            }

            // Event Listeners
            if (kontrakSelect) {
                kontrakSelect.addEventListener("change", function() {
                    console.log('=== KONTRAK CHANGED ===');
                    console.log('New value:', this.value);
                    updateKontrakInfo();
                });
            }

            if (progressInput) {
                progressInput.addEventListener("input", function() {
                    const value = Math.min(100, Math.max(0, this.value));
                    this.value = value;
                    if (progressSlider) progressSlider.value = value;
                    updateProgressVisual(value);
                    hitungRealisasi();
                });

                progressInput.addEventListener("change", function() {
                    const value = Math.min(100, Math.max(0, this.value));
                    this.value = value;
                    if (progressSlider) progressSlider.value = value;
                    updateProgressVisual(value);
                    hitungRealisasi();
                });
            }

            if (progressSlider) {
                progressSlider.addEventListener("input", function() {
                    if (progressInput) progressInput.value = this.value;
                    updateProgressVisual(this.value);
                    hitungRealisasi();
                });
            }

            if (keteranganInput) {
                keteranganInput.addEventListener("input", updateCharCount);
            }

            // Initialize functions
            function initializeAll() {
                console.log('=== INITIALIZE ALL ===');

                // Setup tanggal validation
                setupTanggalValidation();

                // Pastikan updateKontrakInfo dipanggil pertama kali
                updateKontrakInfo();

                // Update progress visual
                if (progressInput) {
                    updateProgressVisual(progressInput.value);
                }

                // Update character count
                updateCharCount();

                console.log('=== INITIALIZATION COMPLETE ===');
            }

            // Initialize langsung
            initializeAll();

            // Form validation
            const form = document.getElementById('editRealisasiForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const progress = parseFloat(progressInput.value);
                    const kontrakId = kontrakSelect.value;
                    const tanggal = tanggalRealisasi.value;

                    let errors = [];

                    if (!kontrakId) {
                        errors.push('Silakan pilih kontrak terlebih dahulu');
                        kontrakSelect.focus();
                    }

                    if (isNaN(progress) || progress < 0 || progress > 100) {
                        errors.push('Progress harus antara 0-100%');
                        progressInput.focus();
                    }

                    if (!tanggal) {
                        errors.push('Silakan pilih tanggal realisasi');
                        tanggalRealisasi.focus();
                    }

                    if (errors.length > 0) {
                        // Gunakan SweetAlert jika tersedia
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: '<ul class="text-start">' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>',
                                confirmButtonColor: '#dc3545'
                            });
                        } else {
                            alert(errors.join('\n'));
                        }
                        return false;
                    }

                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
                    submitBtn.disabled = true;

                    // Submit form
                    setTimeout(() => {
                        this.submit();
                    }, 500);
                });
            }

            // Reset form handler
            const resetBtn = document.querySelector('button[type="reset"]');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    setTimeout(() => {
                        initializeAll();
                    }, 100);
                });
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <style>
        /* Dropdown Styling */
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
            outline: none;
        }

        /* Progress Styling */
        .progress-slider {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            width: 100%;
            margin: 10px 0;
        }

        .progress-slider::-webkit-slider-thumb {
            appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #ffc107;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .progress-slider::-moz-range-thumb {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #ffc107;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .progress-input {
            font-weight: 600;
            text-align: center;
            border: 2px solid #e9ecef;
        }

        .progress-input:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .nilai-realisasi-display {
            font-weight: 600;
            font-size: 1.1rem;
            color: #198754;
            text-align: right;
            background-color: #f8f9fa !important;
        }

        .tanggal-realisasi {
            font-weight: 500;
        }

        .card-header {
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }

        .form-label {
            color: #495057;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .btn-lg {
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 0.75rem;
            font-size: 1rem;
        }

        .alert {
            border-radius: 0.75rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            font-weight: 500;
        }

        /* Info Card Styling */
        .card.bg-light {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 4px;
        }

        /* Progress Bar Colors */
        .progress-bar.bg-danger {
            background-color: #dc3545 !important;
        }

        .progress-bar.bg-warning {
            background-color: #ffc107 !important;
        }

        .progress-bar.bg-success {
            background-color: #198754 !important;
        }

        /* Date input specific styling */
        .tanggal-realisasi {
            background-color: white;
        }

        .tanggal-realisasi:focus {
            background-color: white;
            border-color: #ffc107;
        }
    </style>
</x-layout>
