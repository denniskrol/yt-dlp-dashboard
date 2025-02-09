<?php

namespace App\Livewire\Forms;

use App\Models\Preset;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PresetForm extends Form
{
    public ?Preset $preset;
    #[Validate('required|string')]
    public string $format = 'mp4';
    #[Validate('required|string')]
    public string $path = '';
    #[Validate('required|string')]
    public string $quality = 'best';
    #[Validate('required|string')]
    public string $name = '';

    public function mount(): void
    {
        $this->path = config('app.media_download_path');
    }

    public function setPreset(Preset $preset): void
    {
        $this->preset = $preset;

        $this->format = $preset->format;
        $this->path = $preset->path;
        $this->quality = $preset->quality;
        $this->name = $preset->name;
    }

    public function store(): void
    {
        $this->validate();

        Preset::create($this->except(['preset']));
        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $this->preset->update($this->except(['preset']));
    }
}
