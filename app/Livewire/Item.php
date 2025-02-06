<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Item extends Component
{
    public \App\Models\Item $item;

    public function render(): View
    {
        return view('components.item-row');
    }
}
