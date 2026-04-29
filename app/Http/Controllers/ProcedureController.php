<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    public function index(Request $request)
    {
        $categories = Procedure::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $query = Procedure::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('risk')) {
            $query->byRisk($request->risk);
        }

        $procedures = $query->orderBy('risk_level')->orderBy('name')->paginate(30)->withQueryString();

        return view('procedures.index', compact('procedures', 'categories'));
    }

    public function show(string $slug)
    {
        $procedure = Procedure::where('slug', $slug)->firstOrFail();

        return view('procedures.show', compact('procedure'));
    }
}
