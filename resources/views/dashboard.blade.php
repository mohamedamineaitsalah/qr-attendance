@extends('layouts.app')
@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card stat-inside">
            <div class="stat-icon"><i class="bi bi-door-open-fill"></i></div>
            <div class="stat-value">{{ $insideCount }}</div>
            <div class="stat-label">{{ __('Currently Inside') }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card stat-entry">
            <div class="stat-icon"><i class="bi bi-arrow-down-circle-fill"></i></div>
            <div class="stat-value">{{ $entriesCount }}</div>
            <div class="stat-label">{{ __('Entries Today') }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card stat-exit">
            <div class="stat-icon"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <div class="stat-value">{{ $exitsCount }}</div>
            <div class="stat-label">{{ __('Exits Today') }}</div>
        </div>
    </div>
</div>

{{-- Latest Scans + Rejected --}}
<div class="row g-4">
    {{-- Latest Scans --}}
    <div class="col-xl-7">
        <div class="content-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-activity" style="color:#6c63ff;"></i>
                    {{ __('Latest Scans') }}
                </div>
                <a href="{{ route('attendance.history') }}" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:0.75rem;">
                    {{ __('View All') }}
                </a>
            </div>
            <div class="card-body">
                @if($latestScans->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        {{ __('No scans recorded yet.') }}
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Person') }}</th>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Time') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestScans as $scan)
                            <tr>
                                <td>
                                    <span class="fw-600">{{ $scan->person?->full_name ?? '—' }}</span>
                                </td>
                                <td>
                                    @if($scan->action === 'ENTRY')
                                        <span class="badge-entry"><i class="bi bi-arrow-down-circle me-1"></i>{{ __('ENTRY') }}</span>
                                    @else
                                        <span class="badge-exit"><i class="bi bi-arrow-up-circle me-1"></i>{{ __('EXIT') }}</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $scan->date }}</td>
                                <td class="text-muted">{{ $scan->time }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Latest Rejected --}}
    <div class="col-xl-5">
        <div class="content-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-x-octagon-fill" style="color:#f59e0b;"></i>
                    {{ __('Rejected Scans') }}
                </div>
                <a href="{{ route('attendance.rejected') }}" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:0.75rem;">
                    {{ __('View All') }}
                </a>
            </div>
            <div class="card-body">
                @if($latestRejected->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-shield-check fs-2 d-block mb-2"></i>
                        {{ __('No rejected scans.') }}
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Person / Token') }}</th>
                                <th>{{ __('Reason') }}</th>
                                <th>{{ __('Time') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestRejected as $rej)
                            <tr>
                                <td>
                                    @if($rej->person)
                                        <span class="fw-600">{{ $rej->person->full_name }}</span>
                                    @else
                                        <span class="text-muted" style="font-size:0.78rem;font-family:monospace;">
                                            {{ Str::limit($rej->qr_token, 20) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-rejected" style="font-size:0.7rem;">{{ Str::limit($rej->reason, 35) }}</span>
                                </td>
                                <td class="text-muted" style="font-size:0.78rem;">
                                    {{ $rej->created_at->format('H:i:s') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
