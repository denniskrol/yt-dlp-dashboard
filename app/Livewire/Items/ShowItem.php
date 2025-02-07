<?php

namespace App\Livewire\Items;

use App\Jobs\DownloadItem;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShowItem extends Component
{
    public Item $item;

    public function redownload(): void
    {
        DownloadItem::dispatch($this->item);
        $this->dispatch('toast', type: 'success', message: 'Redownloading item...');
    }

    public function render(): View
    {
        return view('livewire.items.show');
    }
}
