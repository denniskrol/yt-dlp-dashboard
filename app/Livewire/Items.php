<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public function delete($id): void
    {
        //Item::where('id', $id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Item deleted');
    }

    public function redownload($id): void
    {
        //$item = Item::where('id', $id)->firstOrFail();
        //DownloadItem::dispatch($item)->onQueue(config('queue.connections.'.config('queue.default').'.queue').'-items');
        $this->dispatch('toast', type: 'success', message: 'Redownloading item...');
    }

    public function render(): View
    {
        $items = Item::latest()->paginate(50);

        return view('livewire.items')
            ->with([
                'items' => $items
            ]);
    }
}
