<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $showcaseQueries = [
            'headache' => 'migraine, decompression, CO₂ buildup, radiation sickness',
            "can't sleep" => 'circadian disruption, habitat noise, melatonin, exercise timing',
            'broken bone' => 'low-g fracture, osteoporosis, mining injury, casting in 1/6 gravity',
            'feeling sad' => 'Earth-sickness, isolation depression, lunar seasonal affective disorder',
            'dust in lungs' => 'regolith pneumoconiosis, silicosis, decontamination protocol',
            'dizzy' => 'vestibular adaptation, decompression, anemia, CO₂ poisoning',
            'radiation' => 'cosmic ray exposure, solar particle events, dosimetry, cancer screening',
        ];

        return view('search.index', compact('query', 'showcaseQueries'));
    }
}
