<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Person;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class ExportController extends Controller
{
    public function excel(Request $request)
    {
        return Excel::download(new AttendanceExport($request), 'attendance_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function pdf(Request $request)
    {
        $query = Attendance::with('person')->orderByDesc('created_at');

        if ($personId = $request->input('person_id')) {
            $query->where('person_id', $personId);
        }
        if ($date = $request->input('date')) {
            $query->where('date', $date);
        }
        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        $records = $query->limit(500)->get();

        $pdf = Pdf::loadView('exports.attendance_pdf', compact('records'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('attendance_' . now()->format('Y-m-d') . '.pdf');
    }
}
