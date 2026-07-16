<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Person;
use App\Models\RejectedScan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $insideCount   = Person::where('status', 'INSIDE')->count();
        $entriesCount  = Attendance::where('action', 'ENTRY')->where('date', $today)->count();
        $exitsCount    = Attendance::where('action', 'EXIT')->where('date', $today)->count();

        $latestScans = Attendance::with('person')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $latestRejected = RejectedScan::with('person')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        if (request()->ajax()) {
            return view('partials.dashboard-content', compact(
                'insideCount',
                'entriesCount',
                'exitsCount',
                'latestScans',
                'latestRejected'
            ));
        }

        return view('dashboard', compact(
            'insideCount',
            'entriesCount',
            'exitsCount',
            'latestScans',
            'latestRejected'
        ));
    }
}
