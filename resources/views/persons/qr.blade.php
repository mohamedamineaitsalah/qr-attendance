@extends('layouts.app')
@section('title', 'QR Code — {{ $person->full_name }}')
@section('page-title', 'QR Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="content-card text-center">
            <div class="p-4 pb-2">
                <div class="mb-2" style="font-size:0.82rem;color:#6b7280;text-transform:uppercase;letter-spacing:1px;font-weight:600;">
                    QR Code for
                </div>
                <h2 style="font-size:1.5rem;font-weight:800;color:#1a1f36;">{{ $person->full_name }}</h2>
            </div>

            {{-- QR Code Display --}}
            <div class="p-4">
                <div style="display:inline-block;padding:16px;background:#fff;border:2px solid #e8ecf0;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    {!! $qrSvg !!}
                </div>
            </div>

            {{-- Token --}}
            <div class="px-4 pb-2">
                <div class="p-3 rounded-3" style="background:#f8fafc;border:1.5px solid #e8ecf0;">
                    <div style="font-size:0.7rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">QR Token</div>
                    <code style="font-size:0.75rem;color:#6c63ff;word-break:break-all;">{{ $person->qr_token }}</code>
                </div>
            </div>

            {{-- Status --}}
            <div class="px-4 pb-4">
                <div class="mt-3">
                    <span class="fw-600 me-2" style="font-size:0.82rem;color:#6b7280;">Current Status:</span>
                    @if($person->status === 'INSIDE')
                        <span class="badge-inside"><i class="bi bi-circle-fill me-1" style="font-size:0.55rem;"></i>INSIDE</span>
                    @else
                        <span class="badge-outside">OUTSIDE</span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-4 pb-4 d-flex gap-2">
                <a href="{{ route('persons.qr.download', $person) }}"
                   class="btn btn-primary-custom flex-grow-1">
                    <i class="bi bi-download me-2"></i> Download QR (SVG)
                </a>
                <a href="{{ route('persons.edit', $person) }}"
                   class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="{{ route('persons.index') }}"
                   class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
