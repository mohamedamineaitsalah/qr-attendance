@extends('layouts.app')
@section('title', __('Attendance History'))
@section('page-title', __('Attendance History'))

@section('content')
{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-funnel-fill" style="color:#6c63ff;"></i> {{ __('Filters') }}
    </div>
    <div class="p-4">
        <form method="GET" action="{{ route('attendance.history') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('Person') }}</label>
                    <select name="person_id" class="form-select">
                        <option value="">{{ __('All People') }}</option>
                        @foreach($persons as $p)
                            <option value="{{ $p->id }}" {{ request('person_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Date') }}</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Action') }}</label>
                    <select name="action" class="form-select">
                        <option value="">{{ __('Entry & Exit') }}</option>
                        <option value="ENTRY" {{ request('action') === 'ENTRY' ? 'selected' : '' }}>{{ __('Entry only') }}</option>
                        <option value="EXIT"  {{ request('action') === 'EXIT'  ? 'selected' : '' }}>{{ __('Exit only') }}</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary-custom flex-grow-1">
                        <i class="bi bi-search me-1"></i> {{ __('Filter') }}
                    </button>
                    <a href="{{ route('attendance.history') }}" class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Export buttons --}}
<div class="d-flex gap-2 mb-4">
    <a href="{{ route('export.excel', request()->query()) }}"
       class="btn btn-sm rounded-3" style="background:rgba(16,185,129,0.1);color:#10b981;font-weight:600;font-size:0.82rem;">
        <i class="bi bi-file-earmark-excel me-2"></i>{{ __('Export Excel') }}
    </a>
    <a href="{{ route('export.pdf', request()->query()) }}"
       class="btn btn-sm rounded-3" style="background:rgba(239,68,68,0.1);color:#ef4444;font-weight:600;font-size:0.82rem;">
        <i class="bi bi-file-earmark-pdf me-2"></i>{{ __('Export PDF') }}
    </a>
    
    <form action="{{ route('attendance.clear_all') }}" method="POST" class="ms-2" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer tout l\'historique ?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm rounded-3" style="background:rgba(245,158,11,0.1);color:#f59e0b;font-weight:600;font-size:0.82rem;">
            <i class="bi bi-trash-fill me-2"></i>{{ __('Clear All History') }}
        </button>
    </form>

    <span class="ms-auto badge align-self-center" style="background:rgba(108,99,255,0.1);color:#6c63ff;font-size:0.78rem;padding:7px 12px;border-radius:8px;">
        {{ $records->total() }} {{ __('records') }}
    </span>
</div>

{{-- Table --}}
<div class="content-card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-clock-history" style="color:#6c63ff;"></i>
        {{ __('Complete History') }}
    </div>
    <div class="card-body">
        @if($records->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                <p class="mb-0">{{ __('No attendance records found.') }}</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Person') }}</th>
                        <th>{{ __('Action') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Time') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#6c63ff,#a78bfa);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.78rem;flex-shrink:0;">
                                    {{ strtoupper(substr($record->person?->first_name ?? '?', 0, 1)) }}
                                </div>
                                <span class="fw-600" style="font-size:0.88rem;">{{ $record->person?->full_name ?? '—' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($record->action === 'ENTRY')
                                <span class="badge-entry"><i class="bi bi-arrow-down-circle me-1"></i>{{ __('ENTRY') }}</span>
                            @else
                                <span class="badge-exit"><i class="bi bi-arrow-up-circle me-1"></i>{{ __('EXIT') }}</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $record->date }}</td>
                        <td class="text-muted">{{ $record->time }}</td>
                        <td class="text-end">
                            <form action="{{ route('attendance.destroy', $record) }}" method="POST" onsubmit="return confirm('{{ __('Delete') }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm"
                                        style="background:rgba(239,68,68,0.1);color:#ef4444;" title="{{ __('Delete') }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center py-3">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
