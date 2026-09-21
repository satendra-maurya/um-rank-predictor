<x-layouts.app title="How It Works - UM Rank Predictor">

    <div class="bg-slate-900 text-white py-10 border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight">How Rank Prediction Works</h1>
            <p class="text-slate-400 text-sm mt-2">Data-driven, statistical rank estimation for competitive exam candidates.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 space-y-6">
            <h2 class="text-xl font-bold text-slate-900">Understanding Our Rank Prediction Engine</h2>
            
            <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                UM Rank Predictor relies on real-time crowd-sourced candidate scores and normalized statistical models. When candidates enter their marks for SSC, Railway, or State exams, our backend algorithm analyzes score distribution across all validated candidate submissions.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                <div class="space-y-2">
                    <h3 class="font-bold text-slate-900 text-base">1. Scoring & Negative Marking</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Raw scores are computed strictly adhering to official marking schemes (e.g. 2 marks per question with 0.50 negative marking for SSC CGL Tier 1).
                    </p>
                </div>
                <div class="space-y-2">
                    <h3 class="font-bold text-slate-900 text-base">2. Anti-Abuse Safeguards</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        To protect crowd data integrity, duplicate submissions and suspicious IP bursts are audited. Only trusted submissions enter the rank percentile calculations.
                    </p>
                </div>
                <div class="space-y-2">
                    <h3 class="font-bold text-slate-900 text-base">3. Category & Gender Ranking</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        You receive an expected General Rank, Category Rank (UR, OBC, EWS, SC, ST), and Percentile Score relative to the candidate pool.
                    </p>
                </div>
                <div class="space-y-2">
                    <h3 class="font-bold text-slate-900 text-base">4. Extensible Exam Models</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        As new exams, shifts, and normalization formulas are released, our prediction models adapt without requiring complex frontend changes.
                    </p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 text-center">
                <a href="{{ route('home') }}#predictor-section" class="inline-block py-3.5 px-8 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition-all">
                    Try Rank Predictor Now
                </a>
            </div>
        </div>

    </div>

</x-layouts.app>
