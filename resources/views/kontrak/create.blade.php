<x-layout>
    <x-slot:title>Tambah Kontrak & Biaya</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title m-0">
                    <i class="bi bi-file-earmark-plus me-2"></i> Tambah Kontrak & Biaya
                </h3>
            </div>

            <div class="card-body">
                <!-- SweetAlert untuk success/error akan dihandle di JavaScript -->

                <form action="{{ route('kontrak.store') }}" method="POST" enctype="multipart/form-data" id="kontrakForm">
                    @csrf

                    <!-- SECTION 1: DATA KONTRAK -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-file-text me-2"></i> Data Kontrak</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Proyek -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Proyek <span class="text-danger">*</span></label>
                                    <select name="proyek_id" id="proyekSelect" class="form-select select2" required>
                                        <option value="">-- Pilih Proyek --</option>
                                        @foreach($proyeks as $p)
                                            <option value="{{ $p->id }}"
                                                data-kantor-id="{{ $p->kantor_id }}"
                                                data-kantor="{{ $p->kantor->nama_kantor ?? '-' }}"
                                                {{ old('proyek_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_proyek }} ({{ $p->kantor->nama_kantor ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="proyek-error"></div>
                                </div>

                                <!-- Bagian -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Bagian <span class="text-danger">*</span></label>
                                    <select name="bagian" id="bagianSelect" class="form-select" required>
                                        <option value="">-- Pilih Bagian --</option>
                                        <option value="Perencana" {{ old('bagian') == 'Perencana' ? 'selected' : '' }}>Perencana</option>
                                        <option value="Pelaksana" {{ old('bagian') == 'Pelaksana' ? 'selected' : '' }}>Pelaksana</option>
                                        <option value="Pengawas" {{ old('bagian') == 'Pengawas' ? 'selected' : '' }}>Pengawas</option>
                                        <option value="Pengelola Teknis" {{ old('bagian') == 'Pengelola Teknis' ? 'selected' : '' }}>Pengelola Teknis</option>
                                    </select>
                                    <div class="invalid-feedback" id="bagian-error"></div>
                                    <div id="bagianWarning" class="text-danger small mt-1 d-none"></div>
                                </div>

                                <!-- Mitra -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mitra <span class="text-danger">*</span></label>
                                    <input type="text" name="mitra" class="form-control"
                                           placeholder="Nama perusahaan/penyedia jasa"
                                           value="{{ old('mitra') }}" required>
                                    <div class="invalid-feedback" id="mitra-error"></div>
                                </div>

                                <!-- Nomor SPK -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor SPK <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_spk" class="form-control"
                                           placeholder="Contoh: SPK/001/2024"
                                           value="{{ old('nomor_spk') }}" required>
                                    <div class="invalid-feedback" id="nomor_spk-error"></div>
                                </div>

                                <!-- Tanggal -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal" class="form-control"
                                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                    <div class="invalid-feedback" id="tanggal-error"></div>
                                </div>

                                <!-- Jangka Waktu -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Jangka Waktu</label>
                                    <div class="input-group">
                                        <input type="number" name="jangka_waktu" class="form-control"
                                               placeholder="0" min="0" value="{{ old('jangka_waktu') }}">
                                        <span class="input-group-text">hari</span>
                                    </div>
                                </div>

                                <!-- Masa Pemeliharaan -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Masa Pemeliharaan</label>
                                    <div class="input-group">
                                        <input type="number" name="masa_pemeliharaan" class="form-control"
                                               placeholder="0" min="0" value="{{ old('masa_pemeliharaan') }}">
                                        <span class="input-group-text">hari</span>
                                    </div>
                                </div>

                                <!-- Pagu Biaya -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Pagu Biaya</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="pagu_biaya" id="paguBiaya"
                                               class="form-control" placeholder="0"
                                               value="{{ old('pagu_biaya') }}">
                                    </div>
                                    <div class="invalid-feedback" id="pagu_biaya-error"></div>
                                    <!-- Display format Rupiah untuk Pagu Biaya -->
                                    <div class="form-text" id="paguBiayaText">
                                        {{ old('pagu_biaya') ? format_currency(old('pagu_biaya')) : '' }}
                                    </div>
                                    <!-- Display dalam bentuk terbilang -->
                                    <div class="form-text small text-muted mt-1" id="paguBiayaTerbilang">
                                        {{ old('pagu_biaya') ? terbilang_rupiah(old('pagu_biaya')) : '' }}
                                    </div>
                                </div>

                                <!-- Nilai Kontrak -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nilai Kontrak <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="nilai_kontrak" id="nilaiKontrak"
                                               class="form-control" placeholder="0" required
                                               value="{{ old('nilai_kontrak') }}">
                                    </div>
                                    <div class="invalid-feedback" id="nilai_kontrak-error"></div>
                                    <div class="form-text" id="nilaiKontrakText">
                                        {{ old('nilai_kontrak') ? format_currency(old('nilai_kontrak')) : '' }}
                                    </div>
                                    <!-- Display dalam bentuk terbilang -->
                                    <div class="form-text small text-muted mt-1" id="nilaiKontrakTerbilang">
                                        {{ old('nilai_kontrak') ? terbilang_rupiah(old('nilai_kontrak')) : '' }}
                                    </div>
                                </div>

                                <!-- File SPK -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Upload File SPK</label>
                                    <input type="file" name="file_spk" id="fileSpk" class="form-control"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <div class="invalid-feedback" id="file_spk-error"></div>
                                    <div class="form-text">Maksimal 10MB. Format: PDF, DOC, DOCX, JPG, JPEG, PNG</div>
                                    <!-- File Preview -->
                                    <div id="filePreview" class="mt-2 d-none">
                                        <div class="alert alert-info py-2">
                                            <i class="bi bi-file-earmark me-2"></i>
                                            <span id="fileName"></span>
                                            <small class="text-muted" id="fileSize"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: TERMIN BIAYA -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Termin Biaya (Progress & Realisasi)</h5>
                            <button type="button" id="addTerminBtn" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Termin
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Summary -->
                            <div class="alert alert-info mb-4">
                                <div class="row">
                                    <div class="col-md-2">
                                        <small class="text-muted">Pagu Biaya</small>
                                        <div id="summaryPaguBiaya" class="fw-bold">Rp 0</div>
                                        <small id="summaryPaguBiayaTerbilang" class="text-muted"></small>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Nilai Kontrak</small>
                                        <div id="summaryNilaiKontrak" class="fw-bold">Rp 0</div>
                                        <small id="summaryNilaiKontrakTerbilang" class="text-muted"></small>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Total Progress</small>
                                        <div id="summaryTotalProgress" class="fw-bold">0%</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Total Realisasi</small>
                                        <div id="summaryTotalRealisasi" class="fw-bold">Rp 0</div>
                                        <small id="summaryTotalRealisasiTerbilang" class="text-muted"></small>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Sisa Kontrak</small>
                                        <div id="summarySisaKontrak" class="fw-bold text-success">Rp 0</div>
                                        <small id="summarySisaKontrakTerbilang" class="text-success"></small>
                                    </div>
                                </div>
                                <div id="progressWarning" class="text-danger mt-2 d-none">
                                    <i class="bi bi-exclamation-triangle"></i> Total progress melebihi 100%!
                                </div>
                                <!-- Tampilkan selisih Pagu Biaya vs Nilai Kontrak -->
                                <div id="paguComparison" class="text-muted small mt-2 d-none">
                                    <span id="selisihPagu"></span>
                                </div>
                            </div>

                            <!-- Table Header -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">Jenis</th>
                                            <th width="10%">Termin</th>
                                            <th width="15%">Progress (%)</th>
                                            <th width="20%">Tanggal Realisasi</th>
                                            <th width="20%">Nominal Realisasi</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="terminContainer">
                                        <!-- Termin akan ditambahkan dinamis -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty State -->
                            <div id="emptyTermin" class="text-center py-5">
                                <i class="bi bi-inbox display-5 text-muted"></i>
                                <p class="text-muted mt-3">Belum ada termin biaya</p>
                                <p class="text-muted small">Klik "Tambah Termin" untuk menambah data</p>
                            </div>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('kontrak.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2" id="resetBtn">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="bi bi-check-circle"></i> Simpan Kontrak & Biaya
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Termin Template (Hidden) -->
    <template id="terminTemplate">
        <tr class="termin-row">
            <td class="align-middle text-center">
                <span class="termin-number">1</span>
            </td>
            <td>
                <select name="biaya[__INDEX__][jenis]" class="form-control form-control-sm jenis-select">
                    <option value="Masa Pelaksanaan">Masa Pelaksanaan</option>
                    <option value="Masa Pemeliharaan">Masa Pemeliharaan</option>
                </select>
            </td>
            <td>
                <input type="number" name="biaya[__INDEX__][termin]"
                       class="form-control form-control-sm termin-input" min="1" value="__TERMIN__">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" name="biaya[__INDEX__][progress]"
                           class="form-control form-control-sm progress-input"
                           min="0" max="100" step="0.01" value="0">
                    <span class="input-group-text">%</span>
                </div>
                <small class="max-progress-text text-muted"></small>
            </td>
            <td>
                <input type="date" name="biaya[__INDEX__][tanggal_realisasi]" class="form-control form-control-sm tanggal-input" value="{{ date('Y-m-d') }}">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm nominal-display" readonly>
                <input type="hidden" name="biaya[__INDEX__][nominal_realisasi]" class="nominal-hidden">
                <small class="nominal-terbilang text-muted"></small>
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger remove-termin">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    </template>

    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // ================ VARIABLES ================
    let terminCounter = 0;
    let nilaiKontrak = 0;
    let paguBiaya = 0;
    let hasValidationErrors = false;

    // ================ SWEETALERT SUCCESS/ERROR HANDLER ================
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            html: `{!! session('success') !!}`,
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: `{!! session('error') !!}`,
            timer: 4000,
            showConfirmButton: true
        });
    @endif

    @if ($errors->any())
        hasValidationErrors = true;
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += `• {!! $error !!}<br>`;
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: errorMessages,
            showConfirmButton: true,
            confirmButtonText: 'Perbaiki'
        });
    @endif

    // ================ FORMAT NUMBER FUNCTIONS ================
    function formatNumber(num) {
        if (isNaN(num) || num === null) return '';
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function parseNumber(str) {
        if (!str) return 0;
        const cleaned = str.toString().replace(/[^\d]/g, '');
        return parseInt(cleaned) || 0;
    }

    function formatCurrency(num) {
        if (isNaN(num) || num === null || num === '') {
            return 'Rp 0';
        }
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(num);
    }

    // ================ FUNGSI TERBILANG RUPIAH ================
    function terbilangRupiah(num) {
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
            if (sisa > 0) {
                result += ' ' + sisa;
            }
            return result + ' Rupiah';
        } else if (angka < 1000000000) {
            const juta = Math.floor(angka / 1000000);
            const sisa = angka % 1000000;
            let result = juta + ' Juta';
            if (sisa >= 1000) {
                const ribu = Math.floor(sisa / 1000);
                const sisaRibu = sisa % 1000;
                result += ' ' + ribu + ' Ribu';
                if (sisaRibu > 0) {
                    result += ' ' + sisaRibu;
                }
            } else if (sisa > 0) {
                result += ' ' + sisa;
            }
            return result + ' Rupiah';
        } else if (angka < 1000000000000) {
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

                    if (sisaRibu > 0) {
                        result += ' ' + sisaRibu;
                    }
                } else if (sisaJuta > 0) {
                    result += ' ' + sisaJuta;
                }
            } else if (sisa >= 1000) {
                const ribu = Math.floor(sisa / 1000);
                const sisaRibu = sisa % 1000;
                result += ' ' + ribu + ' Ribu';

                if (sisaRibu > 0) {
                    result += ' ' + sisaRibu;
                }
            } else if (sisa > 0) {
                result += ' ' + sisa;
            }

            return result + ' Rupiah';
        } else {
            const triliun = Math.floor(angka / 1000000000000);
            const sisa = angka % 1000000000000;
            let result = triliun + ' Triliun';

            if (sisa >= 1000000000) {
                const miliar = Math.floor(sisa / 1000000000);
                const sisaMiliar = sisa % 1000000000;
                result += ' ' + miliar + ' Miliar';

                if (sisaMiliar >= 1000000) {
                    const juta = Math.floor(sisaMiliar / 1000000);
                    const sisaJuta = sisaMiliar % 1000000;
                    result += ' ' + juta + ' Juta';

                    if (sisaJuta >= 1000) {
                        const ribu = Math.floor(sisaJuta / 1000);
                        const sisaRibu = sisaJuta % 1000;
                        result += ' ' + ribu + ' Ribu';

                        if (sisaRibu > 0) {
                            result += ' ' + sisaRibu;
                        }
                    } else if (sisaJuta > 0) {
                        result += ' ' + sisaJuta;
                    }
                } else if (sisaMiliar >= 1000) {
                    const ribu = Math.floor(sisaMiliar / 1000);
                    const sisaRibu = sisaMiliar % 1000;
                    result += ' ' + ribu + ' Ribu';

                    if (sisaRibu > 0) {
                        result += ' ' + sisaRibu;
                    }
                } else if (sisaMiliar > 0) {
                    result += ' ' + sisaMiliar;
                }
            } else if (sisa >= 1000000) {
                const juta = Math.floor(sisa / 1000000);
                const sisaJuta = sisa % 1000000;
                result += ' ' + juta + ' Juta';

                if (sisaJuta >= 1000) {
                    const ribu = Math.floor(sisaJuta / 1000);
                    const sisaRibu = sisaJuta % 1000;
                    result += ' ' + ribu + ' Ribu';

                    if (sisaRibu > 0) {
                        result += ' ' + sisaRibu;
                    }
                } else if (sisaJuta > 0) {
                    result += ' ' + sisaJuta;
                }
            } else if (sisa >= 1000) {
                const ribu = Math.floor(sisa / 1000);
                const sisaRibu = sisa % 1000;
                result += ' ' + ribu + ' Ribu';

                if (sisaRibu > 0) {
                    result += ' ' + sisaRibu;
                }
            } else if (sisa > 0) {
                result += ' ' + sisa;
            }

            return result + ' Rupiah';
        }
    }

    // ================ FUNGSI TERBILANG SINGKAT ================
    function terbilangSingkat(num) {
        if (isNaN(num) || num === null || num === 0 || num === '') {
            return '';
        }

        const angka = parseInt(num);

        if (angka < 1000) {
            return angka + ' Rupiah';
        } else if (angka < 1000000) {
            const ribu = (angka / 1000).toFixed(angka % 1000 === 0 ? 0 : 1);
            return ribu + ' Ribu';
        } else if (angka < 1000000000) {
            const juta = (angka / 1000000).toFixed(angka % 1000000 === 0 ? 0 : 2);
            return juta + ' Juta';
        } else if (angka < 1000000000000) {
            const miliar = (angka / 1000000000).toFixed(angka % 1000000000 === 0 ? 0 : 2);
            return miliar + ' Miliar';
        } else {
            const triliun = (angka / 1000000000000).toFixed(angka % 1000000000000 === 0 ? 0 : 2);
            return triliun + ' Triliun';
        }
    }

    // ================ FILE VALIDATION ================
    function validateFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['application/pdf', 'application/msword',
                             'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                             'image/jpeg', 'image/jpg', 'image/png'];

        if (file.size > maxSize) {
            return {
                valid: false,
                message: 'Ukuran file maksimal 10MB'
            };
        }

        if (!allowedTypes.includes(file.type)) {
            return {
                valid: false,
                message: 'Format file tidak didukung. Gunakan PDF, DOC, DOCX, JPG, JPEG, atau PNG.'
            };
        }

        return { valid: true, message: 'File valid' };
    }

    // ================ FILE PREVIEW ================
    $('#fileSpk').on('change', function(e) {
        const file = e.target.files[0];
        const preview = $('#filePreview');
        const fileName = $('#fileName');
        const fileSize = $('#fileSize');
        const fileError = $('#file_spk-error');

        if (file) {
            const validation = validateFile(file);

            if (!validation.valid) {
                $(this).val('');
                preview.addClass('d-none');
                fileError.text(validation.message).show();
                $(this).addClass('is-invalid');
                return;
            }

            // Format file size
            const formatFileSize = (bytes) => {
                if (bytes >= 1048576) {
                    return (bytes / 1048576).toFixed(2) + ' MB';
                } else if (bytes >= 1024) {
                    return (bytes / 1024).toFixed(2) + ' KB';
                } else {
                    return bytes + ' bytes';
                }
            };

            fileName.text(file.name);
            fileSize.text(' (' + formatFileSize(file.size) + ')');
            preview.removeClass('d-none');
            fileError.text('').hide();
            $(this).removeClass('is-invalid');
        } else {
            preview.addClass('d-none');
            fileError.text('').hide();
            $(this).removeClass('is-invalid');
        }
    });

    // ================ INITIALIZE SELECT2 ================
    if ($.fn.select2) {
        $('#proyekSelect').select2({
            placeholder: "-- Pilih Proyek --",
            width: '100%'
        });
    }

    // ================ UPDATE SUMMARY ================
    function updateSummary() {
        const nilaiInput = parseNumber($('#nilaiKontrak').val());
        const paguInput = parseNumber($('#paguBiaya').val());
        nilaiKontrak = nilaiInput;
        paguBiaya = paguInput;

        // Update nilai kontrak display
        $('#summaryNilaiKontrak').text(formatCurrency(nilaiInput));
        $('#nilaiKontrakText').html(`
            <span class="text-success">${formatCurrency(nilaiInput)}</span>
        `);
        $('#nilaiKontrakTerbilang').text(terbilangRupiah(nilaiInput));
        $('#summaryNilaiKontrakTerbilang').text(terbilangSingkat(nilaiInput));

        // Update pagu biaya display
        $('#summaryPaguBiaya').text(formatCurrency(paguInput));
        $('#paguBiayaText').html(`
            <span class="text-info">${formatCurrency(paguInput)}</span>
        `);
        $('#paguBiayaTerbilang').text(terbilangRupiah(paguInput));
        $('#summaryPaguBiayaTerbilang').text(terbilangSingkat(paguInput));

        // Hitung total progress dan realisasi
        let totalProgress = 0;
        let totalRealisasi = 0;

        $('.progress-input').each(function() {
            totalProgress += parseFloat($(this).val()) || 0;

            const progress = parseFloat($(this).val()) || 0;
            const nominal = (progress / 100) * nilaiKontrak;
            totalRealisasi += nominal;

            // Update nominal display
            const row = $(this).closest('tr');
            row.find('.nominal-display').val(formatCurrency(nominal));
            row.find('.nominal-hidden').val(nominal);
            row.find('.nominal-terbilang').text(terbilangSingkat(nominal));
        });

        // Update termin numbers
        $('.termin-row').each(function(index) {
            $(this).find('.termin-number').text(index + 1);
        });

        // Update summary displays
        $('#summaryTotalProgress').text(totalProgress.toFixed(2) + '%');

        const totalRealisasiFormatted = formatCurrency(totalRealisasi);
        $('#summaryTotalRealisasi').text(totalRealisasiFormatted);
        $('#summaryTotalRealisasiTerbilang').text(terbilangSingkat(totalRealisasi));

        const sisaKontrak = nilaiKontrak - totalRealisasi;
        const sisaKontrakFormatted = formatCurrency(sisaKontrak);
        $('#summarySisaKontrak').text(sisaKontrakFormatted);
        $('#summarySisaKontrakTerbilang').text(terbilangSingkat(sisaKontrak));

        // Hitung dan tampilkan selisih Pagu Biaya vs Nilai Kontrak
        const selisih = nilaiInput - paguInput;
        if (selisih !== 0) {
            const selisihAbs = Math.abs(selisih);
            const selisihText = selisih > 0 ?
                `Nilai kontrak melebihi pagu: ${formatCurrency(selisih)} (${terbilangSingkat(selisihAbs)})` :
                `Nilai kontrak di bawah pagu: ${formatCurrency(selisihAbs)} (${terbilangSingkat(selisihAbs)})`;
            $('#selisihPagu').html(`<i class="bi bi-info-circle"></i> ${selisihText}`);
            $('#paguComparison').removeClass('d-none');
        } else {
            $('#paguComparison').addClass('d-none');
        }

        // Show/hide warning
        if (totalProgress > 100) {
            $('#progressWarning').removeClass('d-none');
            $('#submitBtn').prop('disabled', true);
        } else {
            $('#progressWarning').addClass('d-none');
            $('#submitBtn').prop('disabled', false);
        }

        // Update max progress allowed for each input
        updateMaxProgress();

        // Show/hide empty state
        if ($('.termin-row').length > 0) {
            $('#emptyTermin').hide();
        } else {
            $('#emptyTermin').show();
        }
    }

    // ================ UPDATE MAX PROGRESS ================
    function updateMaxProgress() {
        $('.progress-input').each(function() {
            const currentRow = $(this).closest('tr');
            const currentProgress = parseFloat($(this).val()) || 0;

            // Hitung total progress dari row lain
            let otherProgress = 0;
            $('.progress-input').each(function() {
                if ($(this).closest('tr')[0] !== currentRow[0]) {
                    otherProgress += parseFloat($(this).val()) || 0;
                }
            });

            const maxAllowed = 100 - otherProgress;
            $(this).attr('max', maxAllowed);
            $(this).next('.max-progress-text').text(`Maks: ${maxAllowed.toFixed(2)}%`);

            // Jika current melebihi max allowed, adjust
            if (currentProgress > maxAllowed) {
                $(this).val(maxAllowed.toFixed(2));
            }
        });
    }

    // ================ ADD TERMIN ================
    $('#addTerminBtn').click(function() {
        const template = document.getElementById('terminTemplate').innerHTML;
        const newHtml = template
            .replace(/__INDEX__/g, terminCounter)
            .replace(/__TERMIN__/g, terminCounter + 1);

        $('#terminContainer').append(newHtml);
        terminCounter++;

        updateSummary();
    });

    // ================ REMOVE TERMIN ================
    $(document).on('click', '.remove-termin', function() {
        if ($('.termin-row').length > 1) {
            $(this).closest('tr').remove();
            updateSummary();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Minimal harus ada satu termin!',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });

    // ================ EVENT LISTENERS ================
    // Nilai kontrak change
    $('#nilaiKontrak').on('input', function() {
        let value = $(this).val().replace(/\./g, '');
        if (value) {
            $(this).val(formatNumber(parseInt(value)));
        }
        updateSummary();
    });

    // Pagu biaya change
    $('#paguBiaya').on('input', function() {
        let value = $(this).val().replace(/\./g, '');
        if (value) {
            $(this).val(formatNumber(parseInt(value)));
        }
        updateSummary();
    });

    // Progress input change
    $(document).on('input', '.progress-input', function() {
        updateSummary();
    });

    // Termin input change
    $(document).on('input', '.termin-input', function() {
        // Validasi termin unik
        const terminValue = $(this).val();
        const terminValues = [];
        let duplicate = false;

        $('.termin-input').each(function() {
            const val = $(this).val();
            if (val && terminValues.includes(val)) {
                duplicate = true;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
                if (val) terminValues.push(val);
            }
        });

        if (duplicate) {
            $('#progressWarning').html('<i class="bi bi-exclamation-triangle"></i> Terdapat termin yang duplikat!').removeClass('d-none');
            $('#submitBtn').prop('disabled', true);
        }
    });

    // Proyek change - cek bagian duplikat
    $('#proyekSelect').change(function() {
        const proyekId = $(this).val();
        const bagian = $('#bagianSelect').val();

        if (proyekId && bagian) {
            checkDuplicateBagian(proyekId, bagian);
        }
    });

    // Bagian change - cek duplikat
    $('#bagianSelect').change(function() {
        const proyekId = $('#proyekSelect').val();
        const bagian = $(this).val();

        if (proyekId && bagian) {
            checkDuplicateBagian(proyekId, bagian);
        }
    });

    // ================ CHECK DUPLICATE BAGIAN ================
    function checkDuplicateBagian(proyekId, bagian) {
        $.ajax({
            url: '{{ route("api.check-bagian-duplicate") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                proyek_id: proyekId,
                bagian: bagian
            },
            success: function(response) {
                if (response.exists) {
                    $('#bagianWarning').html('<i class="bi bi-exclamation-triangle"></i> Bagian ini sudah ada untuk proyek yang dipilih!').removeClass('d-none');
                    $('#submitBtn').prop('disabled', true);
                } else {
                    $('#bagianWarning').text('').addClass('d-none');
                    $('#submitBtn').prop('disabled', false);
                }
            },
            error: function(xhr) {
                console.error('Error checking duplicate bagian:', xhr.responseText);
            }
        });
    }

    // ================ FORM VALIDATION & SUBMISSION ================
    $('#kontrakForm').submit(function(e) {
        e.preventDefault();

        // Validasi client-side dengan SweetAlert
        const proyekId = $('#proyekSelect').val();
        const bagian = $('#bagianSelect').val();
        const mitra = $('input[name="mitra"]').val();
        const nomorSpk = $('input[name="nomor_spk"]').val();
        const tanggal = $('input[name="tanggal"]').val();
        const nilaiKontrakVal = parseNumber($('#nilaiKontrak').val());
        const totalProgress = parseFloat($('#summaryTotalProgress').text()) || 0;
        const fileInput = $('#fileSpk')[0];

        let errors = [];

        if (!proyekId) errors.push('Pilih proyek terlebih dahulu');
        if (!bagian) errors.push('Pilih bagian kontrak');
        if (!mitra.trim()) errors.push('Masukkan nama mitra');
        if (!nomorSpk.trim()) errors.push('Masukkan nomor SPK');
        if (!tanggal) errors.push('Pilih tanggal kontrak');
        if (!nilaiKontrakVal || nilaiKontrakVal <= 0) errors.push('Nilai kontrak harus lebih dari 0');
        if (totalProgress > 100) errors.push('Total progress tidak boleh lebih dari 100%');

        // Validasi termin duplikat
        const terminValues = [];
        $('.termin-input').each(function() {
            const val = $(this).val();
            if (val && terminValues.includes(val)) {
                errors.push('Terdapat termin yang duplikat');
                return false;
            }
            if (val) terminValues.push(val);
        });

        // Validasi tanggal realisasi tidak boleh kosong
        $('.tanggal-input').each(function() {
            if (!$(this).val()) {
                errors.push('Tanggal realisasi tidak boleh kosong');
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Validasi file jika ada
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const validation = validateFile(file);
            if (!validation.valid) {
                errors.push(validation.message);
            }
        }

        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errors.map(error => `• ${error}`).join('<br>'),
                showConfirmButton: true,
                confirmButtonText: 'Perbaiki'
            });
            return false;
        }

        // Konfirmasi dengan SweetAlert
        Swal.fire({
            title: 'Konfirmasi Penyimpanan',
            html: `
                <p>Apakah Anda yakin ingin menyimpan data kontrak ini?</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang menyimpan data kontrak',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form
                this.submit();
            }
        });
    });

    // ================ RESET FORM ================
    $('#resetBtn').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Reset Form',
            text: 'Apakah Anda yakin ingin mereset semua data yang sudah diisi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#kontrakForm')[0].reset();
                $('#terminContainer').empty();
                terminCounter = 0;
                nilaiKontrak = 0;
                paguBiaya = 0;
                updateSummary();
                $('#filePreview').addClass('d-none');
                $('#paguComparison').addClass('d-none');

                // Reset Select2
                if ($.fn.select2) {
                    $('#proyekSelect').val('').trigger('change');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Form telah direset',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });

    // ================ INITIAL SETUP ================
    // Tambah termin pertama jika tidak ada error validation dari server
    if (!hasValidationErrors) {
        $('#addTerminBtn').click();
    }

    // Format nilai kontrak awal
    if ($('#nilaiKontrak').val()) {
        $('#nilaiKontrak').trigger('input');
    }

    // Format pagu biaya awal
    if ($('#paguBiaya').val()) {
        $('#paguBiaya').trigger('input');
    }
});
    </script>

    <style>
        .select2-container .select2-selection--single {
            height: 45px;
            border-radius: 0.375rem;
        }

        .termin-row:hover {
            background-color: #f8f9fa;
        }

        .progress-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .nominal-display {
            background-color: #e9ecef;
            font-weight: 500;
        }

        .max-progress-text {
            font-size: 0.75rem;
            display: block;
            margin-top: 2px;
        }

        #emptyTermin {
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: #dc3545;
        }

        .form-control.is-invalid ~ .invalid-feedback,
        .form-select.is-invalid ~ .invalid-feedback {
            display: block;
        }

        /* Style untuk display format Rupiah */
        #paguBiayaText, #nilaiKontrakText {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            padding: 0.25rem 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
            border-left: 3px solid #0d6efd;
        }

        #paguBiayaText {
            border-left-color: #17a2b8;
        }

        #nilaiKontrakText {
            border-left-color: #28a745;
        }

        #selisihPagu {
            font-style: italic;
            font-size: 0.875rem;
        }

        /* Style untuk terbilang */
        #paguBiayaTerbilang, #nilaiKontrakTerbilang {
            font-size: 0.8rem;
            color: #6c757d;
            font-style: italic;
        }

        .nominal-terbilang {
            font-size: 0.75rem;
            display: block;
            margin-top: 2px;
            color: #6c757d;
            font-style: italic;
        }

        #summaryPaguBiayaTerbilang,
        #summaryNilaiKontrakTerbilang,
        #summaryTotalRealisasiTerbilang,
        #summarySisaKontrakTerbilang {
            font-size: 0.75rem;
            display: block;
            margin-top: 2px;
            font-style: italic;
        }

        /* Highlight untuk nilai besar */
        .highlight-miliar {
            color: #dc3545;
            font-weight: bold;
        }

        .highlight-juta {
            color: #fd7e14;
            font-weight: bold;
        }

        .highlight-ribu {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</x-layout>
