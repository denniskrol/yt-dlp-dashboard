<?php

namespace App\Livewire\Playlists;

use App\Jobs\ProcessPlaylist;
use App\Models\Playlist;
use App\Models\Preset;
use App\Livewire\Forms\PlaylistForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Playlists extends Component
{
    public PlaylistForm $form;

    public function create(): void
    {
        $this->form->store();

        ProcessPlaylist::dispatch($playlist);
        $this->dispatch('toast', type: 'success', message: 'Playlist added');
    }

    public function mount(): void
    {
        $this->form->mount();
    }

    public function render(): View
    {
        return view('livewire.playlists.index')
            ->title('Playlists')
            ->with([
                'playlists' => Playlist::withCount('items')->latest()->get()
            ]);
    }

    public function updatedFormSelectedPreset($value): void
    {
        $this->form->updatedSelectedPreset($value);
    }
}
