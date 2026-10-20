<x-layout>
    <x-slot:title>Detail User - Monitoring Proyek Bulog</x-slot:title>
    <x-slot:page-title>Detail User - {{ $user->name }}</x-slot:page-title>

    <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-3">

                    <!-- Header Card -->
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="bi bi-person-circle me-2"></i> Detail User - {{ $user->name }}
                        </h3>
                        <div>
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm shadow-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="card-body">
                        <!-- Alert jika user dinonaktifkan -->
                        @if(!$user->is_active)
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>User dinonaktifkan!</strong> User ini tidak dapat login ke sistem.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Alert jika user dihapus (soft delete) -->
                        @if($user->trashed())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-trash me-2"></i>
                                <strong>User dihapus!</strong> User ini berada di trash dan tidak dapat login.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Informasi Utama -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0 text-primary">
                                            <i class="bi bi-info-circle me-2"></i>Informasi Utama
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-4 fw-semibold text-muted">Nama Lengkap</div>
                                            <div class="col-8">
                                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                                                @if($user->id === auth()->id())
                                                    <span class="badge bg-info ms-2">Anda</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-4 fw-semibold text-muted">Email</div>
                                            <div class="col-8">
                                                <span class="text-dark">{{ $user->email }}</span>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success ms-2">
                                                        <i class="bi bi-check-circle me-1"></i>Terverifikasi
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning ms-2">
                                                        <i class="bi bi-exclamation-circle me-1"></i>Belum Verifikasi
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-4 fw-semibold text-muted">Role</div>
                                            <div class="col-8">
                                                <span class="badge bg-{{ $user->getRoleBadgeColor() }}">
                                                    <i class="bi {{ $user->getRoleIcon() }} me-1"></i>
                                                    {{ $user->getRoleLabel() }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-4 fw-semibold text-muted">Status</div>
                                            <div class="col-8">
                                                {!! $user->getStatusBadge() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Kontak -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0 text-primary">
                                            <i class="bi bi-telephone me-2"></i>Informasi Kontak
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-4 fw-semibold text-muted">Telepon</div>
                                            <div class="col-8">
                                                @if($user->phone)
                                                    <span class="text-dark">{{ $user->phone }}</span>
                                                @else
                                                    <span class="text-muted fst-italic">-</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-4 fw-semibold text-muted">Alamat</div>
                                            <div class="col-8">
                                                @if($user->address)
                                                    <span class="text-dark">{{ $user->address }}</span>
                                                @else
                                                    <span class="text-muted fst-italic">-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Sistem -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0 text-primary">
                                            <i class="bi bi-clock-history me-2"></i>Informasi Sistem
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <div class="text-center p-3 bg-light rounded-3">
                                                    <i class="bi bi-calendar-plus text-primary fs-4 mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">Dibuat</h6>
                                                    <p class="mb-0 text-muted small">
                                                        {{ $user->created_at->format('d/m/Y') }}<br>
                                                        <small>{{ $user->created_at->format('H:i:s') }}</small>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="text-center p-3 bg-light rounded-3">
                                                    <i class="bi bi-calendar-check text-success fs-4 mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">Diupdate</h6>
                                                    <p class="mb-0 text-muted small">
                                                        {{ $user->updated_at->format('d/m/Y') }}<br>
                                                        <small>{{ $user->updated_at->format('H:i:s') }}</small>
                                                    </p>
                                                </div>
                                            </div>

                                            @if($user->email_verified_at)
                                            <div class="col-md-3 mb-3">
                                                <div class="text-center p-3 bg-light rounded-3">
                                                    <i class="bi bi-shield-check text-success fs-4 mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">Email Terverifikasi</h6>
                                                    <p class="mb-0 text-muted small">
                                                        {{ $user->email_verified_at->format('d/m/Y') }}<br>
                                                        <small>{{ $user->email_verified_at->format('H:i:s') }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($user->trashed())
                                            <div class="col-md-3 mb-3">
                                                <div class="text-center p-3 bg-light rounded-3">
                                                    <i class="bi bi-trash text-danger fs-4 mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">Dihapus</h6>
                                                    <p class="mb-0 text-muted small">
                                                        {{ $user->deleted_at->format('d/m/Y') }}<br>
                                                        <small>{{ $user->deleted_at->format('H:i:s') }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                        </a>
                                    </div>

                                    <div class="btn-group">
                                        @if($user->trashed())
                                            <!-- Restore User -->
                                            <button class="btn btn-success" onclick="confirmRestore({{ $user->id }})">
                                                <i class="bi bi-arrow-clockwise me-1"></i> Pulihkan User
                                            </button>
                                            <!-- Force Delete -->
                                            <button class="btn btn-danger" onclick="confirmForceDelete({{ $user->id }})">
                                                <i class="bi bi-trash-fill me-1"></i> Hapus Permanen
                                            </button>
                                        @else
                                            <!-- Edit User -->
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning text-dark">
                                                <i class="bi bi-pencil me-1"></i> Edit User
                                            </a>
                                            <!-- Change Password -->
                                            <a href="{{ route('users.change-password', $user->id) }}" class="btn btn-primary">
                                                <i class="bi bi-key me-1"></i> Ubah Password
                                            </a>
                                            <!-- Delete User -->
                                            @if($user->id !== auth()->id())
                                                <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">
                                                    <i class="bi bi-trash me-1"></i> Hapus User
                                                </button>
                                            @else
                                                <button class="btn btn-outline-secondary" disabled title="Tidak dapat hapus akun sendiri">
                                                    <i class="bi bi-trash me-1"></i> Hapus User
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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

    <style>
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .btn-group .btn {
            margin-left: 0.5rem;
        }
    </style>
</x-layout>
