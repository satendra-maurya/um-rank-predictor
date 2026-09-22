<?php

namespace App\Livewire;

use App\Enums\ActiveStatus;
use App\Models\ImportantLink;
use Livewire\Component;

class ImportantLinksWidget extends Component
{
    public function render()
    {
        $links = ImportantLink::with(['exam'])
            ->where('status', ActiveStatus::ACTIVE)
            ->orderBy('sort_order', 'asc')
            ->limit(6)
            ->get();

        return view('livewire.important-links-widget', [
            'links' => $links,
        ]);
    }
}
