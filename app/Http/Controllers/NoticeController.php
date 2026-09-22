<?php

namespace App\Http\Controllers;

use App\Enums\ActiveStatus;
use App\Models\Notice;
use Illuminate\Contracts\View\View;

class NoticeController extends Controller
{
    public function index(): View
    {
        $notices = Notice::with(['exam', 'examCycle', 'examStage'])
            ->where('status', ActiveStatus::ACTIVE)
            ->orderBy('is_important', 'desc')
            ->orderBy('notice_date', 'desc')
            ->paginate(12);

        return view('notices.index', [
            'notices' => $notices,
        ]);
    }

    public function show(string $slug): View
    {
        $notice = Notice::with(['exam', 'examCycle', 'examStage'])
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)->orWhere('id', $slug);
            })
            ->where('status', ActiveStatus::ACTIVE)
            ->firstOrFail();

        return view('notices.show', [
            'notice' => $notice,
        ]);
    }
}
