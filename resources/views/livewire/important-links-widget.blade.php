<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
        <h3 class="text-lg font-bold text-slate-900">Important Direct Links</h3>
    </div>

    @if($links->isEmpty())
        <p class="text-xs text-slate-500 py-4 text-center">No links configured.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            @foreach($links as $link)
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="p-3 rounded-xl border border-slate-100 hover:border-emerald-400 hover:bg-emerald-50/30 transition-all flex items-center justify-between group">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span class="text-xs font-semibold text-slate-800 group-hover:text-emerald-700 truncate">
                            {{ $link->title }}
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endforeach
        </div>
    @endif
</div>
