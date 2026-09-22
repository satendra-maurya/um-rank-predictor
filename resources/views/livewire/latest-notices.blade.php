<div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:26px;box-shadow:var(--shadow-sm);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:9px;height:9px;border-radius:50%;background:var(--danger);display:inline-block;" class="pulse-dot"></span>
            <h3 style="font-size:16px;font-weight:700;color:var(--text);">Latest Updates &amp; Notices</h3>
        </div>
        <a href="{{ route('notices.index') }}" style="font-size:12px;font-weight:600;color:var(--blue);">
            View All →
        </a>
    </div>

    @if($notices->isEmpty())
        <p style="font-size:13px;color:var(--muted);text-align:center;padding:20px 0;">No official notices published yet.</p>
    @else
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($notices as $notice)
                <a href="{{ route('notices.show', $notice->slug ?? $notice->id) }}"
                   style="display:block;padding:12px 14px;border-radius:var(--radius-sm);border:1px solid var(--border);transition:border-color .15s,background .15s;text-decoration:none;"
                   onmouseover="this.style.borderColor='var(--blue)';this.style.background='var(--blue-light)'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.background='transparent'">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:5px;">
                        <span style="font-size:10px;font-weight:700;background:var(--blue-light);color:var(--blue);padding:3px 8px;border-radius:999px;text-transform:uppercase;">
                            {{ $notice->notice_type ?? 'NOTICE' }}
                        </span>
                        @if($notice->notice_date)
                            <span style="font-size:11px;color:var(--muted);">
                                {{ $notice->notice_date->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                    <h4 style="font-size:13.5px;font-weight:600;color:var(--text);line-height:1.4;margin:0;">
                        {{ $notice->title }}
                    </h4>
                    @if($notice->exam)
                        <p style="font-size:12px;color:var(--muted);margin-top:4px;">
                            {{ $notice->exam->short_name ?? $notice->exam->name }}
                        </p>
                    @endif
                </a>
            @endforeach
        </div>
    @endif


</div>
