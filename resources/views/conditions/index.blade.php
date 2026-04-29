@extends('layouts.app')

@section('title', 'Conditions')
@section('description', 'Browse all medical conditions relevant to lunar residents and workers, organized by body system and severity.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-lunar-100 mb-2">Medical Conditions</h1>
        <p class="text-lunar-400">Real medical conditions adapted for lunar environment — 1/6 gravity, radiation, regolith dust, and isolation.</p>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-8 flex flex-wrap gap-3 items-center">
        <select name="system" class="bg-lunar-900 border border-lunar-700 text-lunar-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            <option value="">All Systems</option>
            @foreach($systems as $sys)
            <option value="{{ $sys }}" {{ request('system') === $sys ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $sys)) }}</option>
            @endforeach
        </select>
        <select name="severity" class="bg-lunar-900 border border-lunar-700 text-lunar-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            <option value="">All Severities</option>
            @foreach(['minor', 'moderate', 'severe', 'critical'] as $sev)
            <option value="{{ $sev }}" {{ request('severity') === $sev ? 'selected' : '' }}>{{ ucfirst($sev) }}</option>
            @endforeach
        </select>
        <label class="flex items-center gap-2 text-sm text-lunar-400 cursor-pointer">
            <input type="checkbox" name="emergency" value="1" {{ request('emergency') ? 'checked' : '' }} class="rounded border-lunar-600 bg-lunar-900 text-red-500">
            Emergency only
        </label>
        <button type="submit" class="px-4 py-2 bg-lunar-800 hover:bg-lunar-700 text-lunar-200 text-sm rounded-lg transition-colors">Filter</button>
        @if(request()->hasAny(['system', 'severity', 'emergency']))
        <a href="{{ route('conditions.index') }}" class="text-sm text-lunar-500 hover:text-lunar-300 transition-colors">Clear</a>
        @endif
    </form>

    {{-- Results --}}
    <div class="mb-4 text-sm text-lunar-500">
        Showing {{ $conditions->firstItem() }}–{{ $conditions->lastItem() }} of {{ number_format($conditions->total()) }} conditions
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
        @forelse($conditions as $condition)
        <a href="{{ route('conditions.show', $condition->slug) }}" class="card group {{ $condition->is_emergency ? 'border-red-900/50 hover:border-red-700' : '' }}">
            <div class="flex items-start justify-between gap-2 mb-2">
                <h2 class="text-sm font-medium text-lunar-200 group-hover:text-lunar-100 leading-snug">
                    {{ $condition->lunar_variant_name ?? $condition->name }}
                </h2>
                <span class="badge badge-severity-{{ $condition->severity }} flex-shrink-0">{{ ucfirst($condition->severity) }}</span>
            </div>
            @if($condition->lunar_variant_name && $condition->lunar_variant_name !== $condition->name)
            <p class="text-xs text-lunar-600 mb-2">{{ $condition->name }}</p>
            @endif
            <p class="text-xs text-lunar-400 line-clamp-2">{{ Str::limit($condition->description, 100) }}</p>
            <div class="mt-3 flex items-center gap-2">
                <span class="text-xs text-lunar-600 capitalize">{{ str_replace('_', ' ', $condition->body_system) }}</span>
                @if($condition->is_emergency)
                <span class="badge badge-severity-critical">Emergency</span>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-lunar-500">No conditions found with the selected filters.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    {{ $conditions->links('components.pagination') }}
</div>
@endsection
