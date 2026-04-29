@extends('layouts.app')

@section('title', 'Medications')
@section('description', 'Lunar pharmacy reference — dosing, storage, and supply chain for medications in the lunar environment.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-lunar-100 mb-2">Medications</h1>
        <p class="text-lunar-400">Dosing, storage, supply chain, and interactions adapted for lunar pharmacy. Low-gravity bioavailability, radiation shielding requirements, and resupply intervals.</p>
    </div>

    <form method="GET" class="mb-8 flex flex-wrap gap-3 items-center">
        <select name="class" class="bg-lunar-900 border border-lunar-700 text-lunar-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            <option value="">All Classes</option>
            @foreach($classes as $class)
            <option value="{{ $class }}" {{ request('class') === $class ? 'selected' : '' }}>{{ $class }}</option>
            @endforeach
        </select>
        <label class="flex items-center gap-2 text-sm text-lunar-400 cursor-pointer">
            <input type="checkbox" name="lunar_critical" value="1" {{ request('lunar_critical') ? 'checked' : '' }} class="rounded border-lunar-600 bg-lunar-900 text-blue-500">
            Lunar-critical only
        </label>
        <label class="flex items-center gap-2 text-sm text-lunar-400 cursor-pointer">
            <input type="checkbox" name="who_essential" value="1" {{ request('who_essential') ? 'checked' : '' }} class="rounded border-lunar-600 bg-lunar-900 text-blue-500">
            WHO Essential
        </label>
        <button type="submit" class="px-4 py-2 bg-lunar-800 hover:bg-lunar-700 text-lunar-200 text-sm rounded-lg transition-colors">Filter</button>
        @if(request()->hasAny(['class', 'lunar_critical', 'who_essential']))
        <a href="{{ route('medications.index') }}" class="text-sm text-lunar-500 hover:text-lunar-300 transition-colors">Clear</a>
        @endif
    </form>

    <div class="mb-4 text-sm text-lunar-500">
        Showing {{ $medications->firstItem() }}–{{ $medications->lastItem() }} of {{ number_format($medications->total()) }} medications
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
        @forelse($medications as $med)
        <a href="{{ route('medications.show', $med->slug) }}" class="card group">
            <div class="flex items-start justify-between gap-2 mb-2">
                <h2 class="text-sm font-medium text-lunar-200 group-hover:text-lunar-100">{{ $med->generic_name }}</h2>
                <div class="flex gap-1 flex-shrink-0">
                    @if($med->lunar_critical) <span class="badge badge-lunar">Lunar</span> @endif
                    @if($med->who_essential) <span class="badge bg-green-900/40 text-green-400 border-green-800">WHO</span> @endif
                </div>
            </div>
            <p class="text-xs text-lunar-500 mb-2">{{ $med->drug_class }}</p>
            <p class="text-xs text-lunar-400 line-clamp-2">{{ Str::limit($med->indications, 100) }}</p>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-lunar-500">No medications found with selected filters.</div>
        @endforelse
    </div>

    {{ $medications->links('components.pagination') }}
</div>
@endsection
