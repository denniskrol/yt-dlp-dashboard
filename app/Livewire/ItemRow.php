<?php

namespace App\Livewire;

use App\Jobs\DownloadItem;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ItemRow extends Component
{
    public Item $item;

    public function delete($id): void
    {
        Item::where('id', $id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Item deleted');
    }

    public function redownload($id): void
    {
        $item = Item::where('id', $id)->firstOrFail();
        DownloadItem::dispatch($item);
        $this->dispatch('toast', type: 'success', message: 'Redownloading item...');
    }

    public function render(): View
    {
        return view('components.item-row');
    }
}
