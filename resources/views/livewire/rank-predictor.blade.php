{{-- ================================================================
     RANK PREDICTOR — Livewire Component View
     Single root element required by Livewire.
     Styled to match the reference HTML design system.
     ================================================================ --}}
<div>

{{-- PAGE HERO HEADER --}}
<div class="page-hero">
    <div class="container">

        {{-- Breadcrumb --}}
        @if($step > 1)
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                @if($authoritySlug && $authorityName)
                    <span>/</span>
                    <a href="{{ route('rank-predictor.authority.available-exams', ['authority' => $authoritySlug]) }}">{{ $authorityName }}</a>
                @elseif($selectedCategorySlug)
                    <span>/</span>
                    <span>{{ strtoupper($selectedCategorySlug) }}</span>
                @endif
                @if($selectedExam)
                    <span>/</span>
                    <span>{{ $selectedExam->short_name ?? $selectedExam->name }}</span>
                @endif
                @if($selectedCycle)
                    <span>/</span>
                    <span>{{ $selectedCycle->year }}</span>
                @endif
                @if($selectedStage)
                    <span>/</span>
                    <span>{{ $selectedStage->name }}</span>
                @endif
            </div>
        @endif

        {{-- Heading --}}
        @if($step === 1)
            <h1>UM Rank Predictor</h1>
            <p class="sub">Select your exam board to begin rank prediction</p>
        @elseif($step === 2)
            <h1>{{ strtoupper($selectedCategorySlug ?? 'Exam') }} Rank Predictor</h1>
            <p class="sub">Select Examination</p>
        @elseif($step === 3)
            <h1>Select Exam Cycle</h1>
            <p class="sub">{{ $selectedExam->name ?? '' }}</p>
        @elseif($step === 4)
            <h1>Select Exam Stage</h1>
            <p class="sub">{{ $selectedExam?->short_name ?? '' }} {{ $selectedCycle?->year ?? '' }}</p>
        @elseif($step === 5)
            <h1>{{ $selectedExam?->name ?? 'Rank Predictor' }}</h1>
            <p class="sub">अपनी details और marks भरें और अपनी Estimated Rank जानें।</p>
        @elseif($step === 6)
            <h1>Your Estimated Rank</h1>
            <p class="sub">{{ $selectedExam?->name ?? '' }} {{ $selectedCycle?->year ?? '' }}</p>
        @endif

    </div>
</div>

{{-- PAGE BODY --}}
<div class="page-body">
    <div class="container">

        {{-- Loading Indicator --}}
        <div wire:loading wire:target="selectCategory, selectState, selectAuthority, selectExam, selectCycle, selectStage, submitPrediction"
             class="livewire-loader" style="margin-bottom:22px;">
            <svg style="width:16px;height:16px;flex-shrink:0;" class="spin-icon" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:0.25;"></circle>
                <path fill="currentColor" style="opacity:0.75;" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Loading details...</span>
        </div>

        {{-- Stepper breadcrumb (shown above step panel) --}}
        @if($step > 1)
            <div class="stepper-nav">
                <div class="stepper-nav-crumbs">
                    <a href="{{ route('home') }}">Home</a>
                    @if($authoritySlug && $authorityName)
                        <span class="sep">/</span>
                        <a href="{{ route('rank-predictor.authority.available-exams', ['authority' => $authoritySlug]) }}">{{ $authorityName }}</a>
                    @elseif($selectedCategorySlug)
                        <span class="sep">/</span>
                        <span>{{ strtoupper($selectedCategorySlug) }}</span>
                    @endif
                    @if($selectedExam)
                        <span class="sep">/</span>
                        <span>{{ $selectedExam->short_name ?? $selectedExam->name }}</span>
                    @endif
                    @if($selectedCycle)
                        <span class="sep">/</span>
                        <span>{{ $selectedCycle->year }}</span>
                    @endif
                    @if($selectedStage)
                        <span class="sep">/</span>
                        <span class="active">{{ $selectedStage->name }}</span>
                    @endif
                </div>
                <button wire:click="resetPredictor" type="button">Start Over</button>
            </div>
        @endif

        {{-- ============================================================
             STEP 1 — Category selection
             ============================================================ --}}
        @if($step === 1)
            <div style="text-align:center;margin-bottom:32px;">
                <h2 style="font-size:24px;color:var(--navy);font-family:var(--font-display);">Select Your Exam Category</h2>
                <p style="font-size:14px;color:var(--muted);margin-top:8px;">Choose your target competitive exam authority to begin rank prediction.</p>
            </div>

            <div class="cat-grid">
                <button wire:click="selectCategory('ssc')" type="button" class="cat-card">
                    <div class="cat-icon" style="background:linear-gradient(135deg,#126BD7,#0A3873);">SSC</div>
                    <h3>Staff Selection Commission</h3>
                    <p>CGL, CHSL, MTS, GD Constable &amp; Central Group B/C exams.</p>
                    <div class="go">
                        Explore SSC Exams
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                <button wire:click="selectCategory('railway')" type="button" class="cat-card">
                    <div class="cat-icon" style="background:linear-gradient(135deg,#1C9A5B,#0F5C36);">RRB</div>
                    <h3>Railway Exams</h3>
                    <p>NTPC, Group D, ALP, Technician &amp; Railway Recruitment Boards.</p>
                    <div class="go">
                        Explore Railway Exams
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>

                <button wire:click="selectCategory('state-exams')" type="button" class="cat-card">
                    <div class="cat-icon" style="background:linear-gradient(135deg,#7C3AED,#4C1D95);">STATE</div>
                    <h3>State Level Exams</h3>
                    <p>UPSSSC (PET, VDO), BPSC, UPPSC &amp; State Competitive Boards.</p>
                    <div class="go">
                        Select State &amp; Authority
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>
            </div>
        @endif

        {{-- ============================================================
             STEP 2 — Exam browser (state / authority / exam)
             ============================================================ --}}
        @if($step === 2)
            <div class="step-box">

                @if($selectedCategorySlug === 'state-exams' && !$selectedStateId)
                    {{-- Pick state --}}
                    <div class="step-box-head">
                        <h2>Select State</h2>
                        <p>Choose your state to view available examination authorities.</p>
                    </div>
                    <div class="sel-grid">
                        @foreach($states as $st)
                            <button wire:click="selectState({{ $st->id }})" type="button" class="sel-card">
                                <div>
                                    <h4>{{ $st->name }}</h4>
                                    <p>{{ $st->short_name }}</p>
                                </div>
                                <svg class="sel-card-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endforeach
                    </div>

                @elseif($selectedCategorySlug === 'state-exams' && $selectedStateId && !$selectedAuthorityId)
                    {{-- Pick authority --}}
                    <div class="step-box-head" style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                        <div>
                            <h2>Select Exam Authority</h2>
                            <p>Available examination boards for your selected state.</p>
                        </div>
                        <button wire:click="$set('selectedStateId', null)" type="button"
                                style="font-size:12.5px;font-weight:600;color:var(--blue);background:none;border:none;cursor:pointer;white-space:nowrap;padding:0;margin-top:4px;">
                            Change State
                        </button>
                    </div>
                    @if($authorities->isEmpty())
                        <p style="font-size:14px;color:var(--muted);padding:16px 0;">No active examination authorities found for this state yet.</p>
                    @else
                        <div class="exam-list">
                            @foreach($authorities as $auth)
                                <button wire:click="selectAuthority({{ $auth->id }})" type="button" class="exam-panel" style="text-align:left;width:100%;">
                                    <div class="top">
                                        <div class="ic">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                                <path d="M9 12l2 2 4-4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="tag">{{ $auth->level ?? 'STATE' }}</span>
                                            <h3>{{ $auth->short_name }}</h3>
                                        </div>
                                    </div>
                                    <p class="desc">{{ $auth->name }}</p>
                                    <span class="btn btn-outline btn-sm" style="align-self:flex-start;">Select →</span>
                                </button>
                            @endforeach
                        </div>
                    @endif

                @else
                    {{-- SSC / Railway / authority already selected → pick exam --}}
                    <div class="step-box-head" style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                        <div>
                            <h2>Available {{ strtoupper($selectedCategorySlug ?? '') }} Exams</h2>
                            <p>Select the specific exam you appeared in.</p>
                        </div>
                        <button wire:click="resetPredictor" type="button"
                                style="font-size:12.5px;font-weight:600;color:var(--blue);background:none;border:none;cursor:pointer;white-space:nowrap;padding:0;margin-top:4px;">
                            Change Category
                        </button>
                    </div>
                    @if($exams->isEmpty())
                        <div style="text-align:center;padding:40px 0;">
                            <p style="font-size:14px;color:var(--muted);">No active exams configured in the database for this selection yet.</p>
                        </div>
                    @else
                        <div class="exam-list">
                            @foreach($exams as $ex)
                                <button wire:click="selectExam({{ $ex->id }})" type="button" class="exam-panel" style="text-align:left;width:100%;">
                                    <div class="top">
                                        <div class="ic">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 11l3 3L22 4"/>
                                                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="tag">{{ $ex->examAuthority?->short_name ?? 'EXAM' }}</span>
                                            <h3>{{ $ex->short_name ?? $ex->name }}</h3>
                                        </div>
                                    </div>
                                    <p class="desc">{{ $ex->description ?? $ex->name }}</p>
                                    <span class="btn btn-primary btn-sm" style="align-self:flex-start;">Select Exam →</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endif

            </div>
        @endif

        {{-- ============================================================
             STEP 3 — Select cycle / year
             ============================================================ --}}
        @if($step === 3)
            <div class="step-box">
                <div class="step-box-head">
                    <h2>Select Exam Cycle / Year</h2>
                    <p>Select the specific recruitment year for {{ $selectedExam?->name ?? '' }}.</p>
                </div>
                @if($cycles->isEmpty())
                    <p style="font-size:14px;color:var(--muted);padding:16px 0;">No active recruitment cycles found for this exam.</p>
                @else
                    <div class="sel-grid">
                        @foreach($cycles as $cyc)
                            <button wire:click="selectCycle({{ $cyc->id }})" type="button" class="sel-card">
                                <div>
                                    <h4>{{ $cyc->title ?? (($selectedExam?->short_name ?? '') . ' ' . $cyc->year) }}</h4>
                                    <p>Exam Year: {{ $cyc->year }}</p>
                                </div>
                                <svg class="sel-card-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- ============================================================
             STEP 4 — Select stage / tier
             ============================================================ --}}
        @if($step === 4)
            <div class="step-box">
                <div class="step-box-head">
                    <h2>Select Exam Stage</h2>
                    <p>Choose the stage/tier for {{ $selectedExam?->short_name ?? '' }} {{ $selectedCycle?->year ?? '' }}.</p>
                </div>
                @if($stages->isEmpty())
                    <p style="font-size:14px;color:var(--muted);padding:16px 0;">No active exam stages found.</p>
                @else
                    <div class="sel-grid">
                        @foreach($stages as $stg)
                            <button wire:click="selectStage({{ $stg->id }})" type="button" class="sel-card">
                                <div>
                                    <h4>{{ $stg->name }}</h4>
                                    @if($stg->description)
                                        <p>{{ $stg->description }}</p>
                                    @endif
                                </div>
                                <svg class="sel-card-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- ============================================================
             STEP 5 — Prediction form
             ============================================================ --}}
        @if($step === 5)
            <div class="form-card">
                <div style="border-bottom:1px solid var(--border);padding-bottom:18px;margin-bottom:24px;">
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--blue);">Predictor Form</span>
                    <h2 style="font-size:22px;color:var(--navy);font-family:var(--font-display);margin-top:4px;">
                        {{ $selectedExam?->name ?? 'Exam' }} ({{ $selectedCycle?->year ?? '' }})
                    </h2>
                    <p style="font-size:13.5px;color:var(--muted);margin-top:4px;">
                        Stage: <strong>{{ $selectedStage?->name ?? 'Default Stage' }}</strong>
                    </p>
                </div>

                <form wire:submit.prevent="submitPrediction">
                    <div class="form-grid">

                        <div class="field">
                            <label for="pred_name">Candidate Name <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="pred_name" wire:model="name" placeholder="e.g. Rahul Kumar">
                            @error('name') <span class="error" style="display:block;">{{ $message }}</span> @enderror
                        </div>

                        <div class="field">
                            <label for="pred_roll">Roll Number <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="pred_roll" wire:model="roll_number" placeholder="e.g. 2201004589">
                            @error('roll_number') <span class="error" style="display:block;">{{ $message }}</span> @enderror
                        </div>

                        <div class="field">
                            <label for="pred_dob">Date of Birth <span style="color:var(--danger);">*</span></label>
                            <input type="date" id="pred_dob" wire:model="dob">
                            @error('dob') <span class="error" style="display:block;">{{ $message }}</span> @enderror
                        </div>

                        <div class="field">
                            <label for="pred_category">Category <span style="color:var(--danger);">*</span></label>
                            <select id="pred_category" wire:model="category_id">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="error" style="display:block;">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="field">
                            <label for="pred_score">Marks Obtained <span style="color:var(--danger);">*</span></label>
                            <input type="number" step="0.01" id="pred_score" wire:model="raw_score" placeholder="e.g. 142.50">
                            @error('raw_score') <span class="error" style="display:block;">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label for="pred_gender">Gender <span style="color:var(--danger);">*</span></label>
                            <select id="pred_gender" wire:model="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('gender') <span class="error" style="display:block;">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <x-consent purpose="rank_prediction" :required="true" wireModel="consent" />

                    <div class="form-actions">
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-gold" style="min-width:260px;">
                            <span wire:loading.remove wire:target="submitPrediction">🔎 Calculate My Rank</span>
                            <span wire:loading wire:target="submitPrediction">Analyzing…</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- ============================================================
             STEP 6 — Result
             ============================================================ --}}
        @if($step === 6 && $predictionResult)
            <div class="result-wrap">
                <div class="result-card">
                    <svg class="trophy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M8 4h8v4a4 4 0 01-8 0V4z"/>
                        <path d="M8 5H4v2a4 4 0 004 4"/>
                        <path d="M16 5h4v2a4 4 0 01-4 4"/>
                        <path d="M12 12v4"/>
                        <path d="M9 20h6"/>
                        <path d="M10 16h4l1 4H9l1-4z"/>
                    </svg>

                    <div class="congrats">Congratulations!</div>
                    <div class="rlabel">Your Estimated Rank</div>
                    <div class="rnum">#{{ number_format($predictionResult->predicted_rank_overall ?? 1) }}</div>
                    <div class="rsub">out of {{ number_format($predictionResult->metadata['total_crowd_samples'] ?? 0) }} candidates considered</div>

                    <div class="result-meta-grid">
                        <div class="result-meta">
                            <div class="k">Candidate Name</div>
                            <div class="v">{{ $predictionResult->candidateSubmission?->candidate_name ?? $predictionResult->metadata['candidate_name'] ?? $name }}</div>
                        </div>
                        <div class="result-meta">
                            <div class="k">Roll Number</div>
                            <div class="v">{{ $predictionResult->candidateSubmission?->candidate_identifier ?? $roll_number }}</div>
                        </div>
                        <div class="result-meta">
                            <div class="k">Marks Obtained</div>
                            <div class="v">{{ number_format($predictionResult->candidateSubmission?->raw_score ?? 0, 2) }}</div>
                        </div>
                        <div class="result-meta">
                            <div class="k">Date of Birth</div>
                            <div class="v">{{ $predictionResult->candidateSubmission?->dob?->format('d M Y') ?? '—' }}</div>
                        </div>
                        <div class="result-meta">
                            <div class="k">Category</div>
                            <div class="v">{{ $predictionResult->candidateSubmission?->category?->code ?? '—' }}</div>
                        </div>
                        <div class="result-meta">
                            <div class="k">Gender</div>
                            <div class="v">{{ $predictionResult->candidateSubmission?->gender ?? $gender }}</div>
                        </div>
                        <div class="result-meta">
                            <div class="k">Percentile</div>
                            <div class="v">{{ number_format($predictionResult->percentile ?? 0, 2) }}%</div>
                        </div>
                        <div class="result-meta">
                            <div class="k">Exam</div>
                            <div class="v">{{ $selectedExam?->short_name ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="result-stats">
                    <div class="stat-box">
                        <div class="num">#{{ number_format($predictionResult->predicted_rank_category ?? 1) }}</div>
                        <div class="lbl">Category Rank</div>
                    </div>
                    <div class="stat-box">
                        <div class="num">{{ number_format($predictionResult->metadata['total_crowd_samples'] ?? 0) }}</div>
                        <div class="lbl">Total Candidates Considered</div>
                    </div>
                </div>

                <div class="disclaimer-box">
                    <p>यह rank उपलब्ध candidate data के आधार पर अनुमानित है। यह official rank/merit list नहीं है।</p>
                    <p>UM Rank Predictor is an independent rank estimation platform. Results are estimates based on the candidate data available in the system and should not be treated as an official result, merit list, cutoff or selection confirmation.</p>
                </div>

                <div class="result-actions">
                    <button wire:click="backToStep(5)" type="button" class="btn btn-outline">Check Another Entry</button>
                    <button wire:click="resetPredictor" type="button" class="btn btn-primary">Back to Home</button>
                </div>
            </div>
        @endif

    </div>
</div>

</div>{{-- end single Livewire root --}}
