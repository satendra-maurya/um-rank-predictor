<div class="w-full max-w-4xl mx-auto px-4 py-6 md:py-10" id="predictor-section">

    <!-- Stepper Header Breadcrumb Navigation -->
    @if($step > 1)
        <div class="mb-6 flex items-center justify-between bg-white rounded-xl p-3 sm:p-4 border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-600 overflow-x-auto py-1">
                <button wire:click="resetPredictor" type="button" class="font-medium text-blue-600 hover:underline flex items-center gap-1 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Home
                </button>
                <span class="text-slate-300">/</span>
                @if($selectedCategorySlug)
                    <span class="font-semibold text-slate-800 uppercase">{{ strtoupper($selectedCategorySlug) }}</span>
                @endif
                @if($selectedExam)
                    <span class="text-slate-300">/</span>
                    <span class="font-semibold text-slate-800">{{ $selectedExam->short_name ?? $selectedExam->name }}</span>
                @endif
                @if($selectedCycle)
                    <span class="text-slate-300">/</span>
                    <span class="font-medium text-slate-700">{{ $selectedCycle->year }}</span>
                @endif
                @if($selectedStage)
                    <span class="text-slate-300">/</span>
                    <span class="font-medium text-slate-700">{{ $selectedStage->name }}</span>
                @endif
            </div>

            <button wire:click="resetPredictor" type="button" class="text-xs text-slate-500 hover:text-slate-800 underline shrink-0 ml-2">
                Start Over
            </button>
        </div>
    @endif

    <!-- Loading Overlay Spinner -->
    <div wire:loading wire:target="selectCategory, selectState, selectAuthority, selectExam, selectCycle, selectStage, submitPrediction" class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl text-xs sm:text-sm font-medium flex items-center justify-center gap-2">
        <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Loading details...</span>
    </div>

    <!-- STEP 1: EXAM CATEGORY SELECTION -->
    @if($step === 1)
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Select Your Exam Category
            </h2>
            <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-xl mx-auto">
                Choose your target competitive exam authority to begin rank prediction.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            
            <!-- Category 1: SSC -->
            <button wire:click="selectCategory('ssc')" type="button" class="w-full text-left bg-white hover:bg-blue-50/50 border-2 border-slate-200 hover:border-blue-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all group focus:outline-none focus:ring-4 focus:ring-blue-500/20">
                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition-transform">
                    SSC
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                    Staff Selection Commission
                </h3>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    CGL, CHSL, MTS, GD Constable & Central Group B/C exams.
                </p>
                <div class="mt-4 flex items-center text-xs font-semibold text-blue-600 group-hover:translate-x-1 transition-transform">
                    <span>Explore SSC Exams</span>
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </button>

            <!-- Category 2: Railway -->
            <button wire:click="selectCategory('railway')" type="button" class="w-full text-left bg-white hover:bg-emerald-50/50 border-2 border-slate-200 hover:border-emerald-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all group focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition-transform">
                    RRB
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">
                    Railway Exams
                </h3>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    NTPC, Group D, ALP, Technician & Railway Recruitment Boards.
                </p>
                <div class="mt-4 flex items-center text-xs font-semibold text-emerald-600 group-hover:translate-x-1 transition-transform">
                    <span>Explore Railway Exams</span>
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </button>

            <!-- Category 3: State Exams -->
            <button wire:click="selectCategory('state-exams')" type="button" class="w-full text-left bg-white hover:bg-indigo-50/50 border-2 border-slate-200 hover:border-indigo-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all group focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition-transform">
                    STATE
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                    State Level Exams
                </h3>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    UPSSSC (PET, VDO), BPSC, UPPSC & State Competitive Boards.
                </p>
                <div class="mt-4 flex items-center text-xs font-semibold text-indigo-600 group-hover:translate-x-1 transition-transform">
                    <span>Select State & Authority</span>
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </button>

        </div>
    @endif

    <!-- STEP 2: DYNAMIC EXAM BROWSER -->
    @if($step === 2)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-8">

            <!-- STATE EXAMS FLOW: Select State First -->
            @if($selectedCategorySlug === 'state-exams' && !$selectedStateId)
                <h3 class="text-xl font-bold text-slate-900 mb-2">Select State</h3>
                <p class="text-sm text-slate-500 mb-6">Choose your state to view available state examination authorities.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($states as $st)
                        <button wire:click="selectState({{ $st->id }})" type="button" class="w-full text-left p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/40 transition-all font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ $st->name }} ({{ $st->short_name }})</span>
                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endforeach
                </div>

            <!-- STATE EXAMS FLOW: Select Authority next -->
            @elseif($selectedCategorySlug === 'state-exams' && $selectedStateId && !$selectedAuthorityId)
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Select Exam Authority</h3>
                    <button wire:click="$set('selectedStateId', null)" type="button" class="text-xs text-blue-600 font-medium hover:underline">
                        Change State
                    </button>
                </div>

                @if($authorities->isEmpty())
                    <p class="text-sm text-slate-500 py-4">No active examination authorities found for this state yet.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($authorities as $auth)
                            <button wire:click="selectAuthority({{ $auth->id }})" type="button" class="w-full text-left p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/40 transition-all">
                                <h4 class="font-bold text-slate-900">{{ $auth->short_name }}</h4>
                                <p class="text-xs text-slate-500 mt-1">{{ $auth->name }}</p>
                            </button>
                        @endforeach
                    </div>
                @endif

            <!-- SELECT EXAM FROM AUTHORITIES -->
            @else
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-900">
                        Available {{ strtoupper($selectedCategorySlug) }} Exams
                    </h3>
                    <button wire:click="resetPredictor" type="button" class="text-xs text-blue-600 font-medium hover:underline">
                        Change Category
                    </button>
                </div>

                @if($exams->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-500">No active exams configured in database for this selection yet.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($exams as $ex)
                            <button wire:click="selectExam({{ $ex->id }})" type="button" class="w-full text-left p-5 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 shadow-xs transition-all group">
                                <div class="flex items-center justify-between">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        {{ $ex->examAuthority->short_name ?? 'EXAM' }}
                                    </span>
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                                <h4 class="text-base font-bold text-slate-900 mt-2 group-hover:text-blue-600 transition-colors">
                                    {{ $ex->name }}
                                </h4>
                                @if($ex->description)
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $ex->description }}</p>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            @endif

        </div>
    @endif

    <!-- STEP 3: SELECT EXAM CYCLE -->
    @if($step === 3)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-8">
            <h3 class="text-xl font-bold text-slate-900 mb-2">Select Exam Cycle / Year</h3>
            <p class="text-sm text-slate-500 mb-6">Select the specific year recruitment cycle for {{ $selectedExam->name }}.</p>

            @if($cycles->isEmpty())
                <p class="text-sm text-slate-500 py-4">No active recruitment cycles found for this exam.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($cycles as $cyc)
                        <button wire:click="selectCycle({{ $cyc->id }})" type="button" class="w-full text-left p-5 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/40 transition-all">
                            <h4 class="text-lg font-bold text-slate-900">{{ $cyc->title ?? ($selectedExam->short_name . ' ' . $cyc->year) }}</h4>
                            <p class="text-xs text-slate-500 mt-1">Exam Year: <span class="font-semibold text-slate-700">{{ $cyc->year }}</span></p>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- STEP 4: SELECT EXAM STAGE -->
    @if($step === 4)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-8">
            <h3 class="text-xl font-bold text-slate-900 mb-2">Select Exam Stage</h3>
            <p class="text-sm text-slate-500 mb-6">Choose the stage/tier for {{ $selectedExam->short_name }} {{ $selectedCycle->year }}.</p>

            @if($stages->isEmpty())
                <p class="text-sm text-slate-500 py-4">No active exam stages found.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($stages as $stg)
                        <button wire:click="selectStage({{ $stg->id }})" type="button" class="w-full text-left p-5 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/40 transition-all">
                            <h4 class="text-base font-bold text-slate-900">{{ $stg->name }}</h4>
                            @if($stg->description)
                                <p class="text-xs text-slate-500 mt-1">{{ $stg->description }}</p>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- STEP 5: CONFIGURABLE PREDICTION FORM -->
    @if($step === 5)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-md p-5 sm:p-8">
            
            <div class="border-b border-slate-100 pb-4 mb-6">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Predictor Form</span>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">
                    {{ $selectedExam->name ?? 'Exam' }} ({{ $selectedCycle->year ?? '' }})
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Stage: <span class="font-medium text-slate-700">{{ $selectedStage->name ?? 'Default Stage' }}</span>
                </p>
            </div>

            <form wire:submit.prevent="submitPrediction" class="space-y-6">

                <!-- Field 1: Candidate Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                        Candidate Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" wire:model="name" placeholder="Enter your full name" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition-all" required>
                    @error('name') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Field 2: Reservation Category -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">
                        Reservation Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" wire:model="category_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition-all" required>
                        <option value="">-- Select Your Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Field 3: Gender -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Gender <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['Male', 'Female', 'Other'] as $g)
                            <label class="flex items-center justify-center p-3 rounded-xl border cursor-pointer font-medium text-sm transition-all {{ $gender === $g ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                <input type="radio" wire:model.live="gender" value="{{ $g }}" class="sr-only">
                                <span>{{ $g }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('gender') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Optional Shift Selector -->
                @if($shifts->isNotEmpty())
                    <div>
                        <label for="shift_id" class="block text-sm font-semibold text-slate-700 mb-1">
                            Exam Shift (Optional)
                        </label>
                        <select id="shift_id" wire:model="shift_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition-all">
                            <option value="">-- Select Shift if applicable --</option>
                            @foreach($shifts as $sh)
                                <option value="{{ $sh->id }}">{{ $sh->name }} ({{ $sh->shift_date->format('d M Y') }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Numerical Marks Breakdown Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    
                    <!-- Total Questions -->
                    <div>
                        <label for="total_questions" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Total Questions <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="total_questions" wire:model="total_questions" min="1" max="1000" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-900 bg-white" required>
                        @error('total_questions') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Correct Answers -->
                    <div>
                        <label for="correct_answers" class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">
                            Correct Answers <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="correct_answers" wire:model="correct_answers" min="0" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm font-semibold text-emerald-900 bg-white" required>
                        @error('correct_answers') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Incorrect Answers -->
                    <div>
                        <label for="incorrect_answers" class="block text-xs font-bold text-red-700 uppercase tracking-wider mb-1">
                            Incorrect Answers <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="incorrect_answers" wire:model="incorrect_answers" min="0" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-red-500 text-sm font-semibold text-red-900 bg-white" required>
                        @error('incorrect_answers') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                </div>

                <!-- Submit Button -->
                <button type="submit" wire:loading.attr="disabled" class="w-full py-4 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-base shadow-lg shadow-blue-500/25 transition-all flex items-center justify-center gap-2 focus:outline-none focus:ring-4 focus:ring-blue-500/30">
                    <span wire:loading.remove wire:target="submitPrediction">Check My Rank</span>
                    <span wire:loading wire:target="submitPrediction" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Analyzing your response...
                    </span>
                </button>

            </form>
        </div>
    @endif

    <!-- STEP 6: PREDICTION RESULT DISPLAY -->
    @if($step === 6 && $predictionResult)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xl overflow-hidden p-6 sm:p-10">
            
            <div class="text-center pb-6 border-b border-slate-100">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2 uppercase tracking-wide">
                    Verified Prediction
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Your Rank Prediction
                </h2>
                <p class="text-sm text-slate-600 mt-1">
                    Candidate: <strong class="text-slate-900">{{ $predictionResult->metadata['candidate_name'] ?? $name }}</strong>
                </p>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $selectedExam->name ?? '' }} ({{ $selectedCycle->year ?? '' }}) • {{ $selectedStage->name ?? '' }}
                </p>
            </div>

            <!-- PROMINENT RANK CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-8">
                
                <!-- GENERAL RANK CARD -->
                <div class="bg-gradient-to-br from-blue-900 to-slate-900 text-white p-6 rounded-2xl shadow-md text-center flex flex-col justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-300">General Rank</span>
                    <div class="my-4">
                        <span class="text-4xl sm:text-5xl font-extrabold tracking-tight">
                            #{{ number_format($predictionResult->predicted_rank_overall ?? 1) }}
                        </span>
                    </div>
                    <span class="text-[11px] text-blue-200/80">Overall Crowd Rank</span>
                </div>

                <!-- CATEGORY RANK CARD -->
                <div class="bg-gradient-to-br from-indigo-800 to-blue-900 text-white p-6 rounded-2xl shadow-md text-center flex flex-col justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Category Rank</span>
                    <div class="my-4">
                        <span class="text-4xl sm:text-5xl font-extrabold tracking-tight">
                            #{{ number_format($predictionResult->predicted_rank_category ?? 1) }}
                        </span>
                    </div>
                    <span class="text-[11px] text-indigo-200/80">Category Level</span>
                </div>

                <!-- GENDER RANK & PERCENTILE CARD -->
                <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white p-6 rounded-2xl shadow-md text-center flex flex-col justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Percentile Score</span>
                    <div class="my-4">
                        <span class="text-4xl sm:text-5xl font-extrabold tracking-tight text-emerald-400">
                            {{ number_format($predictionResult->percentile ?? 99.0, 2) }}%
                        </span>
                    </div>
                    <span class="text-[11px] text-slate-300">
                        Gender Rank: #{{ number_format($predictionResult->metadata['predicted_rank_gender'] ?? 1) }}
                    </span>
                </div>

            </div>

            <!-- SCORE BREAKDOWN SUMMARY -->
            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div>
                    <span class="text-slate-500 block">Raw Score</span>
                    <strong class="text-base text-slate-900 font-bold">{{ number_format($predictionResult->candidateSubmission->raw_score, 2) }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Category</span>
                    <strong class="text-slate-900 font-semibold">{{ $predictionResult->candidateSubmission->category->name ?? 'General' }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Attempted / Correct</span>
                    <strong class="text-slate-900 font-semibold">{{ $predictionResult->candidateSubmission->total_attempted }} / {{ $predictionResult->candidateSubmission->correct_answers }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Crowd Pool Size</span>
                    <strong class="text-slate-900 font-semibold">{{ number_format($predictionResult->metadata['total_crowd_samples'] ?? 0) }} candidates</strong>
                </div>
            </div>

            <!-- RESET CTA -->
            <div class="text-center">
                <button wire:click="resetPredictor" type="button" class="py-3 px-8 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-md transition-all">
                    Predict Another Exam Rank
                </button>
            </div>

        </div>
    @endif

</div>
