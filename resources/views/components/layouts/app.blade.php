<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'UM Rank Predictor - Know Your Expected Exam Rank' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Estimate your expected rank based on your marks, category and competitive exam data for SSC, Railway, and State Level Exams.' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-full">

    <!-- Header Navigation -->
    <header class="bg-slate-900 text-white sticky top-0 z-50 shadow-md border-b border-slate-800" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                
                <!-- Brand Logo & Tagline -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center font-bold text-lg text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        UM
                    </div>
                    <div>
                        <span class="text-lg md:text-xl font-bold tracking-tight text-white block leading-tight">
                            UM RANK PREDICTOR
                        </span>
                        <span class="text-[10px] md:text-xs text-blue-400 font-medium block tracking-wide">
                            Your Preparation • Our Prediction • Better Future
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        Home
                    </a>
                    <a href="{{ route('rank-predictor') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('rank-predictor*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        Exams
                    </a>
                    <a href="{{ route('notices.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('notices*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        Latest Updates
                    </a>
                    <a href="{{ route('how-it-works') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('how-it-works') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        How It Works
                    </a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        Contact
                    </a>
                </nav>

                <!-- Mobile Hamburger Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false" class="md:hidden border-t border-slate-800 bg-slate-900 px-4 pt-2 pb-4 space-y-1" id="mobile-menu">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('home') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Home
            </a>
            <a href="{{ route('rank-predictor') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('rank-predictor*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Select Exam & Predict Rank
            </a>
            <a href="{{ route('notices.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('notices*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Latest Updates & Notices
            </a>
            <a href="{{ route('how-it-works') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('how-it-works') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                How It Works
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('about') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                About Platform
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('contact') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Contact Us
            </a>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-sm border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Col 1: Brand Info -->
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-white text-sm">
                            UM
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">UM RANK PREDICTOR</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                        Your preparation deserves accurate, data-driven rank insights. We provide trustworthy expected rank estimations for SSC, Railway, and State competitive examinations.
                    </p>
                    <p class="text-xs text-blue-400 font-medium">
                        Your Preparation • Our Prediction • Better Future
                    </p>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wider mb-4">Quick Navigation</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('rank-predictor') }}" class="hover:text-white transition-colors">Exams & Predictor</a></li>
                        <li><a href="{{ route('notices.index') }}" class="hover:text-white transition-colors">Latest Notices</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="hover:text-white transition-colors">How It Works</a></li>
                    </ul>
                </div>

                <!-- Col 3: Legal & Support -->
                <div>
                    <h3 class="text-xs font-semibold text-slate-200 uppercase tracking-wider mb-4">Support & Information</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact Support</a></li>
                    </ul>
                </div>
            </div>

            <!-- Disclaimer & Bottom Bar -->
            <div class="mt-12 pt-6 border-t border-slate-800 text-xs text-slate-500 flex flex-col md:flex-row items-center justify-between gap-4">
                <p>
                    Disclaimer: Rank predictions are estimations generated based on candidate crowd submissions and statistical models. Official ranks are declared solely by respective examination authorities.
                </p>
                <p class="whitespace-nowrap">
                    &copy; {{ date('Y') }} UM Rank Predictor. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
