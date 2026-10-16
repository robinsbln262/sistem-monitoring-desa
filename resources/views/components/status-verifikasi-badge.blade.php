@props([
    'status' => 'pending',
    'showLabel' => true,
    'showIcon' => true,
    'size' => 'default', // small, default, large
    'class' => '',
])

@php
    $statusMap = [
        'pending' => [
            'badge' => 'bg-warning text-dark',
            'icon' => 'fa-clock',
            'label' => 'Menunggu Verifikasi',
        ],
        'approved' => [
            'badge' => 'bg-success',
            'icon' => 'fa-check-circle',
            'label' => 'Disetujui',
        ],
        'rejected' => [
            'badge' => 'bg-danger',
            'icon' => 'fa-times-circle',
            'label' => 'Ditolak',
        ],
    ];

    $defaultStatus = $statusMap[$status] ?? $statusMap['pending'];

    $sizeClass = match($size) {
        'small' => 'px-2 py-1 fs-7',
        'large' => 'px-4 py-2 fs-5',
        default => 'px-3 py-2 fs-6',
    };
@endphp

<span class="badge {{ $defaultStatus['badge'] }} rounded-pill {{ $sizeClass }} {{ $class }}">
    @if($showIcon)
        <i class="fas {{ $defaultStatus['icon'] }} me-1"></i>
    @endif
    @if($showLabel)
        {{ $defaultStatus['label'] }}
    @endif
</span>