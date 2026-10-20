<x-layout>
    <x-slot:title>{{ $title ?? 'Daftar Kantor' }}</x-slot:title>

    <!-- Load DataTables CSS di sini -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">
                    <i class="bi bi-building me-2"></i> Data Kantor
                </h3>
                <a href="{{ route('kantor.create') }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Kantor
                </a>
            </div>

            <div class="card-body">
                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Alert Error -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Statistik Ringkas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0">{{ $kantors->count() }}</h4>
                                        <small>Total Kantor</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-building fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0">{{ $kantors->sum('proyeks_count') }}</h4>
                                        <small>Total Proyek</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-briefcase fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0">Rp {{ number_format($kantors->sum('pagu_biaya'), 0, ',', '.') }}</h4>
                                        <small>Total Pagu Biaya</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-currency-dollar fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0">Rp {{ number_format($kantors->sum('total_nilai_proyek'), 0, ',', '.') }}</h4>
                                        <small>Total Nilai Kontrak</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-graph-up fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data Kantor -->
                <div class="table-responsive">
                    <table id="kantorTable" class="table align-middle text-center table-hover">
                        <thead class="table-light border-bottom">
                            <tr class="text-primary">
                                <th>No</th>
                                <th>Nama Kantor</th>
                                <th>Alamat</th>
                                <th>Pagu Biaya</th>
                                <th>Jumlah Proyek</th>
                                <th>Total Nilai Kontrak</th>
                                <th>Sisa Pagu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kantors as $key => $k)
                                @php
                                    $sisaPagu = $k->pagu_biaya - $k->total_nilai_proyek;
                                @endphp
                                <tr>
                                    <td class="fw-bold text-muted">{{ $key + 1 }}</td>
                                    <td class="fw-semibold text-start text-dark">
                                        <i class="bi bi-building text-primary me-1"></i>
                                        {{ $k->nama_kantor }}
                                    </td>
                                    <td class="text-muted">{{ $k->alamat ?? '-' }}</td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($k->pagu_biaya, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $k->proyeks_count }}</span>
                                    </td>
                                    <td class="fw-bold text-info">
                                        Rp {{ number_format($k->total_nilai_proyek, 0, ',', '.') }}
                                    </td>
                                    <td class="fw-bold {{ $sisaPagu >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($sisaPagu, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('kantor.edit', $k->id) }}"
                                               class="btn btn-sm btn-warning text-white"
                                               title="Edit Kantor">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $k->id }}, '{{ $k->nama_kantor }}')"
                                                title="Hapus Kantor"
                                                {{ $k->proyeks_count > 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $k->id }}"
                                            action="{{ route('kantor.destroy', $k->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styling untuk tabel -->
    <style>
        #kantorTable tbody tr:nth-child(odd) {
            background-color: #f9fbfd;
        }
        #kantorTable tbody tr:hover {
            background-color: #eef5ff;
            transition: background-color 0.2s ease-in-out;
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .card {
            border: none;
        }
        .card .card-body {
            padding: 1rem;
        }
        .card h4 {
            font-size: 1.5rem;
        }
        .card small {
            font-size: 0.8rem;
            opacity: 0.9;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.375rem !important;
            margin: 0 2px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0d6efd !important;
            color: white !important;
            border: none;
        }
    </style>

    <!-- Script untuk DataTables -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tunggu sampai jQuery dan semua library siap
            if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                initializeDataTable();
            } else {
                // Jika DataTables belum tersedia, tunggu sebentar
                setTimeout(initializeDataTable, 500);
            }
        });

        function initializeDataTable() {
            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTables is not available');
                return;
            }

            $('#kantorTable').DataTable({
                "order": [[1, "asc"]],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data",
                    "zeroRecords": "Data tidak ditemukan",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "→",
                        "previous": "←"
                    }
                },
                "responsive": true,
                "autoWidth": false
            });
        }

        function confirmDelete(id, namaKantor) {
            if (typeof Swal === 'undefined') {
                if (confirm(`Apakah Anda yakin ingin menghapus kantor ${namaKantor}?`)) {
                    document.getElementById('delete-form-' + id).submit();
                }
                return;
            }

            Swal.fire({
                title: "Konfirmasi Hapus",
                html: `Apakah Anda yakin ingin menghapus kantor <strong>${namaKantor}</strong>?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-layout>
