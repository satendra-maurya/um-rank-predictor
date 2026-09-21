<x-layouts.app>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 text-white pt-8 pb-12 md:pt-14 md:pb-20 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <!-- Compact Badge -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs sm:text-sm font-semibold mb-4">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                <span>SSC • Railway • State Exams Rank Engine</span>
            </div>

            <!-- Main Hero Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight max-w-4xl mx-auto">
                Know Your Expected Rank <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-emerald-400">Before You Compete</span>
            </h1>

            <!-- Supporting Subtext -->
            <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto font-normal leading-relaxed">
                Estimate your expected rank based on your marks, category and exam data. Prepare better with data-driven prediction.
            </p>

            <!-- Primary Action CTA -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="#predictor-section" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-base shadow-lg shadow-blue-500/25 transition-all text-center">
                    Check Your Rank Now
                </a>
                <a href="{{ route('how-it-works') }}" class="w-full sm:w-auto px-6 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-base transition-all text-center border border-slate-700">
                    How Prediction Works
                </a>
            </div>

        </div>
    </section>

    <!-- Main Predictor Flow Section -->
    <section class="py-6 sm:py-10 bg-slate-50">
        @livewire('rank-predictor')
    </section>

    <!-- Homepage Grid: Latest Notices & Important Links -->
    <section class="py-12 bg-slate-100 border-t border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Col 1: Latest Notices Widget -->
                @livewire('latest-notices', ['limit' => 5])

                <!-- Col 2: Important Links Widget -->
                @livewire('important-links-widget')

            </div>
        </div>
    </section>

    <!-- How It Works Summary Cards -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Simple 3-Step Process</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                How UM Rank Predictor Works
            </h2>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Step 1 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                    <div class="w-12 h-12 rounded-xl bg-blue-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Select Exam</h3>
                    <p class="text-sm text-slate-600">
                        Choose your exam category (SSC, Railway, or State exam), exam cycle year, and tier stage.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Enter Score</h3>
                    <p class="text-sm text-slate-600">
                        Input your category, gender, total questions, correct answers, and incorrect answers.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                    <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Get Instant Rank</h3>
                    <p class="text-sm text-slate-600">
                        Our scoring engine computes your expected overall rank, category rank, and percentile.
                    </p>
                </div>

            </div>

        </div>
    </section>

</x-layouts.app>
