@extends('layouts.app')
@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
<div id="dashboard-content">
    @include('partials.dashboard-content')
</div>
@endsection

@push('scripts')
<script>
    setInterval(() => {
        fetch('{{ route('dashboard') }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('dashboard-content').innerHTML = html;
        })
        .catch(error => console.error('Error fetching dashboard data:', error));
    }, 3000);
</script>
@endpush
