@extends('layouts.app')
@section('title', $type === 'ENTRY' ? __('Scanner Entry') : __('Scanner Exit'))
@section('page-title', $type === 'ENTRY' ? __('Scanner Entry') : __('Scanner Exit'))

@push('styles')
<style>
    .scanner-wrapper {
        max-width: 500px;
        margin: 0 auto;
    }
    #qr-reader {
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #e8ecf0;
        background: #000;
        min-height: 300px;
        position: relative;
    }
    #qr-reader video { border-radius: 14px; }
    .scan-overlay {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        pointer-events: none;
        z-index: 10;
    }
    .scan-frame {
        width: 200px; height: 200px;
        border: 3px solid #6c63ff;
        border-radius: 16px;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.35);
        animation: pulse-border 2s ease-in-out infinite;
    }
    @keyframes pulse-border {
        0%, 100% { border-color: #6c63ff; box-shadow: 0 0 0 9999px rgba(0,0,0,0.35), 0 0 20px rgba(108,99,255,0.4); }
        50%       { border-color: #a78bfa; box-shadow: 0 0 0 9999px rgba(0,0,0,0.35), 0 0 40px rgba(108,99,255,0.7); }
    }
    #result-card {
        display: none;
        animation: slideIn 0.4s ease;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .result-success { border-left: 4px solid #10b981; }
    .result-exit    { border-left: 4px solid #ef4444; }
    .result-rejected{ border-left: 4px solid #f59e0b; }
    .result-person-name { font-size: 1.4rem; font-weight: 800; }
    .result-action-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 16px; border-radius: 10px;
        font-weight: 700; font-size: 1rem;
    }
    .action-entry { background: rgba(16,185,129,0.12); color: #10b981; }
    .action-exit  { background: rgba(239,68,68,0.12); color: #ef4444; }
    .action-rejected { background: rgba(245,158,11,0.12); color: #f59e0b; }
    .scan-btn {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        border: none; cursor: pointer;
        transition: all 0.2s ease;
    }
    .scan-btn-start {
        background: linear-gradient(135deg, #6c63ff, #a78bfa);
        color: #fff;
        box-shadow: 0 4px 20px rgba(108,99,255,0.4);
    }
    .scan-btn-start:hover { transform: scale(1.08); }
    .scan-btn-stop {
        background: rgba(239,68,68,0.1);
        color: #ef4444;
    }
    #qr-reader__scan_region { min-height: unset !important; }
    #qr-reader__dashboard_section_swaplink { color: #6c63ff !important; }
    #qr-reader select, #qr-reader input[type=button] {
        border-radius: 8px !important;
        font-family: 'Inter', sans-serif !important;
    }
    #qr-reader__status_span { font-family: 'Inter', sans-serif !important; font-size: 0.82rem !important; }
</style>
@endpush

@section('content')
<div class="scanner-wrapper">

    {{-- Controls --}}
    <div class="content-card mb-4">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-qr-code-scan" style="color:#6c63ff;"></i>
            {{ __('Camera Scanner') }} - {{ $type }}
        </div>
        <div class="p-4">
            <div id="qr-reader" class="mb-3">
                <div class="d-flex align-items-center justify-content-center" style="height:280px;color:#fff;">
                    <div class="text-center text-muted">
                        <i class="bi bi-camera fs-2 mb-2 d-block" style="color:#6c63ff;"></i>
                        <span style="font-size:0.85rem;">{{ __('Press Start to activate camera') }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-center gap-4">
                <button id="btn-start" class="scan-btn scan-btn-start" title="Start Scanner">
                    <i class="bi bi-play-fill"></i>
                </button>
                <div class="text-center">
                    <div id="scan-status" style="font-size:0.82rem;color:#6b7280;font-weight:500;">{{ __('Ready') }}</div>
                    <div style="font-size:0.72rem;color:#9ca3af;">{{ __('Point camera at QR code') }}</div>
                </div>
                <button id="btn-stop" class="scan-btn scan-btn-stop" title="Stop Scanner" style="display:none;">
                    <i class="bi bi-stop-fill"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Result Card --}}
    <div id="result-card" class="content-card">
        <div class="p-4" id="result-inner">
            {{-- Filled by JS --}}
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const SCAN_URL = "{{ route('scanner.scan') }}";

let html5QrCode = null;
let scanning = false;
let processingLock = false;

const btnStart = document.getElementById('btn-start');
const btnStop  = document.getElementById('btn-stop');
const scanStatus = document.getElementById('scan-status');
const resultCard = document.getElementById('result-card');
const resultInner = document.getElementById('result-inner');

function startScanner() {
    if (scanning) return;
    html5QrCode = new Html5Qrcode("qr-reader");
    const config = {
        fps: 15,
        qrbox: { width: 200, height: 200 },
        aspectRatio: 1.0,
        disableFlip: false,
    };

    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        () => {}
    ).then(() => {
        scanning = true;
        btnStart.style.display = 'none';
        btnStop.style.display  = 'flex';
        scanStatus.textContent = '{{ __('Scanning…') }}';
        scanStatus.style.color = '#6c63ff';
    }).catch(err => {
        scanStatus.textContent = '{{ __('Camera error:') }} ' + err;
        scanStatus.style.color = '#ef4444';
    });
}

function stopScanner() {
    if (!scanning || !html5QrCode) return;
    html5QrCode.stop().then(() => {
        scanning = false;
        html5QrCode = null;
        btnStart.style.display = 'flex';
        btnStop.style.display  = 'none';
        scanStatus.textContent = '{{ __('Stopped') }}';
        scanStatus.style.color = '#6b7280';
        document.getElementById('qr-reader').innerHTML = `
            <div class="d-flex align-items-center justify-content-center" style="height:280px;">
                <div class="text-center text-muted">
                    <i class="bi bi-camera fs-2 mb-2 d-block" style="color:#6c63ff;"></i>
                    <span style="font-size:0.85rem;">{{ __('Press Start to activate camera') }}</span>
                </div>
            </div>`;
    });
}

async function onScanSuccess(decodedText) {
    if (processingLock) return;
    processingLock = true;

    scanStatus.textContent = '{{ __('Processing…') }}';
    scanStatus.style.color = '#f59e0b';

    try {
        const response = await fetch(SCAN_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ token: decodedText, scan_type: '{{ $type }}' }),
        });

        const data = await response.json();
        showResult(data);

        // Re-enable scanning after 3 seconds
        setTimeout(() => {
            processingLock = false;
            scanStatus.textContent = '{{ __('Scanning…') }}';
            scanStatus.style.color = '#6c63ff';
        }, 3000);

    } catch (err) {
        showError('{{ __('Network error. Please try again.') }}');
        setTimeout(() => { processingLock = false; }, 2000);
    }
}

function showResult(data) {
    resultCard.style.display = 'block';
    resultCard.className = 'content-card';

    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-GB');
    const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });

    if (data.status === 'success') {
        const isEntry = data.action === 'ENTRY';
        resultCard.classList.add('result-' + (isEntry ? 'success' : 'exit'));
        resultInner.innerHTML = `
            <div class="d-flex align-items-start gap-3">
                <div style="width:56px;height:56px;border-radius:14px;background:${isEntry ? 'rgba(16,185,129,0.12)' : 'rgba(239,68,68,0.12)'};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi ${isEntry ? 'bi-arrow-down-circle-fill' : 'bi-arrow-up-circle-fill'}" style="font-size:1.5rem;color:${isEntry ? '#10b981' : '#ef4444'};"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="result-person-name">${data.person.name}</div>
                    <div class="mt-2">
                        <span class="result-action-badge ${isEntry ? 'action-entry' : 'action-exit'}">
                            <i class="bi ${isEntry ? 'bi-arrow-down-circle' : 'bi-arrow-up-circle'}"></i>
                            ${data.action}
                        </span>
                    </div>
                    <div class="mt-3 d-flex gap-3" style="font-size:0.85rem;color:#6b7280;">
                        <span><i class="bi bi-clock me-1"></i>${data.time}</span>
                        <span><i class="bi bi-calendar3 me-1"></i>${data.date}</span>
                    </div>
                    <div class="mt-2" style="font-size:0.82rem;font-weight:600;color:${isEntry ? '#10b981' : '#ef4444'};">
                        <i class="bi bi-check-circle me-1"></i>${data.message}
                    </div>
                </div>
            </div>`;
        scanStatus.textContent = isEntry ? '✓ {{ __('Entry Registered') }}' : '✓ {{ __('Exit Registered') }}';
        scanStatus.style.color = isEntry ? '#10b981' : '#ef4444';

    } else {
        // Rejected
        resultCard.classList.add('result-rejected');
        const personName = data.person ? data.person.name : '{{ __('Unknown') }}';
        resultInner.innerHTML = `
            <div class="d-flex align-items-start gap-3">
                <div style="width:56px;height:56px;border-radius:14px;background:rgba(245,158,11,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-x-octagon-fill" style="font-size:1.5rem;color:#f59e0b;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="result-action-badge action-rejected mb-2">
                        <i class="bi bi-slash-circle"></i> {{ __('REJECTED') }}
                    </div>
                    <div style="font-size:0.9rem;font-weight:600;color:#1a1f36;">${personName}</div>
                    <div class="mt-2" style="font-size:0.85rem;color:#f59e0b;font-weight:500;">
                        <i class="bi bi-exclamation-triangle me-1"></i>${data.message}
                    </div>
                </div>
            </div>`;
        scanStatus.textContent = '⚠ {{ __('Scan Rejected') }}';
        scanStatus.style.color = '#f59e0b';
    }

    // Auto-scroll to result
    resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function showError(msg) {
    resultCard.style.display = 'block';
    resultCard.className = 'content-card result-rejected';
    resultInner.innerHTML = `<div class="text-danger"><i class="bi bi-exclamation-circle me-2"></i>${msg}</div>`;
}

btnStart.addEventListener('click', startScanner);
btnStop.addEventListener('click', stopScanner);
</script>
@endpush
