<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1f36; margin: 0; padding: 20px; }
        h1 { font-size: 18px; font-weight: bold; color: #1a1f36; margin-bottom: 4px; }
        .subtitle { color: #6b7280; font-size: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead tr { background: #0f1117; color: #fff; }
        thead th { padding: 9px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid #e8ecf0; }
        .badge-entry { background: #d1fae5; color: #065f46; padding: 2px 7px; border-radius: 4px; font-weight: 600; }
        .badge-exit  { background: #fee2e2; color: #7f1d1d; padding: 2px 7px; border-radius: 4px; font-weight: 600; }
        .footer { margin-top: 20px; color: #9ca3af; font-size: 9px; text-align: center; }
    </style>
</head>
<body>
    <h1>QR Attendance — History Report</h1>
    <div class="subtitle">Generated on {{ now()->format('d M Y, H:i:s') }} · {{ $records->count() }} records</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Action</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $i => $record)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $record->person?->first_name ?? '—' }}</td>
                <td>{{ $record->person?->last_name  ?? '—' }}</td>
                <td>
                    @if($record->action === 'ENTRY')
                        <span class="badge-entry">ENTRY</span>
                    @else
                        <span class="badge-exit">EXIT</span>
                    @endif
                </td>
                <td>{{ $record->date }}</td>
                <td>{{ $record->time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">QR Attendance Management System</div>
</body>
</html>
