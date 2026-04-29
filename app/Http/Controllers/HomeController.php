<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Condition;
use App\Models\Medication;
use App\Models\Procedure;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'conditions' => Condition::count(),
            'medications' => Medication::count(),
            'procedures' => Procedure::count(),
            'articles' => Article::count(),
        ];

        $emergencyConditions = Condition::emergency()
            ->orderBy('severity', 'desc')
            ->limit(6)
            ->get();

        $featuredArticles = Article::featured()
            ->latest('published_date')
            ->limit(3)
            ->get();

        return view('home', compact('stats', 'emergencyConditions', 'featuredArticles'));
    }
}
