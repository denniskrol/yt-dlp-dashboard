<?php

namespace App\Livewire\Playlists;

use App\Jobs\ProcessPlaylist;
use App\Livewire\Forms\PlaylistForm;
use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class Playlists extends Component
{
    public PlaylistForm $form;

    public function create(): Redirector
    {
        $this->form->store();

        ProcessPlaylist::dispatch($playlist);
        $this->dispatch('toast', type: 'success', message: 'Playlist added');

        return redirect()->to('/playlists');
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
