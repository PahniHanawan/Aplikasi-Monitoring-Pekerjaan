<x-layout>
    <x-slot:title>Manajemen User - Monitoring Proyek Bulog</x-slot:title>
    <x-slot:page-title>Manajemen User</x-slot:page-title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">

            <!-- Header Card -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">
                    <i class="bi bi-people me-2"></i> Manajemen User
                </h3>
                <a href="{{ route('users.create') }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="bi bi-person-plus"></i> Tambah User
                </a>
            </div>

            <!-- Body Card -->
            <div class="card-body">

                <!-- Alert Success -->
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: "{{ session('success') }}",
                                timer: 2500,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: "{{ session('error') }}",
                                timer: 2500,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif

                <!-- Tabel Data User -->
                <div class="table-responsive">
                    <table id="usersTable" class="table table-hover align-middle text-center">
                        <thead class="table-light border-bottom">
                            <tr class="text-primary">
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Telepon</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr class="{{ $user->trashed() ? 'table-danger' : '' }}">
                                    <td class="fw-bold text-muted">{{ $key + 1 }}</td>
                                    <td class="fw-semibold text-start text-dark">
                                        <i class="bi {{ $user->getRoleIcon() }} text-primary me-1"></i>
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info ms-1">Anda</span>
                                        @endif
                                    </td>
                                    <td class="text-secondary">{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->getRoleBadgeColor() }}">
                                            <i class="bi {{ $user->getRoleIcon() }} me-1"></i>
                                            {{ $user->getRoleLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        {!! $user->getStatusBadge() !!}
                                    </td>
                                    <td class="text-muted">
                                        {{ $user->phone ?? '-' }}
                                    </td>
                                    <td class="text-muted">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($user->trashed())
                                                <!-- Restore User -->
                                                <button class="btn btn-sm btn-success"
                                                        onclick="confirmRestore({{ $user->id }})"
                                                        title="Pulihkan User">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                                <!-- Force Delete -->
                                                <button class="btn btn-sm btn-danger"
                                                        onclick="confirmForceDelete({{ $user->id }})"
                                                        title="Hapus Permanen">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            @else
                                                <!-- View -->
                                                <a href="{{ route('users.show', $user->id) }}"
                                                   class="btn btn-sm btn-info text-white"
                                                   title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <!-- Edit -->
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                   class="btn btn-sm btn-warning text-white"
                                                   title="Edit User">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <!-- Change Password -->
                                                <a href="{{ route('users.change-password', $user->id) }}"
                                                   class="btn btn-sm btn-secondary text-white"
                                                   title="Ubah Password">
                                                    <i class="bi bi-key"></i>
                                                </a>
                                                <!-- Delete -->
                                                @if($user->id !== auth()->id())
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete({{ $user->id }})"
                                                            title="Hapus User">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                                            title="Tidak dapat hapus akun sendiri">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- DataTables & SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "order": [[6, "desc"]],
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
                }
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: "Konfirmasi Hapus",
                text: "User akan dipindahkan ke trash. Apakah Anda yakin?",
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

        function confirmRestore(id) {
            Swal.fire({
                title: "Konfirmasi Pemulihan",
                text: "Apakah Anda yakin ingin memulihkan user ini?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Pulihkan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form-' + id).submit();
                }
            });
        }

        function confirmForceDelete(id) {
            Swal.fire({
                title: "Hapus Permanen?",
                text: "Data yang dihapus permanen tidak dapat dikembalikan!",
                icon: "error",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Hapus Permanen",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('force-delete-form-' + id).submit();
                }
            });
        }
    </script>

    <!-- Hidden Forms -->
    @foreach($users as $user)
        @if($user->trashed())
            <form id="restore-form-{{ $user->id }}"
                  action="{{ route('users.restore', $user->id) }}"
                  method="POST" style="display: none;">
                @csrf
                @method('POST')
            </form>
            <form id="force-delete-form-{{ $user->id }}"
                  action="{{ route('users.force-delete', $user->id) }}"
                  method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @else
            <form id="delete-form-{{ $user->id }}"
                  action="{{ route('users.destroy', $user->id) }}"
                  method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <!-- Custom Styling untuk tabel -->
    <style>
        #usersTable tbody tr:nth-child(odd) {
            background-color: #f9fbfd;
        }
        #usersTable tbody tr:hover {
            background-color: #eef5ff;
            transition: background-color 0.2s ease-in-out;
        }
        .table-danger {
            background-color: #f8d7da !important;
        }
    </style>
</x-layout>
