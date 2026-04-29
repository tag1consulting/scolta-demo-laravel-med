@extends('layouts.app')

@section('title', $condition->lunar_variant_name ?? $condition->name)
@section('description', Str::limit($condition->description, 160))

@if($condition->is_emergency)
@section('emergency', true)
@endif

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-lunar-500 mb-6 flex items-center gap-2">
        <a href="{{ route('conditions.index') }}" class="hover:text-lunar-300">Conditions</a>
        <span>/</span>
        <a href="{{ route('conditions.system', $condition->body_system) }}" class="hover:text-lunar-300 capitalize">{{ str_replace('_', ' ', $condition->body_system) }}</a>
        <span>/</span>
        <span class="text-lunar-400">{{ $condition->lunar_variant_name ?? $condition->name }}</span>
    </nav>

    {{-- Header --}}
    <header class="mb-10">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <span class="badge badge-severity-{{ $condition->severity }}">{{ ucfirst($condition->severity) }}</span>
            @if($condition->is_emergency)
            <span class="badge badge-severity-critical">⚠ Emergency Protocol</span>
            @endif
            @if($condition->icd10_code)
            <span class="badge badge-lunar">ICD-10: {{ $condition->icd10_code }}</span>
            @endif
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-lunar-50 mb-2">
            {{ $condition->lunar_variant_name ?? $condition->name }}
        </h1>
        @if($condition->lunar_variant_name && $condition->lunar_variant_name !== $condition->name)
        <p class="text-lunar-400 text-sm">Earth designation: {{ $condition->name }}</p>
        @endif
    </header>

    <div class="lunar-prose">

        {{-- Overview --}}
        <div class="bg-lunar-900/60 border border-lunar-800 rounded-xl p-6 mb-8">
            <p class="text-lunar-200 leading-relaxed">{{ $condition->description }}</p>
        </div>

        {{-- Lunar risk factors --}}
        @if($condition->lunar_risk_factors)
        <h2>Lunar Risk Factors</h2>
        <div class="bg-amber-950/30 border border-amber-800/40 rounded-lg p-5 mb-6">
            <p class="text-amber-200/80">{{ $condition->lunar_risk_factors }}</p>
        </div>
        @endif

        {{-- Symptoms --}}
        <h2>Symptoms</h2>
        <p>{{ $condition->symptoms }}</p>
        @if($condition->lunar_symptoms)
        <div class="bg-blue-950/30 border border-blue-800/40 rounded-lg p-4 mt-3">
            <p class="text-xs text-blue-400 font-medium mb-1 tracking-wider uppercase">Lunar Presentation</p>
            <p class="text-blue-200/80 text-sm">{{ $condition->lunar_symptoms }}</p>
        </div>
        @endif

        {{-- Diagnosis --}}
        <h2>Diagnosis</h2>
        <p>{{ $condition->diagnosis }}</p>

        {{-- Treatment --}}
        <h2>Treatment</h2>
        <p>{{ $condition->treatment }}</p>
        @if($condition->treatment_lunar)
        <div class="bg-blue-950/30 border border-blue-800/40 rounded-lg p-4 mt-3">
            <p class="text-xs text-blue-400 font-medium mb-1 tracking-wider uppercase">Lunar Medical Bay Protocol</p>
            <p class="text-blue-200/80 text-sm">{{ $condition->treatment_lunar }}</p>
        </div>
        @endif

        {{-- Evacuation --}}
        @if($condition->evacuation_criteria)
        <h2 class="text-red-300 border-t-red-900/50">Evacuation Criteria</h2>
        <div class="card-emergency rounded-lg p-5">
            <p class="text-red-200/80">{{ $condition->evacuation_criteria }}</p>
        </div>
        @endif

        {{-- Prevention --}}
        @if($condition->prevention)
        <h2>Prevention</h2>
        <p>{{ $condition->prevention }}</p>
        @endif

    </div>

    {{-- Back link --}}
    <div class="mt-12 pt-6 border-t border-lunar-800 flex items-center justify-between text-sm">
        <a href="{{ route('conditions.index') }}" class="text-lunar-500 hover:text-lunar-300 transition-colors">← All Conditions</a>
        <a href="{{ route('conditions.system', $condition->body_system) }}" class="text-blue-400 hover:text-blue-300 transition-colors capitalize">
            {{ str_replace('_', ' ', $condition->body_system) }} conditions →
        </a>
    </div>
</div>
@endsection
