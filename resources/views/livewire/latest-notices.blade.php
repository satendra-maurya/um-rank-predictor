<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
            <h3 class="text-lg font-bold text-slate-900">Latest Updates & Notices</h3>
        </div>
        <a href="{{ route('notices.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
            View All Notices &rarr;
        </a>
    </div>

    @if($notices->isEmpty())
        <p class="text-xs text-slate-500 py-4 text-center">No official notices published yet.</p>
    @else
        <div class="space-y-3">
            @foreach($notices as $notice)
                <a href="{{ route('notices.show', $notice->slug ?? $notice->id) }}" class="block p-3.5 rounded-xl border border-slate-100 hover:border-blue-300 hover:bg-blue-50/30 transition-all group">
                    <div class="flex items-start justify-between gap-2">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">
                            {{ $notice->notice_type ?? 'NOTICE' }}
                        </span>
                        @if($notice->notice_date)
                            <span class="text-[11px] text-slate-400 font-medium">
                                {{ $notice->notice_date->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors mt-1.5 leading-snug">
                        {{ $notice->title }}
                    </h4>
                    @if($notice->exam)
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $notice->exam->short_name ?? $notice->exam->name }}
                        </p>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>
