@php
    $events = $events ?? [];
@endphp

@forelse($events as $event)
<div class="event-item p-3 border-bottom">
    <div class="d-flex align-items-start">
        <div class="flex-shrink-0 me-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 40px; height: 40px; background-color: {{ $event['color'] }};">
                <i class="bi bi-{{ $event['icon'] }} text-white fs-6"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-1 fw-semibold text-dark small lh-sm">{{ $event['title'] }}</h6>
            <p class="mb-1 text-muted small lh-sm">{{ $event['description'] }}</p>
            <div class="d-flex align-items-center mt-2">
                <small class="text-primary fw-semibold">
                    <i class="bi bi-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($event['start'])->translatedFormat('d M Y') }}
                </small>
                <small class="badge bg-light text-dark border ms-2" style="font-size: 0.65rem;">
                    {{ ucfirst($event['type']) }}
                </small>
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center text-muted py-4">
    <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>
    <p class="mb-0 small">Tidak ada event</p>
</div>
@endforelse