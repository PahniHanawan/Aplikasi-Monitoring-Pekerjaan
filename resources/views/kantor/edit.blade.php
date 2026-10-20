<x-layout>
    <x-slot:title>{{ $title ?? 'Edit Kantor' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="bi bi-pencil-square me-2"></i> Edit Data Kantor
                        </h3>
                        <div class="badge bg-dark">
                            <i class="bi bi-building me-1"></i> ID: {{ $kantor->id }}
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Informasi Penting -->
                        <div class="alert alert-warning border-0 rounded-3 mb-4">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-5 me-2"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Perhatian!</h6>
                                    <p class="mb-0 small">Perubahan data kantor akan mempengaruhi semua proyek dan biaya yang terkait dengan kantor ini.</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('kantor.update', $kantor->id) }}" method="POST" id="kantorForm" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Nama Kantor -->
                                <div class="col-md-12 mb-4">
                                    <label for="nama_kantor" class="form-label fw-semibold">
                                        Nama Kantor <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-building text-muted"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control @error('nama_kantor') is-invalid @enderror"
                                               id="nama_kantor"
                                               name="nama_kantor"
                                               value="{{ old('nama_kantor', $kantor->nama_kantor) }}"
                                               placeholder="Contoh: Kantor Pusat Jakarta, Kantor Cabang Surabaya"
                                               minlength="3"
                                               maxlength="255"
                                               required
                                               autofocus>
                                        <div class="valid-feedback">
                                            <i class="bi bi-check-circle-fill"></i> Nama kantor valid
                                        </div>
                                        @error('nama_kantor')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <small>
                                            <i class="bi bi-lightbulb text-warning"></i>
                                            Nama kantor harus unik dan tidak boleh sama dengan kantor lain.
                                        </small>
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="col-md-12 mb-4">
                                    <label for="alamat" class="form-label fw-semibold">
                                        Alamat Lengkap
                                        <span class="badge bg-secondary ms-1">Opsional</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light align-items-start pt-2 border-end-0">
                                            <i class="bi bi-geo-alt text-muted"></i>
                                        </span>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                                  id="alamat"
                                                  name="alamat"
                                                  rows="4"
                                                  placeholder="Masukkan alamat lengkap kantor termasuk jalan, nomor, RT/RW, kelurahan, kecamatan, kota, dan kode pos"
                                                  maxlength="500">{{ old('alamat', $kantor->alamat) }}</textarea>
                                        <div class="valid-feedback">
                                            <i class="bi bi-check-circle-fill"></i> Alamat valid
                                        </div>
                                        @error('alamat')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> Maksimal 500 karakter
                                        </small>
                                        <small class="text-muted">
                                            <span id="alamatCounter">{{ strlen($kantor->alamat) }}</span>/500 karakter
                                        </small>
                                    </div>
                                </div>

                                <!-- Pagu Biaya -->
                                <div class="col-md-12 mb-4">
                                    <label for="pagu_biaya" class="form-label fw-semibold">
                                        Pagu Biaya Tahunan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-cash-coin text-muted"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control @error('pagu_biaya') is-invalid @enderror"
                                               id="pagu_biaya"
                                               name="pagu_biaya"
                                               value="{{ old('pagu_biaya', number_format($kantor->pagu_biaya, 0, ',', '.')) }}"
                                               placeholder="0"
                                               required>
                                        <span class="input-group-text bg-light border-start-0">IDR</span>
                                        <div class="valid-feedback">
                                            <i class="bi bi-check-circle-fill"></i> Pagu biaya valid
                                        </div>
                                        @error('pagu_biaya')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <small>
                                            <i class="bi bi-lightbulb text-warning"></i>
                                            Pagu biaya tahunan untuk kantor ini. Akan digunakan sebagai acuan budget proyek.
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-info fw-semibold" id="paguTerbilang">
                                            <!-- Terbilang akan muncul di sini -->
                                        </small>
                                    </div>
                                </div>

                                <!-- Informasi Statistik Kantor -->
                                <div class="col-12">
                                    <div class="card border-info bg-light-info mb-4">
                                        <div class="card-header bg-info bg-opacity-25 border-info">
                                            <h6 class="mb-0 text-info-dark">
                                                <i class="bi bi-graph-up me-2"></i> Statistik Kantor
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <div class="border rounded p-2 bg-white">
                                                        <h5 class="text-primary mb-1">{{ $kantor->proyeks_count ?? 0 }}</h5>
                                                        <small class="text-muted">Total Proyek</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="border rounded p-2 bg-white">
                                                        <h5 class="text-success mb-1">{{ $kantor->biayas_count ?? 0 }}</h5>
                                                        <small class="text-muted">Total Biaya</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="border rounded p-2 bg-white">
                                                        <h5 class="text-warning mb-1">{{ number_format($totalNilaiKontrak ?? 0, 0, ',', '.') }}</h5>
                                                        <small class="text-muted">Nilai Kontrak</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="border rounded p-2 bg-white">
                                                        <h5 class="text-danger mb-1">
                                                            {{ $kantor->created_at->diffForHumans() }}
                                                        </h5>
                                                        <small class="text-muted">Dibuat</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview Data -->
                                <div class="col-12">
                                    <div class="card border-success bg-light-success mb-4">
                                        <div class="card-header bg-success bg-opacity-25 border-success">
                                            <h6 class="mb-0 text-success-dark">
                                                <i class="bi bi-eye me-2"></i> Preview Perubahan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row small">
                                                <div class="col-md-6">
                                                    <strong>Nama Kantor:</strong>
                                                    <span id="previewNama" class="text-muted">{{ $kantor->nama_kantor }}</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Alamat:</strong>
                                                    <span id="previewAlamat" class="text-muted">{{ $kantor->alamat ?: 'Tidak diisi' }}</span>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <strong>Pagu Biaya:</strong>
                                                    <span id="previewPagu" class="text-muted">Rp {{ number_format($kantor->pagu_biaya, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <strong>Status:</strong>
                                                    <span class="badge bg-success">Aktif</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Daftar
                                </a>
                                <div class="btn-group">
                                    <button type="reset" class="btn btn-outline-danger">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset Perubahan
                                    </button>
                                    <button type="submit" class="btn btn-warning px-4" id="submitBtn">
                                        <i class="bi bi-save me-1"></i> Update Kantor
                                        <span class="spinner-border spinner-border-sm d-none ms-2" id="loadingSpinner"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('kantorForm');
        const namaKantorInput = document.getElementById('nama_kantor');
        const alamatInput = document.getElementById('alamat');
        const paguBiayaInput = document.getElementById('pagu_biaya');
        const submitBtn = document.getElementById('submitBtn');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Preview elements
        const previewNama = document.getElementById('previewNama');
        const previewAlamat = document.getElementById('previewAlamat');
        const previewPagu = document.getElementById('previewPagu');
        const paguTerbilang = document.getElementById('paguTerbilang');

        // Format Rupiah untuk pagu biaya
        function formatRupiah(angka) {
            if (!angka) return '';

            const number_string = angka.toString().replace(/[^,\d]/g, '');
            const split = number_string.split(',');
            const sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        // Fungsi untuk membersihkan format Rupiah menjadi angka
        function cleanRupiah(rupiah) {
            return parseInt(rupiah.replace(/[^\d]/g, '')) || 0;
        }

        // Fungsi terbilang
        function terbilang(angka) {
            // Implementasi fungsi terbilang yang sama seperti di create
            const bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

            if (angka < 12) {
                return bilangan[angka];
            } else if (angka < 20) {
                return terbilang(angka - 10) + ' Belas';
            } else if (angka < 100) {
                return terbilang(Math.floor(angka / 10)) + ' Puluh ' + terbilang(angka % 10);
            } else if (angka < 200) {
                return 'Seratus ' + terbilang(angka - 100);
            } else if (angka < 1000) {
                return terbilang(Math.floor(angka / 100)) + ' Ratus ' + terbilang(angka % 100);
            } else if (angka < 2000) {
                return 'Seribu ' + terbilang(angka - 1000);
            } else if (angka < 1000000) {
                return terbilang(Math.floor(angka / 1000)) + ' Ribu ' + terbilang(angka % 1000);
            } else if (angka < 1000000000) {
                return terbilang(Math.floor(angka / 1000000)) + ' Juta ' + terbilang(angka % 1000000);
            } else if (angka < 1000000000000) {
                return terbilang(Math.floor(angka / 1000000000)) + ' Milyar ' + terbilang(angka % 1000000000);
            } else {
                return 'Angka terlalu besar';
            }
        }

        // Event listener untuk pagu biaya
        paguBiayaInput.addEventListener('input', function(e) {
            // Hapus semua karakter non-digit
            let value = this.value.replace(/[^\d]/g, '');

            // Format ke Rupiah
            this.value = formatRupiah(value);

            // Update preview
            updatePreview();

            // Update terbilang
            const numericValue = parseInt(value) || 0;
            if (numericValue > 0) {
                paguTerbilang.textContent = terbilang(numericValue) + ' Rupiah';
                paguTerbilang.classList.add('fw-semibold');
            } else {
                paguTerbilang.textContent = '';
            }
        });

        // Event listener untuk nama kantor
        namaKantorInput.addEventListener('input', function() {
            // Validasi panjang
            if (this.value.length < 3 && this.value.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value.length >= 3) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
            updatePreview();
        });

        // Event listener untuk alamat
        alamatInput.addEventListener('input', function() {
            const counter = document.getElementById('alamatCounter');
            counter.textContent = this.value.length;

            if (this.value.length > 500) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value.length > 0) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
            updatePreview();
        });

        // Update preview data
        function updatePreview() {
            previewNama.textContent = namaKantorInput.value || '-';
            previewAlamat.textContent = alamatInput.value || 'Tidak diisi';
            previewPagu.textContent = paguBiayaInput.value || '-';
        }

        // Inisialisasi terbilang
        const initialPagu = {{ $kantor->pagu_biaya }};
        if (initialPagu > 0) {
            paguTerbilang.textContent = terbilang(initialPagu) + ' Rupiah';
            paguTerbilang.classList.add('fw-semibold');
        }

        // Validasi form sebelum submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let isValid = true;
            const errors = [];

            // Validasi nama kantor
            if (!namaKantorInput.value.trim()) {
                isValid = false;
                errors.push('Nama kantor wajib diisi');
                namaKantorInput.classList.add('is-invalid');
            } else if (namaKantorInput.value.trim().length < 3) {
                isValid = false;
                errors.push('Nama kantor minimal 3 karakter');
                namaKantorInput.classList.add('is-invalid');
            }

            // Validasi pagu biaya
            const paguValue = cleanRupiah(paguBiayaInput.value);
            if (!paguValue || paguValue === 0) {
                isValid = false;
                errors.push('Pagu biaya wajib diisi dan harus lebih dari 0');
                paguBiayaInput.classList.add('is-invalid');
            } else if (paguValue < 1000000) {
                isValid = false;
                errors.push('Pagu biaya minimal Rp 1.000.000');
                paguBiayaInput.classList.add('is-invalid');
            } else if (paguValue > 1000000000000) {
                isValid = false;
                errors.push('Pagu biaya maksimal Rp 1.000.000.000.000');
                paguBiayaInput.classList.add('is-invalid');
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<ul class="text-start">' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            // Konversi nilai pagu biaya ke format angka sebelum submit
            const hiddenPaguInput = document.createElement('input');
            hiddenPaguInput.type = 'hidden';
            hiddenPaguInput.name = 'pagu_biaya';
            hiddenPaguInput.value = paguValue;
            form.appendChild(hiddenPaguInput);

            // Konfirmasi sebelum update
            Swal.fire({
                title: 'Konfirmasi Update',
                text: 'Apakah Anda yakin ingin mengupdate data kantor ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    submitBtn.disabled = true;
                    loadingSpinner.classList.remove('d-none');

                    // Submit form
                    form.submit();
                }
            });
        });

        // Real-time validation
        form.addEventListener('input', function(e) {
            const target = e.target;

            if (target.name === 'nama_kantor') {
                if (target.value.trim().length >= 3) {
                    target.classList.remove('is-invalid');
                    target.classList.add('is-valid');
                } else {
                    target.classList.remove('is-valid');
                }
            }

            if (target.name === 'pagu_biaya') {
                const value = cleanRupiah(target.value);
                if (value && value >= 1000000 && value <= 1000000000000) {
                    target.classList.remove('is-invalid');
                    target.classList.add('is-valid');
                } else {
                    target.classList.remove('is-valid');
                }
            }
        });

        // Reset form handler
        form.addEventListener('reset', function() {
            // Reset semua validasi visual
            const inputs = form.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });

            // Reset preview ke nilai awal
            previewNama.textContent = '{{ $kantor->nama_kantor }}';
            previewAlamat.textContent = '{{ $kantor->alamat ?: 'Tidak diisi' }}';
            previewPagu.textContent = 'Rp {{ number_format($kantor->pagu_biaya, 0, ',', '.') }}';

            // Reset counter
            document.getElementById('alamatCounter').textContent = '{{ strlen($kantor->alamat) }}';

            // Reset terbilang
            paguTerbilang.textContent = terbilang({{ $kantor->pagu_biaya }}) + ' Rupiah';
        });
    });
</script>

    <style>
        .form-control.is-valid {
            border-color: #198754;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .input-group-text {
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-group-text {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .bg-light-info {
            background-color: rgba(13, 202, 240, 0.05) !important;
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.05) !important;
        }

        .text-info-dark {
            color: #055160 !important;
        }

        .text-success-dark {
            color: #0f5132 !important;
        }

        #submitBtn:disabled {
            cursor: not-allowed;
        }
    </style>
</x-layout>
