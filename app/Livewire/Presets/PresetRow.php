<?php

namespace App\Livewire\Presets;

use App\Models\Preset;
use Illuminate\Contracts\View\View;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\Component;

class PresetRow extends Component
{
    public Preset $preset;

    public function delete(): Redirector
    {
        $this->preset->delete();
        $this->dispatch('toast', type: 'success', message: 'Preset deleted');

        return redirect()->to('/presets');
    }

    public function render(): View
    {
        return view('livewire.presets.preset-row');
    }
}
