<?php

namespace App\Livewire\Presets;

use App\Models\Preset;
use App\Livewire\Forms\PresetForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShowPreset extends Component
{
    public PresetForm $form;
    public Preset $playlist;

    public function mount(Preset $preset): void
    {
        $this->form->setPreset($preset);
    }

    public function render(): View
    {
        return view('livewire.presets.show');
    }

    public function update(): void
    {
        $this->form->update();
        $this->dispatch('toast', type: 'success', message: 'Preset saved');
    }
}
