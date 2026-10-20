<x-layout>
    <x-slot:title>{{ $title ?? 'Edit Biaya' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">
                    <i class="bi bi-pencil-square me-2"></i> Edit Biaya - Multi Termin
                </h3>
                <div class="badge bg-dark">
                    <i class="bi bi-info-circle me-1"></i> Kontrak: {{ $biaya->kontrak->nomor_spk ?? '-' }}
                </div>
            </div>

            <div class="card-body">
                <!-- Informasi Kontrak -->
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <small><strong>Nilai Kontrak:</strong> Rp {{ number_format($biaya->kontrak->nilai_kontrak ?? 0, 0, ',', '.') }}</small>
                        </div>
                        <div class="col-md-6">
                            <small><strong>Total Termin:</strong> {{ $semuaBiaya->count() }} termin</small>
                        </div>
                    </div>
                </div>

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

                <!-- Progress Total Warning -->
                <div id="totalProgressWarning" class="alert alert-warning d-none mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <span id="warningText"></span>
                </div>

                <!-- Summary Section -->
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <small class="text-muted d-block">Total Progress</small>
                                <h5 id="summaryTotalProgress" class="text-primary mb-0">0%</h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Total Realisasi</small>
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

                <!-- Form Edit Biaya -->
                <form action="{{ route('biaya.update', $biaya->id) }}" method="POST" id="biayaForm">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <!-- Pilih Kantor -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kantor <span class="text-danger">*</span></label>
                            <select name="kantor_id" id="kantorSelect" class="form-select" required>
                                @foreach($kantors as $kantor)
                                    <option value="{{ $kantor->id }}"
                                        {{ $kantor->id == $biaya->kantor_id ? 'selected' : '' }}>
                                        {{ $kantor->nama_kantor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontrak <span class="text-danger">*</span></label>
                            <select name="kontrak_id" id="kontrakSelect" class="form-select" required>
                                <option value="">-- Pilih Kontrak --</option>
                                @foreach($kontraks as $kontrak)
                                    <option value="{{ $kontrak->id }}"
                                        data-nilai="{{ $kontrak->nilai_kontrak }}"
                                        {{ $kontrak->id == $biaya->kontrak_id ? 'selected' : '' }}>
                                        {{ $kontrak->proyek->nama_proyek ?? 'N/A' }} -
                                        {{ $kontrak->bagian }} -
                                        {{ $kontrak->nomor_spk }} -
                                        Rp {{ number_format($kontrak->nilai_kontrak, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!-- Termin Section -->
                    <div id="terminWrapper">
                        @foreach($semuaBiaya as $index => $item)
                        <div class="termin-item card shadow-sm border-0 rounded-3 p-3 position-relative mb-3">
                            <!-- Tombol hapus -->
                            <button type="button" class="btn btn-sm btn-danger remove-termin position-absolute top-0 end-0 m-2"
                                {{ $index == 0 ? 'style="display:none;"' : '' }}>
                                <i class="bi bi-x-lg"></i>
                            </button>

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                    <select name="jenis[]" class="form-select" required>
                                        <option value="Masa Pelaksanaan" {{ $item->jenis == 'Masa Pelaksanaan' ? 'selected' : '' }}>Masa Pelaksanaan</option>
                                        <option value="Masa Pemeliharaan" {{ $item->jenis == 'Masa Pemeliharaan' ? 'selected' : '' }}>Masa Pemeliharaan</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Termin <span class="text-danger">*</span></label>
                                    <input type="number" name="termin[]" value="{{ $item->termin }}"
                                        class="form-control termin-input" placeholder="1" required min="1">
                                    <div class="termin-feedback"></div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Persentase (%) <span class="text-danger">*</span></label>
                                    <input type="number" name="progress[]" value="{{ $item->progress }}" class="form-control progress-input" max="100" min="0" step="0.01" data-max-allowed="100" placeholder="Maks: 100%" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Nominal</label>
                                    <input type="text" class="form-control nominal-input bg-light"
                                        value="{{ number_format($item->nominal_realisasi, 0, ',', '.') }}" readonly>
                                    <input type="hidden" name="nominal_realisasi[]" value="{{ $item->nominal_realisasi }}" class="nominal-hidden">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Tombol Tambah Termin -->
                    <div class="mt-3">
                        <button type="button" id="addTermin" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Termin
                        </button>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('biaya.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="bi bi-check-circle"></i> Update Semua Termin
                                <span class="spinner-border spinner-border-sm d-none ms-2" id="loadingSpinner"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function () {
        console.log('Document ready - Initializing Edit...');

        // Inisialisasi variabel
        const wrapper = $("#terminWrapper");
        const addBtn = $("#addTermin");
        const warningDiv = $("#totalProgressWarning");
        let kontrakData = {};
        let isAdding = false;

        // Inisialisasi data kontrak dari PHP
        function initKontrakData() {
            @foreach($kontraks as $kontrak)
                kontrakData[{{ $kontrak->id }}] = {
                    nilai: {{ $kontrak->nilai_kontrak }},
                    proyek: "{{ addslashes($kontrak->proyek->nama_proyek ?? 'N/A') }}",
                    bagian: "{{ addslashes($kontrak->bagian) }}",
                    nomor_spk: "{{ addslashes($kontrak->nomor_spk) }}"
                };
            @endforeach
        }

        initKontrakData();

        // Inisialisasi Select2 untuk edit
        try {
            if (typeof $.fn.select2 === 'function') {
                $('#kantorSelect').select2({
                    placeholder: "-- Pilih Kantor --",
                    allowClear: true,
                    width: '100%'
                });

                $('#kontrakSelect').select2({
                    placeholder: "-- Pilih Kontrak --",
                    allowClear: true,
                    width: '100%',
                    templateResult: formatKontrakOption,
                    templateSelection: formatKontrakSelection
                });
                console.log('Select2 initialized successfully for edit');
            } else {
                $('#kantorSelect, #kontrakSelect').addClass('form-control');
            }
        } catch (error) {
            console.error('Error initializing Select2:', error);
            $('#kantorSelect, #kontrakSelect').addClass('form-control');
        }

        // Format tampilan option di dropdown
        function formatKontrakOption(kontrak) {
            if (!kontrak.id) {
                return kontrak.text;
            }

            const kontrakId = kontrak.id;
            const data = kontrakData[kontrakId];

            if (data) {
                return $(
                    '<div class="kontrak-option">' +
                        '<div class="fw-semibold">' + data.proyek + '</div>' +
                        '<div class="small text-muted">' +
                            data.bagian + ' - ' + data.nomor_spk +
                        '</div>' +
                        '<div class="small text-success">Rp ' +
                            new Intl.NumberFormat('id-ID').format(data.nilai) +
                        '</div>' +
                    '</div>'
                );
            }

            return kontrak.text;
        }

        // Format tampilan saat dipilih
        function formatKontrakSelection(kontrak) {
            if (!kontrak.id) {
                return kontrak.text;
            }

            const kontrakId = kontrak.id;
            const data = kontrakData[kontrakId];

            if (data) {
                return data.proyek + ' - ' + data.bagian + ' - ' + data.nomor_spk;
            }

            return kontrak.text;
        }

        // Event ketika kantor berubah - load kontrak sesuai kantor
        $('#kantorSelect').on('change', function() {
            const kantorId = $(this).val();
            console.log('Kantor berubah:', kantorId);

            if (kantorId) {
                loadKontrakByKantor(kantorId);
            } else {
                // Reset kontrak select
                $('#kontrakSelect').empty().append('<option value="">-- Pilih Kontrak --</option>');
                if (typeof $.fn.select2 === 'function') {
                    $('#kontrakSelect').trigger('change.select2');
                }
                kontrakData = {};
            }

            updateSummary();
        });

        // Fungsi untuk load kontrak berdasarkan kantor
        function loadKontrakByKantor(kantorId) {
            console.log('Loading kontrak untuk kantor:', kantorId);

            $('#kontrakSelect').empty().append('<option value="">-- Memuat kontrak... --</option>');
            $('#kontrakSelect').prop('disabled', true);

            if (typeof $.fn.select2 === 'function') {
                $('#kontrakSelect').trigger('change.select2');
            }

            $.ajax({
                url: '/biaya/get-kontrak/' + kantorId,
                type: 'GET',
                success: function(data) {
                    console.log('Data kontrak diterima:', data);

                    $('#kontrakSelect').empty();
                    $('#kontrakSelect').append('<option value="">-- Pilih Kontrak --</option>');

                    kontrakData = {};

                    if (data && data.length > 0) {
                        $.each(data, function(index, kontrak) {
                            kontrakData[kontrak.id] = {
                                nilai: kontrak.nilai_kontrak,
                                proyek: kontrak.proyek_nama,
                                bagian: kontrak.bagian,
                                nomor_spk: kontrak.nomor_spk
                            };

                            $('#kontrakSelect').append(
                                '<option value="' + kontrak.id + '">' +
                                kontrak.proyek_nama + ' - ' +
                                kontrak.bagian + ' - ' +
                                kontrak.nomor_spk + ' - Rp ' +
                                new Intl.NumberFormat('id-ID').format(kontrak.nilai_kontrak) +
                                '</option>'
                            );
                        });

                        // Set kontrak yang sedang diedit jika masih sesuai dengan kantor
                        const currentKontrakId = "{{ $biaya->kontrak_id }}";
                        if (currentKontrakId && kontrakData[currentKontrakId]) {
                            $('#kontrakSelect').val(currentKontrakId);
                        }
                    } else {
                        $('#kontrakSelect').append('<option value="">-- Tidak ada kontrak untuk kantor ini --</option>');
                    }

                    $('#kontrakSelect').prop('disabled', false);

                    if (typeof $.fn.select2 === 'function') {
                        $('#kontrakSelect').trigger('change.select2');
                    }

                    // Hitung ulang nominal setelah kontrak di-load
                    hitungSemuaNominal();
                    updateSummary();
                },
                error: function(xhr, status, error) {
                    console.error('Error mengambil kontrak:', error);
                    $('#kontrakSelect').empty();
                    $('#kontrakSelect').append('<option value="">-- Error memuat kontrak --</option>');
                    $('#kontrakSelect').prop('disabled', false);

                    if (typeof $.fn.select2 === 'function') {
                        $('#kontrakSelect').trigger('change.select2');
                    }
                }
            });
        }

        // 🔹 FUNGSI UTAMA UNTUK EDIT
        function calculateTotalProgress() {
            let total = 0;
            wrapper.find(".progress-input").each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            return total;
        }

        function calculateTotalRealisasi() {
            let total = 0;
            wrapper.find(".nominal-hidden").each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            return total;
        }

        function calculateRemainingProgress() {
            const totalProgress = calculateTotalProgress();
            return Math.max(0, 100 - totalProgress);
        }

        function getNilaiKontrak() {
            const kontrakId = $('#kontrakSelect').val();
            return kontrakData[kontrakId] ? kontrakData[kontrakId].nilai : 0;
        }

        // 🔹 UPDATE SUMMARY SECTION
        function updateSummary() {
            const totalProgress = calculateTotalProgress();
            const totalRealisasi = calculateTotalRealisasi();
            const nilaiKontrak = getNilaiKontrak();
            const sisaKontrak = Math.max(0, nilaiKontrak - totalRealisasi);
            const progressPercentage = nilaiKontrak > 0 ? (totalRealisasi / nilaiKontrak) * 100 : 0;

            $('#summaryTotalProgress').text(totalProgress.toFixed(2) + '%');
            $('#summaryTotalRealisasi').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalRealisasi));
            $('#summarySisaKontrak').text('Rp ' + new Intl.NumberFormat('id-ID').format(sisaKontrak));

            $('#overallProgressBar').css('width', progressPercentage + '%');
            $('#overallProgressText').text(progressPercentage.toFixed(1) + '%');

            // Update warna progress bar
            if (progressPercentage >= 100) {
                $('#overallProgressBar').removeClass('bg-warning bg-primary').addClass('bg-success');
            } else if (progressPercentage >= 80) {
                $('#overallProgressBar').removeClass('bg-primary bg-success').addClass('bg-warning');
            } else {
                $('#overallProgressBar').removeClass('bg-warning bg-success').addClass('bg-primary');
            }
        }

        // 🔹 UPDATE PROGRESS WARNING DAN LIMIT
        function updateProgressLimitsAndValidation() {
            const rows = wrapper.find(".termin-item");
            const terminValues = [];
            const totalProgress = calculateTotalProgress();

            rows.each(function() {
                const row = $(this);
                const progressInput = row.find(".progress-input");
                const terminInput = row.find(".termin-input");

                // Validasi termin unik
                const terminValue = terminInput.val();
                if (terminValue && terminValues.includes(terminValue)) {
                    terminInput.addClass('is-invalid');
                    row.find('.termin-feedback').html('<div class="text-danger small mt-1">Termin sudah digunakan</div>');
                } else {
                    terminInput.removeClass('is-invalid');
                    row.find('.termin-feedback').empty();
                    if (terminValue) terminValues.push(terminValue);
                }

                // Update progress limit
                let othersTotal = 0;
                rows.each(function() {
                    if (this !== row[0]) {
                        othersTotal += parseFloat($(this).find(".progress-input").val()) || 0;
                    }
                });

                const maxAllowed = Math.max(0, 100 - othersTotal);
                progressInput.attr("max", maxAllowed);
                progressInput.attr("data-max-allowed", maxAllowed);
                progressInput.attr("placeholder", `Maks: ${maxAllowed}%`);

                // Jika progress melebihi batas, reset ke batas maksimal
                const currentProgress = parseFloat(progressInput.val()) || 0;
                if (currentProgress > maxAllowed) {
                    progressInput.val(maxAllowed).trigger("input");
                }
            });

            updateProgressWarning();
        }

        function updateProgressWarning() {
            const totalProgress = calculateTotalProgress();
            const remainingProgress = calculateRemainingProgress();

            if (totalProgress > 100) {
                warningDiv.removeClass('d-none alert-success alert-warning').addClass('alert-danger');
                $('#warningText').html(`<i class="bi bi-exclamation-triangle"></i> Total progress <strong>${totalProgress.toFixed(2)}%</strong> melebihi 100%! Silakan kurangi progress.`);
            } else if (totalProgress < 100) {
                warningDiv.removeClass('d-none alert-success alert-danger').addClass('alert-warning');
                $('#warningText').html(`<i class="bi bi-info-circle"></i> Total progress <strong>${totalProgress.toFixed(2)}%</strong> - Sisa progress: <strong>${remainingProgress.toFixed(2)}%</strong>`);
            } else {
                warningDiv.removeClass('d-none alert-warning alert-danger').addClass('alert-success');
                $('#warningText').html(`<i class="bi bi-check-circle"></i> Total progress <strong>${totalProgress.toFixed(2)}%</strong> - Sudah mencapai 100%`);
            }
        }

        function hitungNominal(row) {
            const nilaiKontrak = getNilaiKontrak();
            const progress = parseFloat(row.find(".progress-input").val()) || 0;
            const hasil = (progress / 100) * nilaiKontrak;

            row.find(".nominal-input").val(new Intl.NumberFormat("id-ID").format(hasil));
            row.find(".nominal-hidden").val(hasil.toFixed(0));
        }

        function hitungSemuaNominal() {
            wrapper.find(".termin-item").each(function() {
                hitungNominal($(this));
            });
        }

        function bindEvents(row) {
            row.find(".progress-input").on("input", function() {
                const maxAllowed = parseFloat($(this).attr("data-max-allowed")) || 100;
                let currentValue = parseFloat($(this).val()) || 0;

                if (currentValue > maxAllowed) {
                    $(this).val(maxAllowed);
                    currentValue = maxAllowed;
                }

                if (currentValue < 0) {
                    $(this).val(0);
                    currentValue = 0;
                }

                hitungNominal(row);
                updateProgressLimitsAndValidation();
                updateSummary();
            });

            row.find(".termin-input").on("input", function() {
                updateProgressLimitsAndValidation();
            });

            // ✅ PERBAIKAN: Validasi tanggal hanya untuk field yang kosong
            row.find(".tanggal-input").on("change", function() {
                const tanggal = $(this).val();

                if (!tanggal) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.tanggal-feedback').html('<div class="text-danger small mt-1">Tanggal harus diisi</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.tanggal-feedback').empty();
                }
            });

            row.find(".remove-termin").on("click", function() {
                if (wrapper.find(".termin-item").length > 1) {
                    $(this).closest(".termin-item").remove();
                    updateProgressLimitsAndValidation();
                    updateRemoveButtons();
                    updateSummary();

                    wrapper.find(".progress-input").each(function() {
                        const maxAllowed = parseFloat($(this).attr("data-max-allowed")) || 100;
                        const currentValue = parseFloat($(this).val()) || 0;
                        if (currentValue > maxAllowed) {
                            $(this).val(maxAllowed).trigger("input");
                        }
                    });
                }
            });
        }

        function updateRemoveButtons() {
            const rows = wrapper.find(".termin-item");
            if (rows.length === 1) {
                rows.find(".remove-termin").hide();
            } else {
                rows.find(".remove-termin").show();
            }
        }

        // Template untuk row baru
        function getNewRowTemplate() {
            const today = new Date().toISOString().split('T')[0];
            const remainingProgress = calculateRemainingProgress();
            const nextTermin = wrapper.find(".termin-item").length + 1;

            return `
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
                        <input type="number" name="termin[]" class="form-control termin-input" placeholder="${nextTermin}" required min="1" value="${nextTermin}">
                        <div class="termin-feedback"></div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Progress (%) <span class="text-danger">*</span></label>
                        <input type="number" name="progress[]"
                               class="form-control progress-input"
                               max="${remainingProgress}"
                               min="0"
                               step="0.01"
                               value="${remainingProgress > 0 ? remainingProgress : 0}"
                               data-max-allowed="${remainingProgress}"
                               placeholder="Maks: ${remainingProgress}%"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Realisasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_realisasi[]" class="form-control tanggal-input" required value="${today}">
                        <div class="tanggal-feedback"></div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Nominal Realisasi</label>
                        <input type="text" class="form-control nominal-input bg-light" readonly>
                        <input type="hidden" name="nominal_realisasi[]" class="nominal-hidden">
                    </div>
                </div>
            </div>
            `;
        }

        // Bind events untuk semua row yang sudah ada
        wrapper.find(".termin-item").each(function() {
            bindEvents($(this));
            hitungNominal($(this));
        });

        updateProgressLimitsAndValidation();
        updateRemoveButtons();
        updateSummary();

        // Tambah termin baru
        addBtn.off('click').on("click", function() {
            if (isAdding) {
                return;
            }

            isAdding = true;

            const remainingProgress = calculateRemainingProgress();

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

            const newRow = $(getNewRowTemplate());
            wrapper.append(newRow);
            bindEvents(newRow);
            hitungNominal(newRow);

            updateProgressLimitsAndValidation();
            updateRemoveButtons();
            updateSummary();

            setTimeout(() => {
                isAdding = false;
            }, 500);
        });

        // Update nominal ketika kontrak berubah
        $('#kontrakSelect').on('change', function() {
            wrapper.find(".termin-item").each(function() {
                hitungNominal($(this));
            });
            updateSummary();
        });

        // Validasi form sebelum submit
        $('#biayaForm').on('submit', function(e) {
            e.preventDefault();

            const totalProgress = calculateTotalProgress();
            const hasDuplicateTermin = wrapper.find('.termin-input.is-invalid').length > 0;
            const hasEmptyDate = wrapper.find('.tanggal-input.is-invalid').length > 0;
            const hasEmptyFields = wrapper.find('.progress-input').filter(function() {
                return !$(this).val();
            }).length > 0;
            const kontrakId = $('#kontrakSelect').val();

            let errors = [];

            if (!kontrakId) {
                errors.push('Harus memilih kontrak terlebih dahulu!');
            }

            if (totalProgress > 100) {
                errors.push('Total progress tidak boleh melebihi 100%!');
            }

            if (hasDuplicateTermin) {
                errors.push('Terdapat termin yang duplikat!');
            }

            // ✅ PERBAIKAN: Hapus validasi tanggal tidak boleh melebihi hari ini
            // if (hasFutureDate) {
            //     errors.push('Tanggal realisasi tidak boleh melebihi hari ini!');
            // }

            if (hasEmptyDate) {
                errors.push('Semua tanggal realisasi harus diisi!');
            }

            if (hasEmptyFields) {
                errors.push('Semua field progress harus diisi!');
            }

            if (wrapper.find(".termin-item").length === 0) {
                errors.push('Minimal harus ada satu termin!');
            }

            if (errors.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<ul class="text-start">' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Konfirmasi sebelum update
            Swal.fire({
                title: 'Konfirmasi Update',
                text: 'Apakah Anda yakin ingin mengupdate data biaya ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    $(this).off('submit').submit();
                }
            });
        });

        console.log('Edit JavaScript initialized successfully');
    });
    </script>

    <style>
        .progress-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .progress-input[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }

        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }

        .termin-item {
            transition: all 0.3s ease;
        }

        .termin-item:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        #loadingSpinner {
            width: 1rem;
            height: 1rem;
        }

        /* Style untuk option kontrak di Select2 */
        .kontrak-option {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
        }

        .kontrak-option .fw-semibold {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .kontrak-option .small {
            font-size: 0.875em;
        }

        .select2-results__option--highlighted .kontrak-option {
            background-color: #f8f9fa;
        }
    </style>
</x-layout>
