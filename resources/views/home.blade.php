<x-layouts.app>

    {{-- ============================================================
         HERO
         ============================================================ --}}
    <section class="hero">
        <div class="container hero-grid">

            {{-- Left: text --}}
            <div>
                <div class="eyebrow-pill">
                    <span class="dot"></span>
                    Smart Analysis • Accurate Prediction
                </div>
                <h1>Know Your Expected <span class="hl">Rank</span> Before You Compete</h1>
                <p class="hero-sub">Estimate where you stand — before results come out.</p>
                <p class="hero-hi">अपने Marks, Category और Date of Birth के आधार पर अपनी Estimated Rank जानें।</p>
                <div class="hero-cta">
                    <a href="{{ route('rank-predictor') }}" class="btn btn-primary">Check Your Rank</a>
                    <a href="{{ route('how-it-works') }}" class="btn btn-outline">How It Works</a>
                </div>
            </div>

            {{-- Right: illustration --}}
            <div class="hero-art">
                <svg viewBox="0 0 420 360" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="210" cy="190" r="160" fill="var(--blue-light)"/>
                    <rect x="120" y="230" width="180" height="18" rx="4" fill="#0A3873"/>
                    <rect x="130" y="212" width="160" height="18" rx="4" fill="#F2B705"/>
                    <rect x="140" y="194" width="140" height="18" rx="4" fill="#FFFFFF" stroke="#DCE6F2" stroke-width="1"/>
                    <g transform="translate(150,110)">
                        <path d="M60 0L120 26L60 52L0 26L60 0Z" fill="#0A3873"/>
                        <path d="M28 34V56C28 56 40 70 60 70C80 70 92 56 92 56V34" stroke="#0A3873" stroke-width="4" fill="none" stroke-linecap="round"/>
                        <circle cx="120" cy="26" r="3" fill="#F2B705"/>
                        <line x1="120" y1="26" x2="120" y2="50" stroke="#F2B705" stroke-width="2"/>
                    </g>
                    <g transform="translate(255,150)">
                        <rect x="0"  y="60" width="18" height="50" rx="3" fill="#126BD7"/>
                        <rect x="24" y="40" width="18" height="70" rx="3" fill="#0A3873"/>
                        <rect x="48" y="15" width="18" height="95" rx="3" fill="#F2B705"/>
                        <path d="M0 55 L24 34 L48 10 L66 -8" stroke="#D99A00" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path d="M56 -8 L66 -8 L66 2" stroke="#D99A00" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                </svg>
            </div>

        </div>
    </section>

    {{-- ============================================================
         FEATURE STRIP
         ============================================================ --}}
    <section class="strip">
        <div class="container strip-grid">

            <div class="strip-item">
                <div class="strip-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="0.5" fill="currentColor"/>
                    </svg>
                </div>
                <h4>Accurate Rank Analysis</h4>
                <p>Based on marks &amp; database</p>
            </div>

            <div class="strip-item">
                <div class="strip-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 17l5-5 4 4 8-8"/><path d="M17 8h4v4"/>
                    </svg>
                </div>
                <h4>Category Wise Analysis</h4>
                <p>GEN / OBC / EWS / SC / ST</p>
            </div>

            <div class="strip-item">
                <div class="strip-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8"  y1="2" x2="8"  y2="6"/>
                        <line x1="3"  y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <h4>DOB Tie Break</h4>
                <p>Fair handling of equal marks</p>
            </div>

            <div class="strip-item">
                <div class="strip-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/>
                    </svg>
                </div>
                <h4>Secure Data</h4>
                <p>Admin-only access</p>
            </div>

            <div class="strip-item">
                <div class="strip-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2L3 14h7l-1 8 11-14h-7l0-6z"/>
                    </svg>
                </div>
                <h4>Easy to Use</h4>
                <p>Result in a few clicks</p>
            </div>

        </div>
    </section>

    {{-- ============================================================
         LATEST NOTICES & IMPORTANT LINKS (from Livewire widgets)
         ============================================================ --}}
    <section class="section" style="padding-top:0;background:var(--bg);">
        <div class="container">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
                @livewire('latest-notices', ['limit' => 5])
                @livewire('important-links-widget')
            </div>
        </div>
    </section>

    {{-- ============================================================
         SELECT YOUR EXAM
         ============================================================ --}}
    <section class="section" id="examsSection">
        <div class="container">
            <div class="section-head">
                <h2>Select Your Exam</h2>
                <p>Choose an exam board to check your estimated rank</p>
            </div>
            <div class="exam-grid">
                @php
                    $gradients = [
                        'linear-gradient(135deg,#126BD7,#0A3873)',
                        'linear-gradient(135deg,#1C9A5B,#0F5C36)',
                        'linear-gradient(135deg,#D9761F,#8A4A0F)',
                    ];
                @endphp

                @foreach($authorities as $authority)
                    @php
                        $gradient = $gradients[$loop->index % count($gradients)];
                        $isActive = ($authority->status === \App\Enums\ActiveStatus::ACTIVE || (is_numeric($authority->status) && (int)$authority->status === 1));
                        $isComingSoon = ($authority->status === \App\Enums\ActiveStatus::COMING_SOON || (is_numeric($authority->status) && (int)$authority->status === 2));
                    @endphp

                    @if($isActive)
                        <div class="exam-card">
                            <div class="exam-icon" style="background:{{ $gradient }};">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                    <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                                </svg>
                            </div>
                            <h3>{{ $authority->short_name ?: $authority->name }}</h3>
                            <p class="desc">{{ $authority->name }}</p>
                            <a href="{{ route('rank-predictor.authority.available-exams', ['authority' => $authority->slug]) }}" class="btn btn-primary btn-block">
                                Explore {{ $authority->short_name ?: $authority->name }} →
                            </a>
                        </div>
                    @elseif($isComingSoon)
                        <div class="exam-card soon">
                            <span class="exam-badge">Coming Soon</span>
                            <div class="exam-icon" style="background:{{ $gradient }};">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <line x1="8" y1="13" x2="16" y2="13"/>
                                    <line x1="8" y1="17" x2="13" y2="17"/>
                                </svg>
                            </div>
                            <h3>{{ $authority->short_name ?: $authority->name }}</h3>
                            <p class="desc">{{ $authority->name }}</p>
                            <span class="btn btn-outline btn-block" style="opacity:0.6;cursor:not-allowed;pointer-events:none;">
                                Explore {{ $authority->short_name ?: $authority->name }} →
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         HOW IT WORKS
         ============================================================ --}}
    <section class="section" id="howSection" style="background:var(--blue-light);">
        <div class="container">
            <div class="section-head">
                <h2>How It Works</h2>
                <p>Three simple steps to your estimated rank</p>
            </div>
            <div class="steps">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <h4>Select your exam</h4>
                    <p>Pick your exam board and the specific exam panel — currently UPSSSC Junior Assistant is live.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <h4>Enter details &amp; marks</h4>
                    <p>Fill in your name, roll number, date of birth, category, gender and marks.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <h4>Get your estimated rank</h4>
                    <p>Instantly see your overall rank and category-wise rank based on the candidate database.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         ABOUT
         ============================================================ --}}
    <section class="section" id="aboutSection">
        <div class="container about-grid">
            <div>
                <h2 style="color:var(--navy);font-size:28px;margin-bottom:16px;">About UM Rank Predictor</h2>
                <p>UM Rank Predictor is an independent preparation tool built for aspirants of government exams. We help you estimate where your marks might place you, using a transparent, marks-first ranking method with a fair date-of-birth tie-break.</p>
                <p>We are not affiliated with UPSSSC, SSC, Indian Railways or any government body. Our predictions are estimates for preparation purposes only, based on the candidate data available in our system.</p>
            </div>
            <div>
                <svg viewBox="0 0 300 260" xmlns="http://www.w3.org/2000/svg">
                    <rect x="10" y="10" width="280" height="240" rx="20" fill="var(--blue-light)"/>
                    <circle cx="150" cy="110" r="55" fill="#fff" stroke="#DCE6F2" stroke-width="2"/>
                    <path d="M150 80v30l20 15" stroke="#126BD7" stroke-width="6" stroke-linecap="round" fill="none"/>
                    <rect x="70" y="185" width="160" height="14" rx="7" fill="#0A3873"/>
                    <rect x="90" y="205" width="120" height="10" rx="5" fill="#F2B705"/>
                </svg>
            </div>
        </div>
    </section>

    {{-- ============================================================
         CONTACT
         ============================================================ --}}
    <section class="section" id="contactSection" style="background:var(--blue-light);">
        <div class="container">
            <div class="section-head">
                <h2>Contact Us</h2>
                <p>Questions about the platform? Reach out.</p>
            </div>
            <div class="contact-card">
                <div class="contact-row">
                    <div class="ic">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 6l-10 7L2 6"/>
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Email</h4>
                        <p>support@umrankpredictor.example</p>
                    </div>
                </div>
                <div class="contact-row">
                    <div class="ic">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-7.5 8-13a8 8 0 10-16 0c0 5.5 8 13 8 13z"/>
                            <circle cx="12" cy="9" r="2.5"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Based in</h4>
                        <p>Uttar Pradesh, India</p>
                    </div>
                </div>
                <div class="contact-row" style="margin-bottom:0;">
                    <div class="ic">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 3"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Response time</h4>
                        <p>Typically within 2–3 working days</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
