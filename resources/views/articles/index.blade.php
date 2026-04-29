@extends('layouts.app')

@section('title', 'Research')
@section('description', 'Journal articles, case studies, equipment reviews, and policy papers from lunar medical researchers.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-lunar-100 mb-2">Research</h1>
        <p class="text-lunar-400">Clinical case studies, equipment reviews, policy papers, and journal articles from the Lunar Medical Research Cooperative.</p>
    </div>

    <form method="GET" class="mb-8 flex flex-wrap gap-3 items-center">
        <select name="type" class="bg-lunar-900 border border-lunar-700 text-lunar-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            <option value="">All Types</option>
            @foreach($types as $type)
            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-lunar-800 hover:bg-lunar-700 text-lunar-200 text-sm rounded-lg transition-colors">Filter</button>
        @if(request('type'))
        <a href="{{ route('articles.index') }}" class="text-sm text-lunar-500 hover:text-lunar-300 transition-colors">Clear</a>
        @endif
    </form>

    <div class="space-y-4 mb-8">
        @forelse($articles as $article)
        <a href="{{ route('articles.show', $article->slug) }}" class="card group flex gap-5 items-start">
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="badge badge-lunar">{{ str_replace('_', ' ', $article->research_type) }}</span>
                    @if($article->featured) <span class="badge bg-amber-900/40 text-amber-300 border-amber-800">Featured</span> @endif
                </div>
                <h2 class="text-base font-medium text-lunar-200 group-hover:text-lunar-100 mb-1 leading-snug">{{ $article->title }}</h2>
                <p class="text-xs text-lunar-400 line-clamp-2 mb-2">{{ Str::limit($article->abstract, 150) }}</p>
                <div class="text-xs text-lunar-600">
                    {{ $article->author_name }}
                    @if($article->published_date) · {{ $article->published_date->format('M Y') }} @endif
                    @if($article->journal_name) · {{ $article->journal_name }} @endif
                </div>
            </div>
        </a>
        @empty
        <div class="text-center py-12 text-lunar-500">No articles found with selected filters.</div>
        @endforelse
    </div>

    {{ $articles->links('components.pagination') }}
</div>
@endsection
