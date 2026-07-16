<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected Request $request) {}

    public function query()
    {
        $query = Attendance::with('person')->orderByDesc('created_at');

        if ($personId = $this->request->input('person_id')) {
            $query->where('person_id', $personId);
        }
        if ($date = $this->request->input('date')) {
            $query->where('date', $date);
        }
        if ($action = $this->request->input('action')) {
            $query->where('action', $action);
        }

        return $query;
    }

    public function headings(): array
    {
        return ['#', 'First Name', 'Last Name', 'Action', 'Date', 'Time'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->person?->first_name ?? 'N/A',
            $row->person?->last_name  ?? 'N/A',
            $row->action,
            $row->date,
            $row->time,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
