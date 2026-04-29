<?php

namespace App\Http\Controllers;

use App\Models\Anatomy;
use Illuminate\Http\Request;

class AnatomyController extends Controller
{
    public function index(Request $request)
    {
        $systems = Anatomy::select('body_system')
            ->distinct()
            ->orderBy('body_system')
            ->pluck('body_system');

        $query = Anatomy::query();

        if ($request->filled('system')) {
            $query->bySystem($request->system);
        }

        $anatomies = $query->orderBy('body_system')->orderBy('name')->paginate(30)->withQueryString();

        return view('anatomy.index', compact('anatomies', 'systems'));
    }

    public function show(string $slug)
    {
        $anatomy = Anatomy::where('slug', $slug)->firstOrFail();

        return view('anatomy.show', compact('anatomy'));
    }
}
