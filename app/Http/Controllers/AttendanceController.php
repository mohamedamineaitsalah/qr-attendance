<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Person;
use App\Models\RejectedScan;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function inside()
    {
        $persons = Person::where('status', 'INSIDE')
            ->with(['attendance' => function ($q) {
                $q->where('action', 'ENTRY')->orderByDesc('created_at')->limit(1);
            }])
            ->get()
            ->map(function ($person) {
                $lastEntry = $person->attendance->first();
                $person->entry_date = $lastEntry?->date;
                $person->entry_time = $lastEntry?->time;
                return $person;
            });

        return view('attendance.inside', compact('persons'));
    }

    public function history(Request $request)
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

        $records = $query->paginate(20)->withQueryString();
        $persons = Person::orderBy('first_name')->get();

        return view('attendance.history', compact('records', 'persons'));
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->back()->with('success', __('Record deleted successfully.'));
    }

    public function clearAll()
    {
        Attendance::truncate();
        RejectedScan::truncate();
        return redirect()->back()->with('success', __('All history cleared successfully.'));
    }

    public function rejected(Request $request)
    {
        $records = RejectedScan::with('person')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('attendance.rejected', compact('records'));
    }
}
