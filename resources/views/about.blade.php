@extends('layouts.app')

@section('title', 'About This Demo')
@section('description', 'About the Medical On The Moon demo site — a Tag1 Consulting demonstration of Scolta AI-powered search on Laravel.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-2xl font-bold text-lunar-50 mb-8">About This Demo</h1>

    <div class="space-y-8">

        <section>
            <h2 class="text-lg font-semibold text-lunar-100 mb-3">About This Site</h2>
            <p class="text-lunar-300 leading-relaxed"><strong class="text-lunar-100">Medical On The Moon is a fictional medical reference.</strong> It was created by Tag1 Consulting to demonstrate the capabilities of Scolta, an open-source AI-powered search platform, on a Laravel-based reference site.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-lunar-100 mb-3">What You Are Looking At</h2>
            <p class="text-lunar-300 leading-relaxed mb-3">This site is a Laravel demonstration built to show how Scolta performs on structured medical reference content adapted for a fictional lunar setting. The site contains:</p>
            <ul class="space-y-1.5 text-lunar-300 text-sm">
                <li class="flex gap-2"><span class="text-blue-400">›</span> 73 medical conditions adapted for low gravity and space environments</li>
                <li class="flex gap-2"><span class="text-blue-400">›</span> 110 procedures covering emergency and routine lunar medicine</li>
                <li class="flex gap-2"><span class="text-blue-400">›</span> 39 research articles on lunar physiology and space medicine</li>
                <li class="flex gap-2"><span class="text-blue-400">›</span> Medication and anatomical reference for Moon residents</li>
            </ul>
            <p class="text-lunar-300 leading-relaxed mt-3">All medical content is grounded in real space medicine research but adapted for the fictional premise of permanent lunar habitation.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-lunar-100 mb-3">What Scolta Does Here</h2>
            <p class="text-lunar-300 leading-relaxed mb-3">The search bar uses Scolta to let you explore the reference by asking natural language questions. Try these example queries:</p>
            <ul class="space-y-1.5 text-lunar-300 text-sm">
                <li class="flex gap-2"><span class="text-blue-400">›</span> "What are the symptoms of radiation exposure?"</li>
                <li class="flex gap-2"><span class="text-blue-400">›</span> "How does low gravity affect the cardiovascular system?"</li>
                <li class="flex gap-2"><span class="text-blue-400">›</span> "Emergency protocols for decompression"</li>
                <li class="flex gap-2"><span class="text-blue-400">›</span> "What medications are safe during long-duration spaceflight?"</li>
                <li class="flex gap-2"><span class="text-blue-400">›</span> "How is bone density loss treated on the Moon?"</li>
            </ul>
            <p class="text-lunar-300 leading-relaxed mt-3">Scolta uses Pagefind for full-text indexing, Claude via the Anthropic API for query expansion and AI-generated overviews, and a custom BM25-based scoring layer.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-lunar-100 mb-3">About Tag1 Consulting</h2>
            <p class="text-lunar-300 leading-relaxed">Tag1 Consulting is one of the leading Drupal development and consulting firms in the world. Tag1 built and open-sources Scolta as a demonstration of what AI-augmented content discovery can look like on modern web platforms. For more information about Tag1 and Scolta, visit <a href="https://tag1.com" class="text-blue-400 hover:text-blue-300 transition-colors">tag1.com</a>.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-lunar-100 mb-3">Reuse and Attribution</h2>
            <p class="text-lunar-300 leading-relaxed">If you are evaluating Scolta for your organization and have questions about how this demo was built or how to implement Scolta for your use case, contact Tag1 Consulting.</p>
        </section>

    </div>

</div>
@endsection
