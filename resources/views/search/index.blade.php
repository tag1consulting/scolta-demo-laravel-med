@extends('layouts.app')

@section('title', $query ? "Search: {$query}" : 'Search')
@section('description', 'AI-powered lunar medical search with Scolta query expansion and re-ranking.')

@section('content')

{{-- Dark page header — visual bridge from the home hero --}}
<div class="bg-[#0e2040] border-b border-[#1b3860]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-3xl font-bold text-white mb-2">Lunar Medical Search</h1>
        <p class="text-blue-200/70 text-sm">Powered by <strong class="text-blue-200">Scolta</strong> — AI query expansion and re-ranking for lunar medical content.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Scolta search widget --}}
    <div class="bg-white border border-lunar-700 rounded-xl p-6 mb-6 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
            <span class="text-xs text-lunar-400 font-semibold tracking-wider uppercase">Scolta AI Search</span>
        </div>
        <x-scolta::search />
    </div>

    {{-- Showcase queries — collapsed by default --}}
    <details class="group mb-6">
        <summary class="flex items-center gap-2 cursor-pointer text-xs text-lunar-500 hover:text-lunar-300 transition-colors select-none list-none">
            <svg class="w-3.5 h-3.5 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-medium tracking-wider uppercase">Showcase Queries — Try these to see Scolta expansion</span>
        </summary>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($showcaseQueries as $q => $expansion)
            <a href="{{ route('search') }}?q={{ urlencode($q) }}"
               class="bg-white border border-lunar-700 hover:border-blue-400 rounded-lg p-4 group/card transition-all shadow-sm hover:shadow {{ $query === $q ? 'border-blue-500 bg-blue-50' : '' }}">
                <div class="font-medium text-sm text-lunar-200 group-hover/card:text-lunar-100 mb-1">"{{ $q }}"</div>
                <div class="text-xs text-lunar-400">→ {{ $expansion }}</div>
            </a>
            @endforeach
        </div>
    </details>

    {{-- How Scolta works explainer --}}
    <div class="bg-lunar-900 border border-lunar-700 rounded-xl p-6">
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
