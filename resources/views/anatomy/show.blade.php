@extends('layouts.app')

@section('title', $anatomy->name)
@section('description', Str::limit($anatomy->description, 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="text-xs text-lunar-500 mb-6 flex items-center gap-2">
        <a href="{{ route('anatomy.index') }}" class="hover:text-lunar-300">Anatomy</a>
        <span>/</span>
        <span class="text-lunar-400 capitalize">{{ str_replace('_', ' ', $anatomy->body_system) }}</span>
        <span>/</span>
        <span class="text-lunar-400">{{ $anatomy->name }}</span>
    </nav>

    <header class="mb-10">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <span class="badge badge-lunar capitalize">{{ str_replace('_', ' ', $anatomy->body_system) }}</span>
            <span class="badge bg-lunar-800 text-lunar-300 border border-lunar-700">{{ $anatomy->structure_type }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-lunar-100 mb-2">{{ $anatomy->name }}</h1>
    </header>

    <div class="lunar-prose">

        <div class="bg-lunar-900 border border-lunar-700 rounded-xl p-6 mb-8">
            <p class="text-lunar-200">{{ $anatomy->description }}</p>
        </div>

        <h2>Normal Function</h2>
        <p>{{ $anatomy->normal_function }}</p>

        @if($anatomy->lunar_adaptation_arrival || $anatomy->lunar_adaptation_6m || $anatomy->lunar_adaptation_2y)
        <h2>Lunar Adaptations</h2>
        <div class="space-y-4">
            @if($anatomy->lunar_adaptation_arrival)
            <div class="bg-lunar-900 border border-lunar-700 rounded-lg p-5">
                <p class="section-header">On Arrival (First Weeks)</p>
                <p class="text-sm text-lunar-200">{{ $anatomy->lunar_adaptation_arrival }}</p>
            </div>
            @endif
            @if($anatomy->lunar_adaptation_6m)
            <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-5">
                <p class="text-xs text-blue-700 font-semibold mb-2 tracking-wider uppercase">6-Month Resident</p>
                <p class="text-sm text-slate-700">{{ $anatomy->lunar_adaptation_6m }}</p>
            </div>
            @endif
            @if($anatomy->lunar_adaptation_2y)
            <div class="border-l-4 border-purple-500 bg-purple-50 rounded-lg p-5">
                <p class="text-xs text-purple-700 font-semibold mb-2 tracking-wider uppercase">Long-Term Resident (2+ Years)</p>
                <p class="text-sm text-slate-700">{{ $anatomy->lunar_adaptation_2y }}</p>
            </div>
            @endif
        </div>
        @endif

    </div>

    <div class="mt-12 pt-6 border-t border-lunar-700">
        <a href="{{ route('anatomy.index') }}" class="text-lunar-400 hover:text-lunar-200 transition-colors text-sm">← Anatomy Browser</a>
    </div>
</div>
@endsection
