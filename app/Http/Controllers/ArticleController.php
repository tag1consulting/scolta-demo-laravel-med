<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $types = Article::select('research_type')
            ->distinct()
            ->orderBy('research_type')
            ->pluck('research_type');

        $query = Article::query();

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('system')) {
            $query->where('body_system', $request->system);
        }

        $articles = $query->latest('published_date')->paginate(20)->withQueryString();

        return view('articles.index', compact('articles', 'types'));
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        return view('articles.show', compact('article'));
    }
}
