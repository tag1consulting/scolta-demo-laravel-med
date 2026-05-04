@extends('layouts.app')

@section('title', $medication->generic_name)
@section('description', Str::limit($medication->indications, 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="text-xs text-lunar-500 mb-6 flex items-center gap-2">
        <a href="{{ route('medications.index') }}" class="hover:text-lunar-300">Medications</a>
        <span>/</span>
        <span class="text-lunar-400">{{ $medication->generic_name }}</span>
    </nav>

    <header class="mb-10">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <span class="badge badge-lunar">{{ $medication->drug_class }}</span>
            @if($medication->lunar_critical) <span class="badge badge-lunar">⚠ Lunar Critical</span> @endif
            @if($medication->who_essential) <span class="badge bg-green-50 text-green-700 border border-green-200">WHO Essential</span> @endif
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-lunar-100 mb-2">{{ $medication->generic_name }}</h1>
        @if($medication->brand_names)
        <p class="text-lunar-500 text-sm">Also known as: {{ $medication->brand_names }}</p>
        @endif
    </header>

    <div class="lunar-prose">

        <div class="bg-lunar-900 border border-lunar-700 rounded-xl p-6 mb-8">
            <h3 class="section-header">Mechanism of Action</h3>
            <p class="text-lunar-200">{{ $medication->mechanism }}</p>
        </div>

        <h2>Indications</h2>
        <p>{{ $medication->indications }}</p>

        <h2>Dosing</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-lunar-900 border border-lunar-700 rounded-lg p-4">
                <p class="section-header">Standard (Earth)</p>
                <p class="text-sm text-lunar-200">{{ $medication->dosing_standard }}</p>
            </div>
            @if($medication->dosing_lunar)
            <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-4">
                <p class="text-xs text-blue-700 font-semibold mb-2 tracking-wider uppercase">Lunar Protocol</p>
                <p class="text-sm text-slate-700">{{ $medication->dosing_lunar }}</p>
            </div>
            @endif
        </div>

        @if($medication->storage_lunar || $medication->storage_standard)
        <h2>Storage</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($medication->storage_standard)
            <div class="bg-lunar-900 border border-lunar-700 rounded-lg p-4">
                <p class="section-header">Standard</p>
                <p class="text-sm text-lunar-200">{{ $medication->storage_standard }}</p>
            </div>
            @endif
            @if($medication->storage_lunar)
            <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-4">
                <p class="text-xs text-blue-700 font-semibold mb-2 tracking-wider uppercase">Lunar (Radiation + Temperature)</p>
                <p class="text-sm text-slate-700">{{ $medication->storage_lunar }}</p>
            </div>
            @endif
        </div>
        @endif

        @if($medication->supply_chain_notes)
        <h2>Supply Chain & Lunar Pharmacy Notes</h2>
        <p>{{ $medication->supply_chain_notes }}</p>
        @endif

        @if($medication->interactions)
        <h2>Drug Interactions</h2>
        <p>{{ $medication->interactions }}</p>
        @endif

        @if($medication->contraindications)
        <h2>Contraindications</h2>
        <p>{{ $medication->contraindications }}</p>
        @endif

        @if($medication->side_effects)
        <h2>Side Effects</h2>
        <p>{{ $medication->side_effects }}</p>
        @endif

        @if($medication->alternatives)
        <h2>Lunar Alternatives</h2>
        <p>{{ $medication->alternatives }}</p>
        @endif

    </div>

    <div class="mt-12 pt-6 border-t border-lunar-700">
        <a href="{{ route('medications.index') }}" class="text-lunar-400 hover:text-lunar-200 transition-colors text-sm">← All Medications</a>
    </div>
</div>
@endsection
