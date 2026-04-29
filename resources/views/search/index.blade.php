@extends('layouts.app')

@section('title', $query ? "Search: {$query}" : 'Search')
@section('description', 'AI-powered lunar medical search with Scolta query expansion and re-ranking.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-3xl font-bold text-lunar-100 mb-2">Lunar Medical Search</h1>
    <p class="text-lunar-400 text-sm mb-8">Powered by <strong class="text-lunar-300">Scolta</strong> — AI query expansion and re-ranking for lunar medical content.</p>

    {{-- Search form --}}
    <form action="{{ route('search') }}" method="GET" class="mb-8">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-lunar-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    type="search"
                    name="q"
                    value="{{ $query }}"
                    placeholder="headache, can't sleep, broken bone, feeling sad…"
                    autofocus
                    class="w-full pl-12 pr-4 py-3.5 bg-lunar-900 border border-lunar-700 rounded-xl text-lunar-100 placeholder-lunar-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                >
            </div>
            <button type="submit" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl transition-colors whitespace-nowrap">
                Search
            </button>
        </div>
    </form>

    {{-- Showcase queries --}}
    <div class="mb-10">
        <p class="section-header mb-3">Showcase Queries — Demonstrating Scolta Expansion</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($showcaseQueries as $q => $expansion)
            <a href="{{ route('search') }}?q={{ urlencode($q) }}"
               class="bg-lunar-900/60 border border-lunar-800 hover:border-blue-700/50 rounded-lg p-4 group transition-colors {{ $query === $q ? 'border-blue-600 bg-blue-950/30' : '' }}">
                <div class="font-medium text-sm text-lunar-200 group-hover:text-lunar-100 mb-1">"{{ $q }}"</div>
                <div class="text-xs text-lunar-500">→ {{ $expansion }}</div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Scolta search widget placeholder --}}
    <div class="bg-lunar-900 border border-lunar-700 rounded-xl p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
            <span class="text-xs text-lunar-400 font-medium tracking-wider uppercase">Scolta AI Search</span>
        </div>

        @if($query)
            <p class="text-lunar-300 text-sm mb-4">Searching for: <strong class="text-lunar-100">"{{ $query }}"</strong></p>
            <p class="text-xs text-lunar-500 mb-6">Scolta is expanding your query and re-ranking results by lunar medical relevance. The search index will populate after running <code class="text-blue-400">php artisan scolta:build</code>.</p>
        @else
            <p class="text-lunar-400 text-sm">Enter a query above to see Scolta's AI-powered results.</p>
        @endif

        {{-- Scolta widget target --}}
        <div id="scolta-results" class="min-h-32">
            @if($query)
            <div class="text-center py-8 text-lunar-600 text-sm">
                Search index not yet built. Run <code class="text-blue-400">ddev artisan scolta:build</code> to index content.
            </div>
            @endif
        </div>
    </div>

    {{-- How Scolta works explainer --}}
    <div class="mt-8 bg-lunar-900/40 border border-lunar-800 rounded-xl p-6">
        <h2 class="text-sm font-semibold text-lunar-200 mb-4">How Scolta Search Works</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-lunar-400">
            <div>
                <div class="text-blue-400 font-medium mb-1">1. Query Expansion</div>
                <p>Loose queries like "headache" are expanded to include decompression headache, CO₂ buildup, caffeine withdrawal, and radiation sickness — all conditions a lunar resident might mean.</p>
            </div>
            <div>
                <div class="text-blue-400 font-medium mb-1">2. AI Re-ranking</div>
                <p>Results are re-ranked by lunar medical relevance. A condition rare on Earth but common in low-gravity environments rises to the top for lunar users.</p>
            </div>
            <div>
                <div class="text-blue-400 font-medium mb-1">3. Zero Infrastructure</div>
                <p>Scolta runs entirely in-browser using WebAssembly. No search server, no API keys at runtime, no latency from Earth. Index lives in the site itself.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Scolta search integration will be added after index is built --}}
@endpush
