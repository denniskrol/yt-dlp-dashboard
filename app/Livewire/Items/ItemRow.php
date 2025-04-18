<?php

namespace App\Livewire\Items;

use App\Jobs\DownloadItem;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ItemRow extends Component
{
    public Item $item;

    public function delete(): void
    {
        $this->item->delete();
        $this->dispatch('toast', type: 'success', message: 'Item deleted');
    }

    public function redownload(): void
    {
        DownloadItem::dispatch($this->item);
        $this->dispatch('toast', type: 'success', message: 'Redownloading item...');
    }

    public function render(): View
    {
        return view('livewire.items.item-row');
    }
}
