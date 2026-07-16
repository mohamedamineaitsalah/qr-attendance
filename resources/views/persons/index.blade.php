@extends('layouts.app')
@section('title', __('Manage People'))
@section('page-title', __('Manage People'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Manage registered persons and their QR codes.</p>
    </div>
    <a href="{{ route('persons.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-person-plus-fill me-2"></i>Add Person
    </a>
</div>

{{-- Search --}}
<div class="content-card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('persons.index') }}" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border:1.5px solid #e8ecf0;border-radius:10px 0 0 10px;">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0"
                       style="border-radius:0 10px 10px 0;"
                       placeholder="{{ __('Search by name...') }}" value="{{ $search ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary-custom px-4">{{ __('Search') }}</button>
            @if($search)
                <a href="{{ route('persons.index') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
            @endif
        </form>
    </div>
</div>

<div class="content-card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-people-fill" style="color:#6c63ff;"></i>
        {{ __('All People') }}
        <span class="ms-auto badge" style="background:rgba(108,99,255,0.1);color:#6c63ff;font-size:0.75rem;">
            {{ $persons->total() }} {{ __('total') }}
        </span>
    </div>
    <div class="card-body">
        @if($persons->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people fs-1 d-block mb-3"></i>
                <p class="mb-0">No people found. <a href="{{ route('persons.create') }}">Add the first person.</a></p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Full Name') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('QR Token') }}</th>
                        <th>{{ __('Registered') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($persons as $person)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ ($persons->currentPage() - 1) * $persons->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px;height:36px;background:linear-gradient(135deg,#6c63ff,#a78bfa);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                                    {{ strtoupper(substr($person->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-600" style="font-size:0.88rem;">{{ $person->full_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($person->status === 'INSIDE')
                                <span class="badge-inside"><i class="bi bi-circle-fill me-1" style="font-size:0.55rem;"></i>{{ __('INSIDE') }}</span>
                            @else
                                <span class="badge-outside"><i class="bi bi-circle me-1" style="font-size:0.55rem;"></i>{{ __('OUTSIDE') }}</span>
                            @endif
                        </td>
                        <td>
                            <code style="font-size:0.72rem;background:#f0f2f7;padding:3px 7px;border-radius:6px;">
                                {{ Str::limit($person->qr_token, 18) }}
                            </code>
                        </td>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $person->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('persons.qr', $person) }}"
                                   class="btn btn-icon btn-sm" style="background:rgba(16,185,129,0.1);color:#10b981;"
                                   title="View QR">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                                <a href="{{ route('persons.edit', $person) }}"
                                   class="btn btn-icon btn-sm" style="background:rgba(108,99,255,0.1);color:#6c63ff;"
                                   title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('persons.destroy', $person) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $person->full_name }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm"
                                            style="background:rgba(239,68,68,0.1);color:#ef4444;" title="Delete">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                <form action="{{ route('persons.clear_history', $person) }}" method="POST"
                                      onsubmit="return confirm('Delete all scan history for {{ $person->full_name }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm"
                                            style="background:rgba(245,158,11,0.1);color:#f59e0b;" title="Clear History">
                                        <i class="bi bi-clock-history"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center py-3">
            {{ $persons->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
