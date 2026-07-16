@extends('layouts.app')
@section('title', 'Currently Inside')
@section('page-title', 'Currently Inside')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:0.85rem;">
        People currently inside the premises.
    </p>
    <div class="d-flex align-items-center gap-2">
        <span class="badge-inside" style="font-size:0.82rem;padding:8px 14px;">
            <i class="bi bi-circle-fill me-1" style="font-size:0.55rem;"></i>
            {{ $persons->count() }} inside
        </span>
    </div>
</div>

<div class="content-card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-door-open-fill" style="color:#10b981;"></i>
        Currently Inside
    </div>
    <div class="card-body">
        @if($persons->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-door-closed fs-1 d-block mb-3"></i>
                <p class="mb-0">No one is currently inside.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Entry Time</th>
                        <th>Entry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($persons as $i => $person)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px;height:36px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                                    {{ strtoupper(substr($person->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-600" style="font-size:0.88rem;">{{ $person->full_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($person->entry_time)
                                <span class="badge-entry">
                                    <i class="bi bi-clock me-1"></i>{{ $person->entry_time }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $person->entry_date ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
