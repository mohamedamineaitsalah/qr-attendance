<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Person;
use App\Models\RejectedScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScannerController extends Controller
{
    public function entry()
    {
        return view('scanner', ['type' => 'ENTRY']);
    }

    public function exit()
    {
        return view('scanner', ['type' => 'EXIT']);
    }

    public function scan(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'scan_type' => ['required', 'in:ENTRY,EXIT'],
        ]);

        $token = trim($request->input('token'));
        $scanType = $request->input('scan_type');

        return DB::transaction(function () use ($token, $scanType) {
            // Find person by token and lock row for update
            $person = Person::where('qr_token', $token)->lockForUpdate()->first();

            if (!$person) {
                RejectedScan::create([
                    'person_id' => null,
                    'qr_token'  => $token,
                    'reason'    => 'QR token not found in database.',
                ]);
                return response()->json([
                    'status'  => 'rejected',
                    'message' => __('Invalid QR Code'),
                ], 404);
            }

            if ($scanType === 'ENTRY') {
                if ($person->isInside()) {
                    RejectedScan::create([
                        'person_id' => $person->id,
                        'qr_token'  => $token,
                        'reason'    => 'Scan ENTRY rejected: Person already inside.',
                    ]);
                    return response()->json([
                        'status'  => 'rejected',
                        'message' => __('This person is already inside.'),
                        'person'  => ['name' => $person->full_name],
                    ], 400);
                }
            } else { // EXIT
                if (!$person->isInside()) {
                    RejectedScan::create([
                        'person_id' => $person->id,
                        'qr_token'  => $token,
                        'reason'    => 'Scan EXIT rejected: Person already outside.',
                    ]);
                    return response()->json([
                        'status'  => 'rejected',
                        'message' => __('This person is already outside.'),
                        'person'  => ['name' => $person->full_name],
                    ], 400);
                }
            }

            // Record attendance
            Attendance::create([
                'person_id' => $person->id,
                'action'    => $scanType,
                'date'      => now()->toDateString(),
                'time'      => now()->toTimeString(),
            ]);

            // Flip person status
            $person->update(['status' => $scanType === 'ENTRY' ? 'INSIDE' : 'OUTSIDE']);

            $message = $scanType === 'ENTRY' ? __('Entry recorded') : __('Exit recorded');

            return response()->json([
                'status'  => 'success',
                'action'  => $scanType,
                'message' => $message,
                'person'  => [
                    'name'      => $person->full_name,
                    'firstName' => $person->first_name,
                    'lastName'  => $person->last_name,
                ],
                'time' => now()->format('H:i:s'),
                'date' => now()->format('d/m/Y'),
            ]);
        });
    }
}
