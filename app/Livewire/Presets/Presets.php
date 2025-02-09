<?php

namespace App\Livewire\Presets;

use App\Livewire\Forms\PresetForm;
use App\Models\Preset;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class Presets extends Component
{
    public PresetForm $form;

    public function create(): Redirector
    {
        $this->form->store();
        $this->dispatch('toast', type: 'success', message: 'Preset added');

        return redirect()->to('/presets');
    }

    public function render(): View
    {
        return view('livewire.presets.presets')
            ->title('Presets')
            ->with([
                'presets' => Preset::orderBy('name')->get()
            ]);
    }
}
