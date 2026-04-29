<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use Illuminate\Http\Request;

class ConditionController extends Controller
{
    public function index(Request $request)
    {
        $systems = Condition::select('body_system')
            ->distinct()
            ->orderBy('body_system')
            ->pluck('body_system');

        $query = Condition::query();

        if ($request->filled('system')) {
            $query->bySystem($request->system);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->boolean('emergency')) {
            $query->emergency();
        }

        $conditions = $query->orderBy('name')->paginate(30)->withQueryString();

        return view('conditions.index', compact('conditions', 'systems'));
    }

    public function show(string $slug)
    {
        $condition = Condition::where('slug', $slug)->firstOrFail();

        return view('conditions.show', compact('condition'));
    }

    public function bySystem(string $system)
    {
        $conditions = Condition::bySystem($system)
            ->orderBy('severity', 'desc')
            ->orderBy('name')
            ->paginate(30);

        return view('conditions.system', compact('conditions', 'system'));
    }
}
