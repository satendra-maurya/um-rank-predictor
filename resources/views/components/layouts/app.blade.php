<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'UM Rank Predictor — Know Your Expected Exam Rank' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Estimate your expected rank based on your marks, category and exam data for SSC, Railway, and State Level Exams. Free rank predictor for government job aspirants.' }}">

    {{-- Fonts: Poppins (display) + Inter (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

{{-- ================================================================
     HEADER
     ================================================================ --}}
<header class="site-header" x-data="{ open: false }">
    <div class="header-inner">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="brand">
            <svg class="brand-mark" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="48" height="48" rx="12" fill="#0A3873"/>
                <path d="M24 12L40 19L24 26L8 19L24 12Z" fill="#F2B705"/>
                <path d="M14 22V29C14 29 17 33 24 33C31 33 34 29 34 29V22" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M40 19V27" stroke="#F2B705" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <div class="brand-text">
                <div class="name"><span class="um">UM</span> RANK PREDICTOR</div>
                <div class="tagline">Your Preparation • Our Prediction • Better Future</div>
            </div>
        </a>

        {{-- Mobile scrim --}}
        <div class="nav-scrim" id="navScrim" @click="open = false"></div>

        {{-- Desktop + mobile nav --}}
        <nav class="main-nav" id="mainNav" :class="{ 'open': open }">
            <a href="{{ route('home') }}"         class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('rank-predictor') }}" class="{{ request()->routeIs('rank-predictor*') ? 'active' : '' }}">Exams</a>
            <a href="{{ route('notices.index') }}" class="{{ request()->routeIs('notices*') ? 'active' : '' }}">Updates</a>
            <a href="{{ route('how-it-works') }}" class="{{ request()->routeIs('how-it-works') ? 'active' : '' }}">How It Works</a>
            <a href="{{ route('about') }}"        class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('contact') }}"      class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
        </nav>

        {{-- Hamburger --}}
        <button class="hamburger" id="hamburgerBtn" @click="open = !open" aria-label="Menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

    </div>
</header>

{{-- ================================================================
     MAIN CONTENT
     ================================================================ --}}
<main>
    {{ $slot }}
</main>

{{-- ================================================================
     FOOTER
     ================================================================ --}}
<footer>
    <div class="container footer-inner">
        <div class="footer-disclaimer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M12 22s8-4 8-10V6l-8-3-8 3v6c0 6 8 10 8 10z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <circle cx="12" cy="16.3" r="0.6" fill="currentColor"/>
            </svg>
            <div>
                <p>यह एक Independent Rank Prediction Platform है। इसका UPSSSC, SSC या Indian Railways से कोई आधिकारिक संबंध/समर्थन नहीं है।</p>
                <p>UM Rank Predictor is an independent rank estimation platform, not affiliated with or endorsed by UPSSSC, SSC or Indian Railways. Results are estimates only and not an official result, merit list, cutoff or selection confirmation.</p>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} UM Rank Predictor. All rights reserved.</span>
            <span class="footer-motto">Dream · Prepare · Achieve</span>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
