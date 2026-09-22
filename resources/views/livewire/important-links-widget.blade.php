<div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:26px;box-shadow:var(--shadow-sm);">
    <div style="margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border);">
        <h3 style="font-size:16px;font-weight:700;color:var(--text);">Important Direct Links</h3>
    </div>

    @if($links->isEmpty())
        <p style="font-size:13px;color:var(--muted);text-align:center;padding:20px 0;">No links configured.</p>
    @else
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            @foreach($links as $link)
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                   style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding:12px 14px;border-radius:var(--radius-sm);border:1px solid var(--border);transition:border-color .15s,background .15s;text-decoration:none;overflow:hidden;"
                   onmouseover="this.style.borderColor='var(--success)';this.style.background='#f0fdf7'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.background='transparent'">
                    <div style="display:flex;align-items:center;gap:8px;overflow:hidden;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2" style="flex-shrink:0;">
                            <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span style="font-size:12.5px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $link->title }}
                        </span>
                    </div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2" style="flex-shrink:0;">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif
</div>
