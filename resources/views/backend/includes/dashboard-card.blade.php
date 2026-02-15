@php
    $bgColor = $bgColor ?? '#2098d1';
@endphp
<div class="col-12 col-sm-3 col-md-3 col-lg-3">
    <div class="card overflow-hidden">
        <div class="card-body">
            <a href="{{ $url }}">
            <h5 class="dashboard-card-heading text-uppercase" title="{{ $name }}" style="font-size: 15px; color: {{ $bgColor }};">{{ $name }}</h5>
            <div class="d-flex align-items-center gap-2 mt-2 py-1">
                <div class="user-img fs-42 flex-shrink-0">
                    <span class="avatar-title rounded-circle fs-22 dashboard-icon" style="background-color: {{ $bgColor }}; color: #fff;">
                        <i class="{{ $icon }}"></i>
                    </span>
                </div>
                <h3 class="mb-0 dashboard-count text-dark" style="font-size: 2.25rem; font-weight: normal;">{{ $count }}</h3>
            </div>
            </a>
        </div>
    </div>
</div>
