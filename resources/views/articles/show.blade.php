@extends('layouts.app')

@section('title', $article->title)
@section('description', Str::limit($article->abstract, 160))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="text-xs text-lunar-500 mb-6 flex items-center gap-2">
        <a href="{{ route('articles.index') }}" class="hover:text-lunar-300">Research</a>
        <span>/</span>
        <span class="text-lunar-400">{{ str_replace('_', ' ', $article->research_type) }}</span>
    </nav>

    <header class="mb-10">
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="badge badge-lunar">{{ str_replace('_', ' ', $article->research_type) }}</span>
            @if($article->featured) <span class="badge bg-amber-900/40 text-amber-300 border-amber-800">Featured</span> @endif
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-lunar-50 leading-tight mb-4">{{ $article->title }}</h1>
        <div class="text-sm text-lunar-400">
            <span class="font-medium text-lunar-300">{{ $article->author_name }}</span>
            @if($article->author_affiliation) · {{ $article->author_affiliation }} @endif
        </div>
        @if($article->journal_name || $article->published_date)
        <div class="text-xs text-lunar-500 mt-1">
            @if($article->journal_name) {{ $article->journal_name }} @endif
            @if($article->volume_issue) · {{ $article->volume_issue }} @endif
            @if($article->published_date) · {{ $article->published_date->format('F j, Y') }} @endif
        </div>
        @endif
    </header>

    {{-- Abstract --}}
    <div class="bg-lunar-900/60 border border-lunar-800 rounded-xl p-6 mb-8">
        <p class="section-header">Abstract</p>
        <p class="text-lunar-200 leading-relaxed italic">{{ $article->abstract }}</p>
    </div>

    {{-- Body --}}
    <div class="lunar-prose">
        {!! nl2br(e($article->content)) !!}
    </div>

    @if($article->keywords)
    <div class="mt-8 pt-6 border-t border-lunar-800">
        <p class="section-header">Keywords</p>
        <p class="text-sm text-lunar-400">{{ $article->keywords }}</p>
    </div>
    @endif

    @if($article->references)
    <div class="mt-6 pt-6 border-t border-lunar-800">
        <p class="section-header">References</p>
        <div class="text-xs text-lunar-500 leading-relaxed">{!! nl2br(e($article->references)) !!}</div>
    </div>
    @endif

    <div class="mt-12 pt-6 border-t border-lunar-800">
        <a href="{{ route('articles.index') }}" class="text-lunar-500 hover:text-lunar-300 transition-colors text-sm">← All Research</a>
    </div>
</div>
@endsection
