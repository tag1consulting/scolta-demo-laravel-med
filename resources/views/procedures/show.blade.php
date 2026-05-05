@extends('layouts.app')

@section('title', $procedure->name)
@section('description', Str::limit($procedure->description, 160))

@if(in_array($procedure->risk_level, ['high', 'critical']))
@section('emergency', true)
@endif

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="text-xs text-lunar-500 mb-6 flex items-center gap-2">
        <a href="{{ route('procedures.index') }}" class="hover:text-lunar-300">Procedures</a>
        <span>/</span>
        <span class="text-lunar-400">{{ $procedure->name }}</span>
    </nav>

    <header class="mb-10">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <span class="badge badge-risk-{{ $procedure->risk_level }}">{{ ucfirst($procedure->risk_level) }} Risk</span>
            <span class="badge badge-lunar">{{ ucwords(str_replace('_', ' ', $procedure->category)) }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-lunar-100 mb-2">{{ $procedure->name }}</h1>
    </header>

    <div class="lunar-prose">

        <div class="bg-lunar-900 border border-lunar-700 rounded-xl p-6 mb-8">
            <p class="text-lunar-200">{{ $procedure->description }}</p>
        </div>

        <h2>Indications</h2>
        <p>{{ $procedure->indications }}</p>

        @if($procedure->contraindications)
        <h2>Contraindications</h2>
        <p>{{ $procedure->contraindications }}</p>
        @endif

        <h2>Equipment Required</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-lunar-900 border border-lunar-700 rounded-lg p-4">
                <p class="section-header">Standard Equipment</p>
                <p class="text-sm text-lunar-200">{{ $procedure->equipment_standard }}</p>
            </div>
            @if($procedure->equipment_lunar)
            <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-4">
                <p class="text-xs text-blue-700 font-semibold mb-2 tracking-wider uppercase">Lunar Medical Bay Substitutions</p>
                <p class="text-sm text-slate-700">{{ $procedure->equipment_lunar }}</p>
            </div>
            @endif
        </div>

        <h2>Procedure Steps</h2>
        <p>{{ $procedure->steps }}</p>

        @if($procedure->steps_lunar)
        <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-5 mt-4">
            <p class="text-xs text-blue-700 font-semibold mb-2 tracking-wider uppercase">Lunar Technique Modifications (1/6 Gravity)</p>
            <p class="text-sm text-slate-700">{{ $procedure->steps_lunar }}</p>
        </div>
        @endif

        @if($procedure->telemedicine_points)
        <h2>Telemedicine Guidance Points</h2>
        <div class="bg-lunar-900 border border-lunar-700 rounded-lg p-5">
            <p class="text-xs text-lunar-500 mb-3">Contact Earth Medical Relay (+1.3s delay) at these critical decision points:</p>
            <p class="text-lunar-200 text-sm">{{ $procedure->telemedicine_points }}</p>
        </div>
        @endif

        @if($procedure->training_requirements)
        <h2>Training Requirements</h2>
        <p>{{ $procedure->training_requirements }}</p>
        @endif

        @if($procedure->complications)
        <h2>Possible Complications</h2>
        <p>{{ $procedure->complications }}</p>
        @endif

    </div>

    <div class="mt-12 pt-6 border-t border-lunar-700">
        <a href="{{ route('procedures.index') }}" class="text-lunar-400 hover:text-lunar-200 transition-colors text-sm">← All Procedures</a>
    </div>
</div>
@endsection
