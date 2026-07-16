@extends('layouts.app')
@section('title', __('Add Person'))
@section('page-title', __('Add Person'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill" style="color:#6c63ff;"></i>
                {{ __('New Person') }}
            </div>
            <div class="p-4">
                <form action="{{ route('persons.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}" placeholder="{{ __('Enter first name') }}">
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}" placeholder="{{ __('Enter last name') }}">
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="p-3 mb-4 rounded-3" style="background:#f8fafc;border:1.5px dashed #e8ecf0;">
                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size:0.82rem;">
                            <i class="bi bi-qr-code text-primary" style="font-size:1.2rem;color:#6c63ff !important;"></i>
                            <span>{!! __('A unique QR code will be <strong>automatically generated</strong> upon saving.') !!}</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i> {{ __('Save Person') }}
                        </button>
                        <a href="{{ route('persons.index') }}" class="btn btn-outline-secondary rounded-3">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
