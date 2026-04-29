<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index(Request $request)
    {
        $classes = Medication::select('drug_class')
            ->distinct()
            ->orderBy('drug_class')
            ->pluck('drug_class');

        $query = Medication::query();

        if ($request->filled('class')) {
            $query->byClass($request->class);
        }

        if ($request->boolean('lunar_critical')) {
            $query->lunarCritical();
        }

        if ($request->boolean('who_essential')) {
            $query->where('who_essential', true);
        }

        $medications = $query->orderBy('generic_name')->paginate(30)->withQueryString();

        return view('medications.index', compact('medications', 'classes'));
    }

    public function show(string $slug)
    {
        $medication = Medication::where('slug', $slug)->firstOrFail();

        return view('medications.show', compact('medication'));
    }
}
