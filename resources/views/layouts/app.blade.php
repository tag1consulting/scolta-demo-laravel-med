<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Medical On The Moon') — Lunar Medical Reference</title>
    <meta name="description" content="@yield('description', 'Comprehensive medical reference for Moon residents, workers, and explorers. Powered by Scolta AI search.')">
    <meta property="og:title" content="@yield('title', 'Medical On The Moon')">
    <meta property="og:description" content="@yield('description', 'Comprehensive lunar medical reference for Moon residents, workers, and explorers.')">
    <meta property="og:image" content="{{ asset('images/og-image.webp') }}">
    <meta property="og:type" content="website">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-lunar-950 text-lunar-200 font-sans antialiased">

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-lunar-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="relative w-9 h-9 flex-shrink-0">
                        {{-- Moon --}}
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-300 to-slate-500 shadow-[0_0_12px_rgba(100,140,180,0.2)] group-hover:shadow-[0_0_20px_rgba(100,140,180,0.35)] transition-shadow"></div>
                        {{-- Earth --}}
                        <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 border-2 border-white shadow-sm"></div>
                    </div>
                    <div>
                        <div class="text-sm font-bold tracking-wider text-lunar-100 leading-tight">MEDICAL ON THE MOON</div>
                        <div class="text-xs text-lunar-400 tracking-widest leading-tight">LUNAR MEDICAL REFERENCE</div>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-1 text-sm">
                    <a href="{{ route('conditions.index') }}" class="nav-link @active('conditions.*')">Conditions</a>
                    <a href="{{ route('medications.index') }}" class="nav-link @active('medications.*')">Medications</a>
                    <a href="{{ route('procedures.index') }}" class="nav-link @active('procedures.*')">Procedures</a>
                    <a href="{{ route('anatomy.index') }}" class="nav-link @active('anatomy.*')">Anatomy</a>
                    <a href="{{ route('articles.index') }}" class="nav-link @active('articles.*')">Research</a>
                </nav>

                {{-- Search button --}}
                <a href="{{ route('search') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-400 text-white text-sm rounded-full transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span class="hidden sm:inline">Search</span>
                </a>
            </div>
        </div>

        {{-- Mobile nav --}}
        <div class="md:hidden border-t border-[#1b3860] px-4 py-2 flex gap-4 text-xs overflow-x-auto">
            <a href="{{ route('conditions.index') }}" class="nav-link-mobile whitespace-nowrap">Conditions</a>
            <a href="{{ route('medications.index') }}" class="nav-link-mobile whitespace-nowrap">Medications</a>
            <a href="{{ route('procedures.index') }}" class="nav-link-mobile whitespace-nowrap">Procedures</a>
            <a href="{{ route('anatomy.index') }}" class="nav-link-mobile whitespace-nowrap">Anatomy</a>
            <a href="{{ route('articles.index') }}" class="nav-link-mobile whitespace-nowrap">Research</a>
        </div>
    </header>

    {{-- Emergency Banner (shown on emergency pages) --}}
    @hasSection('emergency')
    <div class="bg-red-600 border-b border-red-700 text-white px-4 py-2 text-sm text-center">
        <span class="font-bold">⚠ EMERGENCY PROTOCOL</span> — Contact Earth Telemedicine (+1.3s delay) and begin evacuation assessment immediately.
    </div>
    @endif

    {{-- Main content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#0e2040] mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
                <div>
                    <h3 class="text-white font-semibold mb-4 text-xs tracking-widest uppercase">Clinical</h3>
                    <ul class="space-y-2.5 text-slate-300">
                        <li><a href="{{ route('conditions.index') }}" class="hover:text-white transition-colors">Conditions</a></li>
                        <li><a href="{{ route('medications.index') }}" class="hover:text-white transition-colors">Medications</a></li>
                        <li><a href="{{ route('procedures.index') }}" class="hover:text-white transition-colors">Procedures</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 text-xs tracking-widest uppercase">Science</h3>
                    <ul class="space-y-2.5 text-slate-300">
                        <li><a href="{{ route('anatomy.index') }}" class="hover:text-white transition-colors">Anatomy</a></li>
                        <li><a href="{{ route('articles.index') }}" class="hover:text-white transition-colors">Research</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 text-xs tracking-widest uppercase">Emergency</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('conditions.index') }}?emergency=1" class="text-red-300 hover:text-red-200 transition-colors">Emergency Protocols</a></li>
                        <li><a href="{{ route('procedures.index') }}?risk=critical" class="text-red-300 hover:text-red-200 transition-colors">Critical Procedures</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 text-xs tracking-widest uppercase">Search</h3>
                    <ul class="space-y-2.5 text-slate-300">
                        <li><a href="{{ route('search') }}" class="hover:text-white transition-colors">AI-Powered Search</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 pt-8 border-t border-[#1b3860] text-xs text-slate-400 flex flex-col sm:flex-row justify-between gap-4">
                <p>Medical On The Moon — Lunar Medical Reference. All medical information is for educational purposes only. In a medical emergency, contact Earth Telemedicine immediately.</p>
                <p class="whitespace-nowrap">Search powered by <span class="text-slate-200">Scolta</span></p>
            </div>
        </div>
    </footer>

    {{-- Scolta search JS --}}
    @stack('scripts')
</body>
</html>
