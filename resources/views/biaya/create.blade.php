<x-layout>
    <x-slot:title>Tambah Biaya</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title m-0"><i class="bi bi-plus-circle me-2"></i> Tambah Data</h3>
            </div>
            <div class="card-body">
                <!-- Summary Section -->
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <small class="text-muted d-block">Total Persentase</small> <!-- Ganti Progress jadi Persentase -->
                                <h5 id="summaryTotalProgress" class="text-primary mb-0">0%</h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Total Nominal</small> <!-- Ganti Realisasi jadi Nominal -->
                                <h5 id="summaryTotalRealisasi" class="text-success mb-0">Rp 0</h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Sisa Kontrak</small>
                                <h5 id="summarySisaKontrak" class="text-warning mb-0">Rp 0</h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Progress Keseluruhan</small>
                                <div class="progress" style="height: 8px;">
                                    <div id="overallProgressBar" class="progress-bar bg-success" style="width: 0%"></div>
                                </div>
                                <small id="overallProgressText" class="text-muted">0%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('biaya.store') }}" method="POST" id="biayaForm">
                    @csrf

                    <div class="row g-3">
                        <!-- Kantor -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kantor <span class="text-danger">*</span></label>
                            <select name="kantor_id" id="kantorSelect" class="form-select" required>
                                <option value="">-- Pilih Kantor --</option>
                                @foreach($kantors as $k)
                                    <option value="{{ $k->id }}" {{ old('kantor_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kantor }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kantor_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontrak <span class="text-danger">*</span></label>
                            <select name="kontrak_id" id="kontrakSelect" class="form-select" required>
                                <option value="">-- Pilih Kantor terlebih dahulu --</option>
                            </select>
                            @error('kontrak_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Progress Total Warning -->
                    <div id="totalProgressWarning" class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="warningText">Total progress: 0% - Sisa progress: 100%</span>
                    </div>

                    <!-- Termin Section -->
                    <div id="terminWrapper">
                        <!-- Termin pertama -->
                        <div class="termin-item card shadow-sm border-0 rounded-3 p-3 position-relative mb-3">
                            <button type="button" class="btn btn-sm btn-danger remove-termin position-absolute top-0 end-0 m-2" style="display:none;">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                    <select name="jenis[]" class="form-select" required>
                                        <option value="Masa Pelaksanaan">Masa Pelaksanaan</option>
                                        <option value="Masa Pemeliharaan">Masa Pemeliharaan</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Termin <span class="text-danger">*</span></label>
                                    <input type="number" name="termin[]" class="form-control termin-input" placeholder="1" required min="1" value="1">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Persentase (%) <span class="text-danger">*</span></label> <!-- Ganti Progress jadi Persentase -->
                                    <input type="number" name="progress[]" class="form-control progress-input" value="0" max="100" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Nominal</label> <!-- Ganti Label -->
                                    <input type="text" class="form-control nominal-input bg-light" readonly value="Rp 0">
                                    <input type="hidden" name="nominal_realisasi[]" class="nominal-hidden" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tombol Tambah Termin -->
                    <div class="mt-3">
                        <button type="button" id="addTermin" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Termin
                        </button>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('biaya.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    $(document).ready(function () {
        console.log('Document ready - Initializing...');

        // Inisialisasi Select2
        try {
            if (typeof $.fn.select2 === 'function') {
                console.log('Select2 is available, initializing...');
                $('#kantorSelect').select2({
                    placeholder: "-- Pilih Kantor --",
                    allowClear: true,
                    width: '100%'
                });
                $('#kontrakSelect').select2({
                    placeholder: "-- Pilih Kontrak --",
                    allowClear: true,
                    width: '100%'
                });
                console.log('Select2 initialized successfully');
            } else {
                console.warn('Select2 not available, using standard dropdown');
                $('#kantorSelect, #kontrakSelect').addClass('form-control');
            }
        } catch (error) {
            console.error('Error initializing Select2:', error);
            $('#kantorSelect, #kontrakSelect').addClass('form-control');
        }

        const wrapper = $("#terminWrapper");
        const addBtn = $("#addTermin");
        const warningDiv = $("#totalProgressWarning");
        let kontrakData = {};
        let terminCounter = 1;
        let isAdding = false;

        // 🔹 FUNGSI UTAMA
        function hitungTotalProgress() {
            let total = 0;
            $('.progress-input').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            return total;
        }

        function hitungTotalRealisasi() {
            let total = 0;
            $('.nominal-hidden').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            return total;
        }

        function hitungSisaProgress() {
            return Math.max(0, 100 - hitungTotalProgress());
        }

        function getNilaiKontrak() {
            const kontrakId = $('#kontrakSelect').val();
            return kontrakData[kontrakId] || 0;
        }

        // 🔹 UPDATE SUMMARY SECTION
        function updateSummary() {
            const totalProgress = hitungTotalProgress();
            const totalRealisasi = hitungTotalRealisasi();
            const nilaiKontrak = getNilaiKontrak();
            const sisaKontrak = Math.max(0, nilaiKontrak - totalRealisasi);
            const progressPercentage = nilaiKontrak > 0 ? (totalRealisasi / nilaiKontrak) * 100 : 0;

            $('#summaryTotalProgress').text(totalProgress.toFixed(2) + '%');
            $('#summaryTotalRealisasi').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalRealisasi));
            $('#summarySisaKontrak').text('Rp ' + new Intl.NumberFormat('id-ID').format(sisaKontrak));

            $('#overallProgressBar').css('width', progressPercentage + '%');
            $('#overallProgressText').text(progressPercentage.toFixed(1) + '%');

            // Update warna progress bar berdasarkan persentase
            if (progressPercentage >= 100) {
                $('#overallProgressBar').removeClass('bg-warning bg-success').addClass('bg-success');
            } else if (progressPercentage >= 80) {
                $('#overallProgressBar').removeClass('bg-warning bg-success').addClass('bg-warning');
            } else {
                $('#overallProgressBar').removeClass('bg-warning bg-success').addClass('bg-primary');
            }
        }

        // 🔹 UPDATE PROGRESS WARNING
        function updateProgressWarning() {
            const totalProgress = hitungTotalProgress();
            const remainingProgress = hitungSisaProgress();

            let warningClass = 'alert-warning';
            let warningText = `Total progress: <strong>${totalProgress.toFixed(2)}%</strong> - Sisa progress: <strong>${remainingProgress.toFixed(2)}%</strong>`;

            if (totalProgress > 100) {
                warningClass = 'alert-danger';
                warningText = `Total progress <strong>${totalProgress.toFixed(2)}%</strong> melebihi 100%! Silakan kurangi progress.`;
            } else if (totalProgress === 100) {
                warningClass = 'alert-success';
                warningText = `Total progress <strong>${totalProgress.toFixed(2)}%</strong> - Sudah mencapai 100%`;
            }

            warningDiv.removeClass('alert-warning alert-danger alert-success').addClass(warningClass);
            $('#warningText').html(warningText);
        }

        // 🔹 HITUNG NOMINAL
        function hitungNominal(row) {
            const nilaiKontrak = getNilaiKontrak();
            const progress = parseFloat(row.find('.progress-input').val()) || 0;
            const hasil = (progress / 100) * nilaiKontrak;

            row.find('.nominal-input').val('Rp ' + new Intl.NumberFormat('id-ID').format(hasil));
            row.find('.nominal-hidden').val(hasil);
        }

        function hitungSemuaNominal() {
            $('.termin-item').each(function() {
                hitungNominal($(this));
            });
        }

        // 🔹 VALIDASI TERMIN UNIK
        function validasiTerminUnik() {
            const terminValues = [];
            let hasDuplicate = false;

            $('.termin-input').each(function() {
                const value = $(this).val();
                if (value && terminValues.includes(value)) {
                    $(this).addClass('is-invalid');
                    hasDuplicate = true;
                } else {
                    $(this).removeClass('is-invalid');
                    if (value) terminValues.push(value);
                }
            });

            return !hasDuplicate;
        }

        // 🔹 UPDATE TOMBOL REMOVE
        function updateRemoveButtons() {
            if ($('.termin-item').length === 1) {
                $('.remove-termin').hide();
            } else {
                $('.remove-termin').show();
            }
        }

        // 🔹 UPDATE TERMIN NUMBERS
        function updateTerminNumbers() {
            $('.termin-item').each(function(index) {
                $(this).find('.termin-input').val(index + 1);
            });
        }

        // 🔹 VALIDASI BISNIS
        function validateBusinessRules() {
            const kontrakId = $('#kontrakSelect').val();
            const totalProgress = hitungTotalProgress();
            const totalRealisasi = hitungTotalRealisasi();
            const nilaiKontrak = getNilaiKontrak();

            let errors = [];

            // Rule 1: Total progress tidak boleh > 100%
            if (totalProgress > 100) {
                errors.push('Total progress tidak boleh melebihi 100%');
            }

            // Rule 2: Total realisasi tidak boleh > nilai kontrak
            if (totalRealisasi > nilaiKontrak) {
                errors.push('Total realisasi tidak boleh melebihi nilai kontrak');
            }

            // Rule 3: Harus pilih kontrak
            if (!kontrakId) {
                errors.push('Harus memilih kontrak terlebih dahulu');
            }

            return errors;
        }

        // 🔹 EVENT HANDLERS
        $('#kantorSelect').on('change', function() {
            console.log('Kantor berubah:', $(this).val());

            var kantorId = $(this).val();
            var kontrakSelect = $('#kontrakSelect');

            kontrakSelect.empty();
            kontrakSelect.append('<option value="">-- Memuat kontrak... --</option>');
            kontrakSelect.prop('disabled', true);
            kontrakData = {};

            if (kantorId) {
                console.log('Mengambil kontrak untuk kantor:', kantorId);

                $.ajax({
                    url: '/biaya/get-kontrak/' + kantorId,
                    type: 'GET',
                    success: function(data) {
                        console.log('Data kontrak diterima:', data);

                        kontrakSelect.empty();
                        kontrakSelect.append('<option value="">-- Pilih Kontrak --</option>');

                        if (data && data.length > 0) {
                            $.each(data, function(index, kontrak) {
                                kontrakData[kontrak.id] = kontrak.nilai_kontrak;

                                kontrakSelect.append(
                                    '<option value="' + kontrak.id + '">' +
                                    kontrak.proyek_nama + ' - ' +
                                    kontrak.bagian + ' - ' +
                                    kontrak.nomor_spk + ' - Rp ' +
                                    new Intl.NumberFormat('id-ID').format(kontrak.nilai_kontrak) +
                                    '</option>'
                                );
                            });

                            var oldKontrakId = "{{ old('kontrak_id') }}";
                            if (oldKontrakId) {
                                kontrakSelect.val(oldKontrakId).trigger('change');
                            }
                        } else {
                            kontrakSelect.append('<option value="">-- Tidak ada kontrak untuk kantor ini --</option>');
                        }

                        kontrakSelect.prop('disabled', false);

                        if (typeof $.fn.select2 === 'function') {
                            kontrakSelect.trigger('change.select2');
                        } else {
                            kontrakSelect.trigger('change');
                        }

                        hitungSemuaNominal();
                        updateSummary();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error mengambil kontrak:', error);
                        kontrakSelect.empty();
                        kontrakSelect.append('<option value="">-- Error memuat kontrak --</option>');
                        kontrakSelect.prop('disabled', false);
                    }
                });
            } else {
                kontrakSelect.empty();
                kontrakSelect.append('<option value="">-- Pilih Kantor terlebih dahulu --</option>');
                kontrakSelect.prop('disabled', false);
            }
        });

        // Event delegation untuk progress input
        $(document).on('input', '.progress-input', function() {
            const row = $(this).closest('.termin-item');
            hitungNominal(row);
            updateProgressWarning();
            updateSummary();
        });

        // Event delegation untuk tanggal input (validasi hanya jika kosong)
        $(document).on('change', '.tanggal-input', function() {
            const tanggal = $(this).val();

            if (!tanggal) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
                $(this).after('<div class="invalid-feedback">Tanggal harus diisi</div>');
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });

        // Event delegation untuk remove button
        $(document).on('click', '.remove-termin', function() {
            if ($('.termin-item').length > 1) {
                $(this).closest('.termin-item').remove();
                updateTerminNumbers();
                updateProgressWarning();
                hitungSemuaNominal();
                updateRemoveButtons();
                updateSummary();
            }
        });

        // Tambah termin baru
        addBtn.on('click', function() {
            if (isAdding) {
                console.log('Masih proses tambah sebelumnya, tunggu...');
                return;
            }

            isAdding = true;

            const remainingProgress = hitungSisaProgress();

            if (remainingProgress <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Dapat Menambah Termin',
                    text: 'Total progress sudah mencapai 100%. Tidak dapat menambah termin baru.',
                    confirmButtonColor: '#ffc107'
                });
                isAdding = false;
                return;
            }

            terminCounter++;

            const newRow = `
            <div class="termin-item card shadow-sm border-0 rounded-3 p-3 position-relative mb-3">
                <button type="button" class="btn btn-sm btn-danger remove-termin position-absolute top-0 end-0 m-2">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis[]" class="form-select" required>
                            <option value="Pelaksanaan">Pelaksanaan</option>
                            <option value="Pemeliharaan">Pemeliharaan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Termin <span class="text-danger">*</span></label>
                        <input type="number" name="termin[]" class="form-control termin-input" placeholder="1" required min="1" value="${terminCounter}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Progress (%) <span class="text-danger">*</span></label>
                        <input type="number" name="progress[]" class="form-control progress-input" value="0" max="${remainingProgress}" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Realisasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_realisasi[]" class="form-control tanggal-input" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Nominal Realisasi</label>
                        <input type="text" class="form-control nominal-input bg-light" readonly value="Rp 0">
                        <input type="hidden" name="nominal_realisasi[]" class="nominal-hidden" value="0">
                    </div>
                </div>
            </div>
            `;

            wrapper.append(newRow);
            updateRemoveButtons();
            updateProgressWarning();
            updateSummary();

            setTimeout(() => {
                isAdding = false;
            }, 100);
        });

        // Update nominal ketika kontrak berubah
        $('#kontrakSelect').on('change', function() {
            console.log('Kontrak berubah:', $(this).val());
            hitungSemuaNominal();
            updateSummary();
        });

        // Validasi form sebelum submit
        $('#biayaForm').on('submit', function(e) {
            const totalProgress = hitungTotalProgress();
            const hasValidTermin = validasiTerminUnik();
            const hasEmptyDate = $('.tanggal-input.is-invalid').length > 0;
            const kontrakId = $('#kontrakSelect').val();
            const businessErrors = validateBusinessRules();

            console.log('Validasi submit - Kontrak ID:', kontrakId);

            // Validasi kontrak
            if (!kontrakId) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Kontrak Belum Dipilih',
                    text: 'Harap pilih kontrak terlebih dahulu!',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Validasi total progress
            if (totalProgress > 100) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Total Progress Melebihi 100%',
                    text: 'Total progress tidak boleh melebihi 100%! Silakan kurangi progress pada beberapa termin.',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Validasi termin unik
            if (!hasValidTermin) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Termin Duplikat',
                    text: 'Terdapat termin yang duplikat! Silakan periksa kembali nomor termin.',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Validasi tanggal kosong
            if (hasEmptyDate) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal Tidak Lengkap',
                    text: 'Semua tanggal realisasi harus diisi!',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Validasi business rules
            if (businessErrors.length > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<ul class="text-start">' + businessErrors.map(error => `<li>${error}</li>`).join('') + '</ul>',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Validasi minimal satu termin
            if ($('.termin-item').length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Data Termin Kosong',
                    text: 'Minimal harus ada satu termin!',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }
        });

        // Inisialisasi pertama kali
        updateRemoveButtons();
        updateProgressWarning();
        updateSummary();

        // Trigger change jika ada old kantor_id
        @if(old('kantor_id'))
            $('#kantorSelect').val("{{ old('kantor_id') }}").trigger('change');
        @endif

        console.log('JavaScript loaded successfully');
    });
</script>
</x-layout>
