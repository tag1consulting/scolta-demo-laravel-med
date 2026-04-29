@extends('layouts.app')

@section('title', 'Anatomy & Physiology')
@section('description', 'Human anatomy adapted for lunar residence — 1/6 gravity adaptations, bone loss, cardiovascular deconditioning, and long-term physiological changes.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-lunar-100 mb-2">Anatomy & Physiology</h1>
        <p class="text-lunar-400">How the human body adapts to lunar residence — from arrival through long-term habitation. 1/6 gravity, radiation exposure, and isolation effects on every major system.</p>
    </div>

    <form method="GET" class="mb-8 flex gap-3 items-center">
        <select name="system" class="bg-lunar-900 border border-lunar-700 text-lunar-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            <option value="">All Systems</option>
            @foreach($systems as $sys)
            <option value="{{ $sys }}" {{ request('system') === $sys ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $sys)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-lunar-800 hover:bg-lunar-700 text-lunar-200 text-sm rounded-lg transition-colors">Filter</button>
        @if(request('system'))
        <a href="{{ route('anatomy.index') }}" class="text-sm text-lunar-500 hover:text-lunar-300 transition-colors">Clear</a>
        @endif
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
        @forelse($anatomies as $anatomy)
        <a href="{{ route('anatomy.show', $anatomy->slug) }}" class="card group">
            <div class="flex items-start justify-between gap-2 mb-2">
                <h2 class="text-sm font-medium text-lunar-200 group-hover:text-lunar-100 leading-snug">{{ $anatomy->name }}</h2>
                <span class="badge badge-lunar flex-shrink-0">{{ $anatomy->structure_type }}</span>
            </div>
            <p class="text-xs text-lunar-500 mb-2 capitalize">{{ str_replace('_', ' ', $anatomy->body_system) }}</p>
            <p class="text-xs text-lunar-400 line-clamp-2">{{ Str::limit($anatomy->description, 100) }}</p>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-lunar-500">No anatomy entries found with the selected filters.</div>
        @endforelse
    </div>

    {{ $anatomies->links('components.pagination') }}
</div>
@endsection
