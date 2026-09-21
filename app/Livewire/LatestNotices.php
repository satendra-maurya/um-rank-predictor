<?php

namespace App\Livewire;

use App\Models\Notice;
use Livewire\Component;

class LatestNotices extends Component
{
    public int $limit = 5;

    public function render()
    {
        $notices = Notice::with(['exam', 'examCycle'])
            ->where('status', 'ACTIVE')
            ->orderBy('is_important', 'desc')
            ->orderBy('notice_date', 'desc')
            ->limit($this->limit)
            ->get();

        return view('livewire.latest-notices', [
            'notices' => $notices,
        ]);
    }
}
