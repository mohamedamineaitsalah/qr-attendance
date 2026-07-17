@extends('layouts.app')
@section('title', __('Rejected Scans'))
@section('page-title', __('Rejected Scans'))

@section('content')
<div class="content-card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-x-octagon-fill" style="color:#f59e0b;"></i>
        {{ __('Rejected Scans Log') }}
        
        <form action="{{ route('attendance.clear_rejected') }}" method="POST" class="ms-3" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer tous les scans refusés ?') }}')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm rounded-3" style="background:rgba(239,68,68,0.1);color:#ef4444;font-weight:600;font-size:0.75rem;">
                <i class="bi bi-trash-fill me-1"></i> {{ __('Supprimer tout') }}
            </button>
        </form>

        <span class="ms-auto badge" style="background:rgba(245,158,11,0.1);color:#f59e0b;font-size:0.75rem;">
            {{ $records->total() }} {{ __('total') }}
        </span>
    </div>
    <div class="card-body">
        @if($records->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-shield-check fs-1 d-block mb-3"></i>
                <p class="mb-0">{{ __('No rejected scans recorded.') }}</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Person / Token') }}</th>
                        <th>{{ __('Reason') }}</th>
                        <th>{{ __('Date & Time') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $rec)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}</td>
                        <td>
                            @if($rec->person)
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px;height:32px;background:rgba(245,158,11,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#f59e0b;font-weight:700;font-size:0.78rem;flex-shrink:0;">
                                        {{ strtoupper(substr($rec->person->first_name, 0, 1)) }}
                                    </div>
                                    <span class="fw-600" style="font-size:0.88rem;">{{ $rec->person->full_name }}</span>
                                </div>
                            @else
                                <code style="font-size:0.75rem;background:#f0f2f7;padding:3px 7px;border-radius:6px;word-break:break-all;">
                                    {{ Str::limit($rec->qr_token, 28) }}
                                </code>
                            @endif
                        </td>
                        <td>
                            <span class="badge-rejected">{{ $rec->reason }}</span>
                        </td>
                        <td class="text-muted" style="font-size:0.82rem;">
                            {{ $rec->created_at->format('d M Y, H:i:s') }}
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
