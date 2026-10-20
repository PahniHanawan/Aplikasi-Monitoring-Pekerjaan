@php
    $alerts = $alerts ?? [];
@endphp

@if(count($alerts) > 0)
<div class="col-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
            <h5 class="fw-bold mb-0 text-warning">
                <i class="bi bi-bell me-2"></i>Peringatan & Notifikasi
            </h5>
            <span class="badge bg-warning alert-badge">{{ count($alerts) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($alerts as $alert)
                <a href="{{ $alert['url'] ?? '#' }}" 
                   class="list-group-item border-0 py-3 px-3 text-decoration-none 
                          border-start border-{{ $alert['level'] }} border-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center 
                                      bg-{{ $alert['level'] }} bg-opacity-10"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-{{ $alert['icon'] }} text-{{ $alert['level'] }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold mb-0 text-dark">{{ $alert['title'] }}</h6>
                                <small class="badge bg-{{ $alert['level'] }}">
                                    {{ strtoupper($alert['type'] ?? 'info') }}
                                </small>
                            </div>
                            <p class="mb-1 text-dark">{{ $alert['message'] }}</p>
                            @if(isset($alert['subtitle']))
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>{{ $alert['subtitle'] }}
                            </small>
                            @endif
                            @if(isset($alert['date']))
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-calendar me-1"></i>{{ $alert['date'] }}
                            </small>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif