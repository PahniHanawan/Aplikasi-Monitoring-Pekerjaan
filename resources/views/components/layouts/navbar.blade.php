<nav class="app-header navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <!-- Left: Sidebar Toggle -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-dark" href="#" data-bs-toggle="sidebar">
                    <i class="bi bi-list fs-5"></i>
                </a>
            </li>
            <li class="nav-item d-md-none">
                <a class="nav-link text-dark" href="#" data-bs-toggle="mobile-sidebar">
                    <i class="bi bi-list fs-5"></i>
                </a>
            </li>
        </ul>

        <!-- Center: Brand/Title -->
        <div class="navbar-brand mx-auto d-none d-md-block">
            <small class="text-muted">
                <i class="bi bi-clock me-1"></i>
                <span id="current-time">{{ now()->format('H:i') }}</span>
            </small>
        </div>

        <!-- Right: User Menu -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center py-1 text-dark"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- User Avatar with Status -->
                    <div class="position-relative me-2">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 32px; height: 32px;">
                            <i class="bi bi-person-fill text-white"></i>
                        </div>
                        <!-- Online Status Indicator -->
                        <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                              style="width: 10px; height: 10px;"></span>
                    </div>

                    <!-- User Info -->
                    <div class="d-none d-sm-flex flex-column align-items-start me-2">
                        <span class="fw-semibold text-dark" style="font-size: 0.9rem;">
                            {{ Auth::user()->name }}
                        </span>
                        <small class="text-muted" style="font-size: 0.75rem; line-height: 1;">
                            <span class="badge bg-{{ Auth::user()->getRoleBadgeColor() }} py-1">
                                {{ Auth::user()->getRoleLabel() }}
                            </span>
                        </small>
                    </div>

                    <i class="bi bi-chevron-down text-muted ms-1"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 240px;">
                    <!-- Header -->
                    <li class="dropdown-header bg-light py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-white fs-6"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-dark">{{ Auth::user()->name }}</h6>
                                <small class="text-muted d-flex align-items-center mt-1">
                                    <i class="bi {{ Auth::user()->getRoleIcon() }} me-1"></i>
                                    {{ Auth::user()->getRoleLabel() }}
                                </small>
                            </div>
                        </div>
                    </li>

                    <li><hr class="dropdown-divider my-2"></li>

                    <!-- Menu Items -->

                    @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="#">
                            <i class="bi bi-graph-up me-3 text-primary"></i>
                            <div>
                                <span class="d-block">Analytics</span>
                                <small class="text-muted">View reports</small>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->isAdmin())
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('users.index') }}">
                            <i class="bi bi-people me-3 text-primary"></i>
                            <div>
                                <span class="d-block">User Management</span>
                                <small class="text-muted">Manage users</small>
                            </div>
                        </a>
                    </li>
                    @endif

                    <li><hr class="dropdown-divider my-2"></li>

                    <!-- Logout -->
                    {{-- <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center py-2 text-danger">
                                <i class="bi bi-box-arrow-right me-3"></i>
                                <div>
                                    <span class="d-block">Logout</span>
                                    <small class="text-muted">Sign out from system</small>
                                </div>
                            </button>
                        </form>
                    </li> --}}
                </ul>
            </li>
        </ul>
    </div>
</nav>
