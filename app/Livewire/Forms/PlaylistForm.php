<?php

namespace App\Livewire\Forms;

use App\Models\Playlist;
use App\Models\Preset;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PlaylistForm extends Form
{
    public ?Playlist $playlist;
    #[Validate('required|boolean')]
    public bool $auto_update = true;
    #[Validate('required|string')]
    public string $format = 'mp4';
    #[Validate('required|string')]
    public string $path = '';
    #[Validate('required|boolean')]
    public bool $prefix_playlist_name = true;
    public Collection $presets;
    #[Validate('required|string')]
    public string $quality = 'best';
    public string $selectedPreset = '';
    #[Validate('required|string')]
    public string $title = '';
    #[Validate('required|string|min:6')]
    public string $url = '';

    public function mount(): void
    {
        $this->path = config('app.media_download_path');
        $this->presets = Preset::orderBy('name')->get();
        $this->selectedPreset = config('app.default_preset');
    }

    public function setPlaylist(Playlist $playlist): void
    {
        $this->playlist = $playlist;

        $this->auto_update = $playlist->auto_update;
        $this->format = $playlist->format;
        $this->path = $playlist->path;
        $this->prefix_playlist_name = $playlist->prefix_playlist_name;
        $this->quality = $playlist->quality;
        $this->title = $playlist->title;
        $this->url = $playlist->url;
    }

    public function store(): void
    {
        $this->validate();

        Playlist::create($this->except(['playlist', 'presets', 'selectedPreset']));
        $this->reset(['url', 'title']);
    }

    public function update(): void
    {
        $this->validate();

        $this->playlist->update($this->except(['playlist', 'presets', 'selectedPreset']));
    }

    public function updatedSelectedPreset($value): void
    {
        if ($value == 'custom') {
            $this->reset(['format', 'quality']);
            $this->quality = 'best';
            $this->path = config('app.media_download_path');
        }
        else {
            $preset = Preset::findOrFail($this->selectedPreset);
            $this->format = $preset->format;
            $this->path = $preset->path;
            $this->quality = $preset->quality;
        }
    }
}
