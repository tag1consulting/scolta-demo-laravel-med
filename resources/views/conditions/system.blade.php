@extends('layouts.app')

@section('title', ucwords(str_replace('_', ' ', $system)) . ' Conditions')
@section('description', 'Medical conditions affecting the ' . str_replace('_', ' ', $system) . ' system in lunar residents and workers.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-lunar-500 mb-6 flex items-center gap-2">
        <a href="{{ route('conditions.index') }}" class="hover:text-lunar-300">Conditions</a>
        <span>/</span>
        <span class="text-lunar-400 capitalize">{{ str_replace('_', ' ', $system) }}</span>
    </nav>

    <h1 class="text-3xl font-bold text-lunar-100 mb-2 capitalize">{{ str_replace('_', ' ', $system) }} Conditions</h1>
    <p class="text-lunar-400 mb-8">{{ $conditions->total() }} conditions affecting the {{ str_replace('_', ' ', $system) }} system.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
        @forelse($conditions as $condition)
        <a href="{{ route('conditions.show', $condition->slug) }}" class="card group {{ $condition->is_emergency ? 'border-red-900/50 hover:border-red-700' : '' }}">
            <div class="flex items-start justify-between gap-2 mb-2">
                <h2 class="text-sm font-medium text-lunar-200 group-hover:text-lunar-100 leading-snug">
                    {{ $condition->lunar_variant_name ?? $condition->name }}
                </h2>
                <span class="badge badge-severity-{{ $condition->severity }} flex-shrink-0">{{ ucfirst($condition->severity) }}</span>
            </div>
            <p class="text-xs text-lunar-400 line-clamp-2">{{ Str::limit($condition->description, 100) }}</p>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-lunar-500">No conditions found for this system yet.</div>
        @endforelse
    </div>

    {{ $conditions->links('components.pagination') }}
</div>
@endsection
