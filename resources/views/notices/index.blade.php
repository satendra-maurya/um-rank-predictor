<x-layouts.app title="Official Exam Updates & Notices - UM Rank Predictor">

    <div class="bg-slate-900 text-white py-10 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold tracking-tight">Official Exam Notices & Updates</h1>
            <p class="text-slate-400 text-sm mt-2">Latest notifications, answer keys, exam dates, and official announcements.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($notices->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                <p class="text-slate-500">No notices published at this moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($notices as $notice)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-800 uppercase">
                                    {{ $notice->notice_type ?? 'NOTICE' }}
                                </span>
                                @if($notice->notice_date)
                                    <span class="text-xs text-slate-400 font-medium">
                                        {{ $notice->notice_date->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                            
                            <h2 class="text-base font-bold text-slate-900 leading-snug">
                                <a href="{{ route('notices.show', $notice->slug ?? $notice->id) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $notice->title }}
                                </a>
                            </h2>

                            @if($notice->exam)
                                <p class="text-xs text-slate-500 font-medium mt-2">
                                    Exam: {{ $notice->exam->name }}
                                </p>
                            @endif

                            @if($notice->content)
                                <p class="text-xs text-slate-600 mt-2 line-clamp-3">
                                    {{ $notice->content }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <a href="{{ route('notices.show', $notice->slug ?? $notice->id) }}" class="font-bold text-blue-600 hover:underline">
                                Read Full Notice &rarr;
                            </a>
                            @if($notice->official_url)
                                <a href="{{ $notice->official_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-slate-700">
                                    Official Link
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $notices->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>
