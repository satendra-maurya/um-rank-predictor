<x-layouts.app :title="$notice->title . ' - UM Rank Predictor'">

    <div class="bg-slate-900 text-white py-8 border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('notices.index') }}" class="text-xs text-blue-400 font-semibold hover:underline flex items-center gap-1 mb-3">
                &larr; Back to All Notices
            </a>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-blue-600 text-white uppercase">
                    {{ $notice->notice_type ?? 'NOTICE' }}
                </span>
                @if($notice->notice_date)
                    <span class="text-xs text-slate-300">
                        Published on {{ $notice->notice_date->format('d F Y') }}
                    </span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug">
                {{ $notice->title }}
            </h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-10 space-y-6">
            
            <!-- Metadata summary -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs sm:text-sm grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <span class="text-slate-500 block">Exam Name</span>
                    <strong class="text-slate-900 font-semibold">{{ $notice->exam->name ?? 'General Exam' }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Exam Cycle</span>
                    <strong class="text-slate-900 font-semibold">{{ $notice->examCycle->year ?? 'Current' }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Stage</span>
                    <strong class="text-slate-900 font-semibold">{{ $notice->examStage->name ?? 'N/A' }}</strong>
                </div>
            </div>

            <!-- Content -->
            @if($notice->content)
                <div class="prose max-w-none text-slate-800 text-sm sm:text-base leading-relaxed">
                    {!! nl2br(e($notice->content)) !!}
                </div>
            @endif

            <!-- Links & Attachments -->
            <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center gap-4">
                @if($notice->official_url)
                    <a href="{{ $notice->official_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-sm transition-all inline-flex items-center gap-2">
                        <span>Visit Official Portal</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif

                @if($notice->attachment_url)
                    <a href="{{ $notice->attachment_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs sm:text-sm border border-slate-300 transition-all inline-flex items-center gap-2">
                        <span>Download Official Document</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                @endif
            </div>

        </div>
    </div>

</x-layouts.app>
