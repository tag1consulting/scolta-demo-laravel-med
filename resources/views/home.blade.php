@extends('layouts.app')

@section('title', 'Medical On The Moon')
@section('description', 'Comprehensive lunar medical reference for Moon residents, workers, and explorers. Powered by Scolta AI search.')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden bg-[#0e2040] border-b border-[#1b3860]">
    {{-- Background: NASA Apollo photograph of lunar surface (public domain) --}}
    <div class="absolute inset-0 pointer-events-none">
        <img src="{{ asset('images/hero-banner.webp') }}"
             alt="Lunar surface — NASA Apollo photograph"
             class="w-full h-full object-cover opacity-15"
             loading="eager">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0e2040] via-[#0e2040]/85 to-[#0e2040]/40"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <div class="max-w-3xl">
            <p class="text-xs tracking-widest uppercase text-blue-300 mb-4 font-medium">Lunar Medical Reference</p>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                Medical care,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-blue-400">384,400 km from home.</span>
            </h1>
            <p class="text-lg text-blue-100/80 mb-8 max-w-2xl">
                Comprehensive medical reference for Moon residents, miners, researchers, and visitors. Real medicine adapted for 1/6 gravity, cosmic radiation, regolith dust, and 1.3-second telemedicine delays.
            </p>

            {{-- Search hero --}}
            <form action="{{ route('search') }}" method="GET" class="flex gap-3 max-w-xl">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input
                        type="search"
                        name="q"
                        placeholder="headache, can't sleep, broken bone…"
                        class="w-full pl-12 pr-4 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:bg-white/15 text-sm backdrop-blur-sm"
                    >
                </div>
                <button type="submit" class="px-6 py-3.5 bg-blue-500 hover:bg-blue-400 text-white font-medium rounded-xl transition-colors text-sm whitespace-nowrap shadow-sm">
                    Search
                </button>
            </form>

            {{-- Showcase queries --}}
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach(['headache', "can't sleep", 'broken bone', 'dizzy', 'dust in lungs', 'radiation'] as $q)
                <a href="{{ route('search') }}?q={{ urlencode($q) }}" class="text-xs text-white/50 hover:text-white/90 transition-colors px-2.5 py-1 rounded-full border border-white/15 hover:border-white/40">
                    {{ $q }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Stats --}}
        <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl">
            @foreach([
                ['label' => 'Conditions', 'count' => $stats['conditions'], 'url' => route('conditions.index')],
                ['label' => 'Medications', 'count' => $stats['medications'], 'url' => route('medications.index')],
                ['label' => 'Procedures', 'count' => $stats['procedures'], 'url' => route('procedures.index')],
                ['label' => 'Articles', 'count' => $stats['articles'], 'url' => route('articles.index')],
            ] as $stat)
            <a href="{{ $stat['url'] }}" class="bg-white/10 border border-white/15 rounded-lg p-4 hover:bg-white/15 transition-colors group text-center">
                <div class="text-2xl font-bold text-white group-hover:text-blue-200 transition-colors">
                    {{ number_format($stat['count']) }}
                </div>
                <div class="text-xs text-white/50 mt-1">{{ $stat['label'] }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Body Systems navigation --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <p class="section-header">Browse by Body System</p>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
        @php
        $systems = [
            ['slug' => 'cardiovascular',  'label' => 'Cardiovascular',  'icon' => '♥', 'note' => 'Low-g deconditioning'],
            ['slug' => 'musculoskeletal', 'label' => 'Musculoskeletal',  'icon' => '🦴', 'note' => 'Bone loss, atrophy'],
            ['slug' => 'respiratory',     'label' => 'Respiratory',      'icon' => '🫁', 'note' => 'Dust, pressure'],
            ['slug' => 'neurological',    'label' => 'Neurological',     'icon' => '🧠', 'note' => 'Vestibular, ICP'],
            ['slug' => 'psychological',   'label' => 'Psychological',    'icon' => '🌙', 'note' => 'Isolation, Earth-sickness'],
            ['slug' => 'dermatological',  'label' => 'Dermatological',   'icon' => '🔆', 'note' => 'Radiation, suit irritation'],
            ['slug' => 'ophthalmological','label' => 'Ophthalmological', 'icon' => '👁',  'note' => 'Intracranial pressure'],
            ['slug' => 'oncological',     'label' => 'Oncological',      'icon' => '☢',  'note' => 'Radiation-induced'],
            ['slug' => 'infectious',      'label' => 'Infectious',       'icon' => '🦠', 'note' => 'Closed habitats'],
            ['slug' => 'trauma',          'label' => 'Trauma',           'icon' => '⚠',  'note' => 'Mining, EVA'],
            ['slug' => 'toxicological',   'label' => 'Toxicological',    'icon' => '⚗',  'note' => 'Regolith, chemicals'],
            ['slug' => 'nutritional',     'label' => 'Nutritional',      'icon' => '🌱', 'note' => 'Hydroponic diet'],
        ];
        @endphp
        @foreach($systems as $sys)
        <a href="{{ route('conditions.system', $sys['slug']) }}" class="card group text-center hover:border-blue-700/50">
            <div class="text-2xl mb-2">{{ $sys['icon'] }}</div>
            <div class="text-sm font-medium text-lunar-200 group-hover:text-lunar-100">{{ $sys['label'] }}</div>
            <div class="text-xs text-lunar-500 mt-1">{{ $sys['note'] }}</div>
        </a>
        @endforeach
    </div>
</section>

{{-- Emergency protocols banner --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
    <div class="card-emergency rounded-xl p-6 flex items-start gap-4">
        <div class="text-red-500 text-2xl flex-shrink-0">⚠</div>
        <div class="flex-1 min-w-0">
            <h2 class="text-base font-semibold text-red-700 mb-2">Emergency Protocols</h2>
            <p class="text-sm text-red-600/80 mb-4">For life-threatening emergencies, contact Earth Telemedicine (+1.3s relay delay) and initiate evacuation assessment. All emergency conditions are listed below.</p>
            @if($emergencyConditions->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach($emergencyConditions as $cond)
                <a href="{{ route('conditions.show', $cond->slug) }}" class="text-xs px-3 py-1.5 bg-red-100 border border-red-200 rounded-full text-red-700 hover:bg-red-200 transition-colors">
                    {{ $cond->lunar_variant_name ?? $cond->name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Recent research --}}
@if($featuredArticles->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex items-center justify-between mb-6">
        <p class="section-header">Featured Research</p>
        <a href="{{ route('articles.index') }}" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">All research →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($featuredArticles as $article)
        <a href="{{ route('articles.show', $article->slug) }}" class="card group">
            <div class="badge badge-lunar mb-3">{{ str_replace('_', ' ', $article->research_type) }}</div>
            <h3 class="text-sm font-medium text-lunar-200 group-hover:text-lunar-100 mb-2 leading-snug">{{ $article->title }}</h3>
            <p class="text-xs text-lunar-500 line-clamp-2">{{ Str::limit($article->abstract, 120) }}</p>
            <p class="text-xs text-lunar-600 mt-3">{{ $article->author_name }}</p>
        </a>
        @endforeach
    </div>
</section>
@endif

@endsection
