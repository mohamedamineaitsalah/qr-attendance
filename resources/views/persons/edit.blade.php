@extends('layouts.app')
@section('title', 'Edit Person')
@section('page-title', 'Edit Person')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-pencil-fill" style="color:#6c63ff;"></i>
                Edit: {{ $person->full_name }}
            </div>
            <div class="p-4">
                <form action="{{ route('persons.update', $person) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $person->first_name) }}">
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $person->last_name) }}">
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="p-3 mb-4 rounded-3" style="background:#f8fafc;border:1.5px solid #e8ecf0;">
                        <div class="form-label mb-1">QR Token (read-only)</div>
                        <code style="font-size:0.78rem;color:#6c63ff;word-break:break-all;">{{ $person->qr_token }}</code>
                        <div class="mt-2 d-flex gap-2">
                            <a href="{{ route('persons.qr', $person) }}" class="btn btn-sm rounded-3"
                               style="background:rgba(108,99,255,0.1);color:#6c63ff;font-size:0.78rem;">
                                <i class="bi bi-qr-code me-1"></i> View QR
                            </a>
                            <a href="{{ route('persons.qr.download', $person) }}" class="btn btn-sm rounded-3"
                               style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.78rem;">
                                <i class="bi bi-download me-1"></i> Download QR
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i> Update Person
                        </button>
                        <a href="{{ route('persons.index') }}" class="btn btn-outline-secondary rounded-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
