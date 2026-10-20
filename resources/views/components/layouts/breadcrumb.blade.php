<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            <i class="bi bi-house-door me-1"></i>Dashboard
        </a>
    </li>

    @php
        $routes = [
            'kantor' => 'Kantor',
            'proyek' => 'Proyek',
            'kontrak' => 'Kontrak',
            'biaya' => 'Masa Pelaksanaan',
            'realisasi' => 'Realisasi',
            'dokumen' => 'Dokumen',
            'users' => 'Manajemen User' // TAMBAHKAN INI
        ];
    @endphp

    @foreach($routes as $route => $label)
        @if(request()->routeIs($route . '*'))
            <li class="breadcrumb-item {{ request()->routeIs($route . '.index') ? 'active' : '' }}">
                @if(request()->routeIs($route . '.index'))
                    {{ $label }}
                @else
                    <a href="{{ route($route . '.index') }}">{{ $label }}</a>
                @endif
            </li>

            @if(request()->routeIs($route . '.create'))
                <li class="breadcrumb-item active">Tambah {{ $route === 'users' ? 'User' : $label }}</li>
            @elseif(request()->routeIs($route . '.edit'))
                <li class="breadcrumb-item active">Edit {{ $route === 'users' ? 'User' : $label }}</li>
            @elseif(request()->routeIs($route . '.show'))
                <li class="breadcrumb-item active">Detail {{ $route === 'users' ? 'User' : $label }}</li>
            @elseif(request()->routeIs($route . '.change-password'))
                <li class="breadcrumb-item active">Ubah Password</li>
            @endif
        @endif
    @endforeach

    @if(request()->routeIs('dashboard'))
        <li class="breadcrumb-item active">Overview</li>
    @elseif(!collect($routes)->keys()->contains(fn($key) => request()->routeIs($key . '*')))
        <li class="breadcrumb-item active">{{ $pageTitle ?? 'Halaman' }}</li>
    @endif
</ol>
