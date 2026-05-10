@extends('layouts.app')

@section('title', 'About This Demo')
@section('description', 'About Medical On The Moon — a Scolta AI search demonstration by Tag1 Consulting.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="prose prose-invert max-w-none">
        <h1 class="text-3xl font-bold text-white mb-8">About This Demo</h1>

        <h2 class="text-xl font-semibold text-lunar-100 mt-8 mb-4">About This Site</h2>
        <p class="text-slate-300 mb-4"><strong class="text-white">Medical On The Moon is a fictional medical reference.</strong> It was created by Tag1 Consulting to demonstrate the capabilities of Scolta, an open-source AI-powered search platform, on a content-rich Laravel application with specialized medical vocabulary.</p>

        <h2 class="text-xl font-semibold text-lunar-100 mt-8 mb-4">What You Are Looking At</h2>
        <p class="text-slate-300 mb-4">This site is a Laravel demonstration built to show how Scolta performs on highly technical, vocabulary-rich content. The site contains thousands of medical reference entries including:</p>
        <ul class="text-slate-300 mb-4 space-y-1 list-disc list-inside">
            <li>Medical conditions adapted for the lunar environment (reduced gravity, radiation, isolation)</li>
            <li>Medications with dosage adjustments for Moon-based administration</li>
            <li>Surgical and emergency procedures adapted for low-gravity operating environments</li>
            <li>Anatomical references with notes on lunar physiology effects</li>
            <li>Research articles on space medicine and long-duration lunar habitation</li>
        </ul>
        <p class="text-slate-300 mb-4">All medical content is based on real medicine, adapted for the fictional premise. The site should not be used as actual medical advice — but the underlying medical information is accurate.</p>

        <h2 class="text-xl font-semibold text-lunar-100 mt-8 mb-4">What Scolta Does Here</h2>
        <p class="text-slate-300 mb-4">The search bar uses Scolta to let you explore the medical reference using natural language rather than category browsing. Try these example queries:</p>
        <ul class="text-slate-300 mb-4 space-y-1 list-disc list-inside">
            <li>"How does reduced gravity affect bone density long-term?"</li>
            <li>"Emergency treatment for decompression sickness on the Moon"</li>
            <li>"Medications that require different dosing in low gravity"</li>
            <li>"Psychological effects of long-duration lunar isolation"</li>
            <li>"Surgery protocols without Earth-level atmospheric pressure"</li>
        </ul>
        <p class="text-slate-300 mb-4">Scolta uses Pagefind for full-text indexing, Claude via the Anthropic API for query expansion and AI-generated overviews, and a custom BM25-based scoring layer tuned for medical terminology.</p>

        <h2 class="text-xl font-semibold text-lunar-100 mt-8 mb-4">About Tag1 Consulting</h2>
        <p class="text-slate-300 mb-4">Tag1 Consulting is one of the leading Drupal development and consulting firms in the world. Tag1 built and open-sources Scolta as a demonstration of what AI-augmented content discovery can look like on modern web platforms — including Laravel applications. For more information about Tag1 and Scolta, visit <a href="https://tag1.com" class="text-blue-400 hover:text-blue-300">tag1.com</a>.</p>

        <h2 class="text-xl font-semibold text-lunar-100 mt-8 mb-4">Reuse and Attribution</h2>
        <p class="text-slate-300 mb-4">If you are evaluating Scolta for your organization and have questions about how this demo was built or how to implement Scolta for your use case, contact Tag1 Consulting.</p>
    </div>
</div>
@endsection
