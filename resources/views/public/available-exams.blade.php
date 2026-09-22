<x-layouts.app :title="($authority->short_name ?: $authority->name) . ' - Available Exams | UM Rank Predictor'">

    {{-- ============================================================
         HERO BANNER
         ============================================================ --}}
    <div class="page-hero">
        <div class="container">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                <a href="{{ route('home') }}#examsSection" style="color:rgba(255,255,255,0.7);font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    ← Back to Home
                </a>
            </div>
            <h1 class="page-title">{{ $authority->short_name ?: $authority->name }} Exams</h1>
            <p class="page-sub">{{ $authority->name }}</p>
            @if($authority->description)
                <p style="color:rgba(255,255,255,0.8);font-size:14px;max-width:640px;margin-top:8px;">{{ $authority->description }}</p>
            @endif
        </div>
    </div>

    {{-- ============================================================
         AVAILABLE EXAMS LIST
         ============================================================ --}}
    <div class="page-body">
        <div class="container">
            <div class="section-head" style="text-align:left;margin-bottom:32px;">
                <h2>Available Exams</h2>
                <p>Select an exam to check your estimated rank</p>
            </div>

            @if($authority->exams->isEmpty())
                <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:48px 24px;text-align:center;max-width:560px;margin:0 auto;box-shadow:var(--shadow-sm);">
                    <div style="width:56px;height:56px;border-radius:50%;background:var(--blue-light);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;color:var(--blue);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <line x1="9" y1="13" x2="15" y2="13"/>
                        </svg>
                    </div>
                    <h3 style="font-size:18px;color:var(--navy);font-weight:700;margin-bottom:8px;">No exams available for this authority yet.</h3>
                    <p style="font-size:14px;color:var(--muted);margin-bottom:24px;">Please check back later or explore other exam authorities.</p>
                    <a href="{{ route('home') }}#examsSection" class="btn btn-outline">Browse Other Authorities</a>
                </div>
            @else
                <div class="exam-grid">
                    @php
                        $gradients = [
                            'linear-gradient(135deg,#126BD7,#0A3873)',
                            'linear-gradient(135deg,#1C9A5B,#0F5C36)',
                            'linear-gradient(135deg,#D9761F,#8A4A0F)',
                        ];
                    @endphp

                    @foreach($authority->exams as $exam)
                        @php
                            $gradient = $gradients[$loop->index % count($gradients)];
                            $isExamActive = ($exam->status === \App\Enums\ActiveStatus::ACTIVE || (is_numeric($exam->status) && (int)$exam->status === 1));
                        @endphp

                        @if($isExamActive)
                            <div class="exam-card">
                                <div class="exam-icon" style="background:{{ $gradient }};">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <path d="M14 2v6h6"/>
                                        <line x1="8" y1="13" x2="16" y2="13"/>
                                        <line x1="8" y1="17" x2="13" y2="17"/>
                                    </svg>
                                </div>
                                <h3>{{ $exam->short_name ?: $exam->name }}</h3>
                                <p class="desc">{{ $exam->description ?: $exam->name }}</p>
                                <a href="{{ route('rank-predictor', ['category' => $authority->slug, 'exam' => $exam->slug]) }}" class="btn btn-primary btn-block">
                                    Select Exam →
                                </a>
                            </div>
                        @else
                            <div class="exam-card soon" style="opacity:0.65;">
                                <span class="exam-badge" style="background:var(--muted);color:#fff;">Unavailable</span>
                                <div class="exam-icon" style="background:linear-gradient(135deg,#64748B,#334155);">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <line x1="8" y1="13" x2="16" y2="13"/>
                                    </svg>
                                </div>
                                <h3>{{ $exam->short_name ?: $exam->name }}</h3>
                                <p class="desc">{{ $exam->description ?: $exam->name }}</p>
                                <span class="btn btn-outline btn-block" style="opacity:0.6;cursor:not-allowed;pointer-events:none;">
                                    Unavailable
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
