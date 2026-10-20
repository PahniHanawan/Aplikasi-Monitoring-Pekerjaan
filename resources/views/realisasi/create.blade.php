<x-layout>
    <x-slot:title>{{ $title ?? 'Tambah Realisasi' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title m-0">
                    <i class="bi bi-clipboard-check me-2"></i> Tambah Realisasi
                </h3>
            </div>

            <div class="card-body">
                <!-- Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Info -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Catatan:</strong> Nilai realisasi akan dihitung otomatis berdasarkan nilai kontrak dan progress.
                </div>

                <!-- Form Realisasi -->
                <form action="{{ route('realisasi.store') }}" method="POST" id="realisasiForm">
                    @csrf

                    <div class="row g-3">
                        <!-- Pilih Kontrak dengan Select2 -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Kontrak <span class="text-danger">*</span></label>
                            <select name="kontrak_id" class="form-select select2-kontrak" required id="kontrakSelect">
                                <option value="">-- Pilih Kontrak --</option>
                                @foreach($kontraks as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kontrak_id') == $k->id ? 'selected' : '' }}
                                        data-nilai="{{ $k->nilai_kontrak }}"
                                        data-proyek="{{ $k->proyek->nama_proyek ?? 'N/A' }}"
                                        data-mitra="{{ $k->mitra }}"
                                        data-nomor="{{ $k->nomor_spk }}"
                                        data-nilai-formatted="{{ format_currency($k->nilai_kontrak) }}">
                                        {{ $k->nomor_spk }} - {{ $k->proyek->nama_proyek ?? 'N/A' }} - {{ $k->mitra }} - {{ format_currency($k->nilai_kontrak) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <small>Ketik untuk mencari kontrak berdasarkan nomor SPK, nama proyek, atau mitra</small>
                            </div>
                            @error('kontrak_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informasi Kontrak yang Dipilih -->
                        <div class="col-md-12" id="kontrakInfo" style="display: none;">
                            <div class="alert alert-light border">
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">Proyek</small>
                                        <div id="infoProyek" class="fw-bold"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Nomor SPK</small>
                                        <div id="infoNomor" class="fw-bold"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Mitra</small>
                                        <div id="infoMitra" class="fw-bold"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Progress (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="progress_persen" value="{{ old('progress_persen') }}"
                                       class="form-control @error('progress_persen') is-invalid @enderror"
                                       placeholder="Contoh: 50" min="0" max="100" step="0.01" required
                                       id="progressInput">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">
                                <small>Masukkan progress dari 0-100%</small>
                            </div>
                            @error('progress_persen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nilai Kontrak (Readonly) -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nilai Kontrak</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="nilai_kontrak_display" class="form-control bg-light" readonly>
                            </div>
                            <div class="form-text">
                                <small id="nilaiKontrakTerbilang" class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Nilai Realisasi (Readonly) -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nilai Realisasi</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="nilai_realisasi_display" class="form-control bg-light" readonly>
                            </div>
                            <input type="hidden" name="nilai_realisasi" id="nilai_realisasi_hidden" value="{{ old('nilai_realisasi', 0) }}">
                            <div class="form-text">
                                <small id="nilaiRealisasiTerbilang" class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Tanggal Realisasi -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Realisasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_realisasi" value="{{ old('tanggal_realisasi', date('Y-m-d')) }}"
                                   class="form-control @error('tanggal_realisasi') is-invalid @enderror" required
                                   id="tanggalInput">
                            @error('tanggal_realisasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>Pilih tanggal realisasi sesuai kebutuhan</small>
                            </div>
                        </div>

                        <!-- Keterangan (Opsional) -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>

                        <!-- Summary -->
                        <div class="col-12">
                            <div class="alert alert-secondary">
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">Progress</small>
                                        <div id="summaryProgress" class="fw-bold">0%</div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Nilai Kontrak</small>
                                        <div id="summaryNilaiKontrak" class="fw-bold">Rp 0</div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Nilai Realisasi</small>
                                        <div id="summaryNilaiRealisasi" class="fw-bold">Rp 0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('realisasi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2" id="resetButton">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-success" id="submitButton">
                                <i class="bi bi-check-circle"></i> Simpan Realisasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Select2 Custom Styling */
        .select2-container .select2-selection--single {
            height: 45px !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px !important;
            padding-left: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px !important;
        }

        .select2-dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* Form Styling */
        .form-control.bg-light {
            background-color: #f8f9fa !important;
            border-color: #e9ecef;
        }

        .alert-light {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        #kontrakInfo {
            transition: all 0.3s ease;
        }

        /* Progress bar styling */
        .progress {
            height: 10px;
            margin-top: 5px;
        }

        /* Currency display */
        .form-text small {
            font-style: italic;
        }
    </style>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        console.log('🚀 Document ready - Initializing Realisasi Form');

        // Initialize Select2
        $('#kontrakSelect').select2({
            placeholder: "-- Pilih Kontrak --",
            allowClear: true,
            width: '100%',
            dropdownParent: $('.card-body'),
            minimumInputLength: 0,
            templateResult: formatKontrakOption,
            templateSelection: formatKontrakSelection
        });

        // Format function for Select2 options
        function formatKontrakOption(option) {
            if (!option.id) {
                return option.text;
            }

            const $option = $(option.element);
            const nilai = $option.data('nilai-formatted') || 'Rp 0';
            const proyek = $option.data('proyek') || '';
            const mitra = $option.data('mitra') || '';

            return $(
                `<div>
                    <strong>${option.text}</strong>
                    <div class="small text-muted">
                        Proyek: ${proyek} | Mitra: ${mitra}
                    </div>
                </div>`
            );
        }

        function formatKontrakSelection(option) {
            if (!option.id) {
                return option.text;
            }
            return $(option.element).text();
        }

        // Initialize variables
        let selectedKontrakId = null;
        let nilaiKontrak = 0;

        // Event Listeners
        $('#kontrakSelect').on('change', function() {
            const kontrakId = $(this).val();
            selectedKontrakId = kontrakId;

            if (kontrakId) {
                const selectedOption = $(this).find('option:selected');
                nilaiKontrak = parseFloat(selectedOption.data('nilai')) || 0;
                const nilaiFormatted = selectedOption.data('nilai-formatted') || 'Rp 0';
                const proyek = selectedOption.data('proyek') || '';
                const nomor = selectedOption.data('nomor') || '';
                const mitra = selectedOption.data('mitra') || '';

                // Update kontrak info display
                $('#kontrakInfo').show();
                $('#infoProyek').text(proyek);
                $('#infoNomor').text(nomor);
                $('#infoMitra').text(mitra);

                // Update nilai kontrak display
                $('#nilai_kontrak_display').val(nilaiFormatted);

                // Update terbilang
                if (typeof terbilangRupiah === 'function') {
                    $('#nilaiKontrakTerbilang').text(terbilangRupiah(nilaiKontrak));
                }

                // Update summary
                $('#summaryNilaiKontrak').text(nilaiFormatted);

                // Calculate realisasi
                calculateRealisasi();
            } else {
                // Reset if no kontrak selected
                $('#kontrakInfo').hide();
                $('#nilai_kontrak_display').val('');
                $('#nilaiKontrakTerbilang').text('');
                $('#summaryNilaiKontrak').text('Rp 0');
                resetCalculation();
            }
        });

        $('#progressInput').on('input', function() {
            calculateRealisasi();
        });

        $('#tanggalInput').on('change', function() {
            validateTanggal();
        });

        // Reset button
        $('#resetButton').on('click', function(e) {
            e.preventDefault();
            resetForm();
        });

        // Form submission
        $('#realisasiForm').on('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });

        // Initialize form if there's old data
        @if(old('kontrak_id'))
            setTimeout(function() {
                $('#kontrakSelect').val('{{ old("kontrak_id") }}').trigger('change');
                $('#progressInput').val('{{ old("progress_persen") }}').trigger('input');
            }, 500);
        @endif

        // Helper functions
        function calculateRealisasi() {
            if (!selectedKontrakId || nilaiKontrak <= 0) {
                resetCalculation();
                return;
            }

            const progress = parseFloat($('#progressInput').val()) || 0;
            const nilaiRealisasi = Math.round((progress / 100) * nilaiKontrak);

            // Format nilai realisasi
            const formattedRealisasi = formatRupiah(nilaiRealisasi);

            // Update display
            $('#nilai_realisasi_display').val(formattedRealisasi);
            $('#nilai_realisasi_hidden').val(nilaiRealisasi);

            // Update terbilang
            if (typeof terbilangRupiah === 'function') {
                $('#nilaiRealisasiTerbilang').text(terbilangRupiah(nilaiRealisasi));
            }

            // Update summary
            $('#summaryProgress').text(progress.toFixed(2) + '%');
            $('#summaryNilaiRealisasi').text(formattedRealisasi);

            console.log('Calculated:', {
                progress,
                nilaiKontrak,
                nilaiRealisasi
            });
        }

        function resetCalculation() {
            $('#nilai_realisasi_display').val('Rp 0');
            $('#nilai_realisasi_hidden').val('0');
            $('#nilaiRealisasiTerbilang').text('');
            $('#summaryProgress').text('0%');
            $('#summaryNilaiRealisasi').text('Rp 0');
        }

        function resetForm() {
            // Reset Select2
            $('#kontrakSelect').val(null).trigger('change');

            // Reset all inputs
            $('#progressInput').val('');
            $('#nilai_kontrak_display').val('');
            $('#nilai_realisasi_display').val('Rp 0');
            $('#nilai_realisasi_hidden').val('0');
            $('#tanggalInput').val('{{ date('Y-m-d') }}');
            $('textarea[name="keterangan"]').val('');

            // Reset displays
            $('#kontrakInfo').hide();
            $('#nilaiKontrakTerbilang').text('');
            $('#nilaiRealisasiTerbilang').text('');
            $('#summaryProgress').text('0%');
            $('#summaryNilaiKontrak').text('Rp 0');
            $('#summaryNilaiRealisasi').text('Rp 0');

            // Remove validation classes
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            console.log('Form reset successfully');
        }

        function validateTanggal() {
            const tanggalInput = $('#tanggalInput');
            const tanggal = tanggalInput.val();

            if (!tanggal) {
                showError(tanggalInput, 'Tanggal realisasi harus diisi');
                return false;
            }

            const selectedDate = new Date(tanggal);
            const today = new Date();


            // Remove error if valid
            clearError(tanggalInput);
            return true;
        }

        function validateForm() {
            let isValid = true;

            // Validate kontrak
            if (!selectedKontrakId) {
                showError($('#kontrakSelect'), 'Silakan pilih kontrak terlebih dahulu');
                isValid = false;
            } else {
                clearError($('#kontrakSelect'));
            }

            // Validate progress
            const progress = parseFloat($('#progressInput').val()) || 0;
            if (isNaN(progress) || progress < 0 || progress > 100) {
                showError($('#progressInput'), 'Progress harus antara 0-100%');
                isValid = false;
            } else {
                clearError($('#progressInput'));
            }

            // Validate tanggal
            if (!validateTanggal()) {
                isValid = false;
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Harap periksa kembali data yang diisi',
                    confirmButtonColor: '#dc3545'
                });
            }

            return isValid;
        }

        function showError(element, message) {
            element.addClass('is-invalid');
            element.after(`<div class="invalid-feedback">${message}</div>`);
        }

        function clearError(element) {
            element.removeClass('is-invalid');
            element.next('.invalid-feedback').remove();
        }

        function formatRupiah(amount) {
            const number = parseFloat(amount) || 0;
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        // Add terbilangRupiah function if not defined
        if (typeof terbilangRupiah === 'undefined') {
            window.terbilangRupiah = function(num) {
                if (isNaN(num) || num === null || num === 0 || num === '') {
                    return 'Nol Rupiah';
                }

                const angka = parseInt(num);

                if (angka < 1000) {
                    return angka + ' Rupiah';
                } else if (angka < 1000000) {
                    const ribu = Math.floor(angka / 1000);
                    const sisa = angka % 1000;
                    let result = ribu + ' Ribu';
                    if (sisa > 0) result += ' ' + sisa;
                    return result + ' Rupiah';
                } else if (angka < 1000000000) {
                    const juta = Math.floor(angka / 1000000);
                    const sisa = angka % 1000000;
                    let result = juta + ' Juta';

                    if (sisa >= 1000) {
                        const ribu = Math.floor(sisa / 1000);
                        const sisaRibu = sisa % 1000;
                        result += ' ' + ribu + ' Ribu';
                        if (sisaRibu > 0) result += ' ' + sisaRibu;
                    } else if (sisa > 0) {
                        result += ' ' + sisa;
                    }

                    return result + ' Rupiah';
                } else {
                    const miliar = Math.floor(angka / 1000000000);
                    const sisa = angka % 1000000000;
                    let result = miliar + ' Miliar';

                    if (sisa >= 1000000) {
                        const juta = Math.floor(sisa / 1000000);
                        const sisaJuta = sisa % 1000000;
                        result += ' ' + juta + ' Juta';

                        if (sisaJuta >= 1000) {
                            const ribu = Math.floor(sisaJuta / 1000);
                            const sisaRibu = sisaJuta % 1000;
                            result += ' ' + ribu + ' Ribu';
                            if (sisaRibu > 0) result += ' ' + sisaRibu;
                        } else if (sisaJuta > 0) {
                            result += ' ' + sisaJuta;
                        }
                    } else if (sisa >= 1000) {
                        const ribu = Math.floor(sisa / 1000);
                        const sisaRibu = sisa % 1000;
                        result += ' ' + ribu + ' Ribu';
                        if (sisaRibu > 0) result += ' ' + sisaRibu;
                    } else if (sisa > 0) {
                        result += ' ' + sisa;
                    }

                    return result + ' Rupiah';
                }
            };
        }

        console.log('✅ Realisasi Form initialized successfully');
    });
    </script>
</x-layout>
