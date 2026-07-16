<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::query();
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%");
            });
        }
        $persons = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('persons.index', compact('persons', 'search'));
    }

    public function create()
    {
        return view('persons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
        ]);

        $data['qr_token'] = (string) Str::uuid();
        Person::create($data);

        return redirect()->route('persons.index')->with('success', 'Person added successfully.');
    }

    public function edit(Person $person)
    {
        return view('persons.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
        ]);

        $person->update($data);
        return redirect()->route('persons.index')->with('success', 'Person updated successfully.');
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return redirect()->route('persons.index')->with('success', 'Person deleted successfully.');
    }

    public function clearHistory(Person $person)
    {
        $person->attendance()->delete();
        $person->rejectedScans()->delete();
        return redirect()->route('persons.index')->with('success', "History cleared for {$person->full_name}.");
    }

    public function downloadQr(Person $person)
    {
        $qrSvg = QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($person->qr_token);

        return response($qrSvg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qr_' . $person->qr_token . '.svg"');
    }

    public function showQr(Person $person)
    {
        $qrSvg = QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($person->qr_token);

        return view('persons.qr', compact('person', 'qrSvg'));
    }
}
