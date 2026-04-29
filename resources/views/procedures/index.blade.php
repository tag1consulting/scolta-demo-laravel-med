@extends('layouts.app')

@section('title', 'Procedures')
@section('description', 'Medical procedures adapted for lunar environment — from first aid to surgery in 1/6 gravity with limited equipment.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-lunar-100 mb-2">Medical Procedures</h1>
        <p class="text-lunar-400">From first aid to surgical intervention — adapted for 1/6 gravity, limited equipment, and telemedicine guidance from Earth.</p>
    </div>

    <form method="GET" class="mb-8 flex flex-wrap gap-3 items-center">
        <select name="category" class="bg-lunar-900 border border-lunar-700 text-lunar-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $cat)) }}</option>
            @endforeach
        </select>
        <select name="risk" class="bg-lunar-900 border border-lunar-700 text-lunar-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
            <option value="">All Risk Levels</option>
            @foreach(['low', 'medium', 'high', 'critical'] as $r)
            <option value="{{ $r }}" {{ request('risk') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-lunar-800 hover:bg-lunar-700 text-lunar-200 text-sm rounded-lg transition-colors">Filter</button>
        @if(request()->hasAny(['category', 'risk']))
        <a href="{{ route('procedures.index') }}" class="text-sm text-lunar-500 hover:text-lunar-300 transition-colors">Clear</a>
        @endif
    </form>

    <div class="mb-4 text-sm text-lunar-500">
        Showing {{ $procedures->firstItem() }}–{{ $procedures->lastItem() }} of {{ number_format($procedures->total()) }} procedures
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
        @forelse($procedures as $proc)
        <a href="{{ route('procedures.show', $proc->slug) }}" class="card group {{ in_array($proc->risk_level, ['high', 'critical']) ? 'border-orange-900/40 hover:border-orange-700' : '' }}">
            <div class="flex items-start justify-between gap-2 mb-2">
                <h2 class="text-sm font-medium text-lunar-200 group-hover:text-lunar-100 leading-snug">{{ $proc->name }}</h2>
                <span class="badge badge-risk-{{ $proc->risk_level }} flex-shrink-0">{{ ucfirst($proc->risk_level) }}</span>
            </div>
            <p class="text-xs text-lunar-500 mb-2">{{ ucwords(str_replace('_', ' ', $proc->category)) }}</p>
            <p class="text-xs text-lunar-400 line-clamp-2">{{ Str::limit($proc->description, 100) }}</p>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-lunar-500">No procedures found with the selected filters.</div>
        @endforelse
    </div>

    {{ $procedures->links('components.pagination') }}
</div>
@endsection
