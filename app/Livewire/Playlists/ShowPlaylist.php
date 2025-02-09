<?php

namespace App\Livewire\Playlists;

use App\Jobs\ProcessPlaylist;
use App\Models\Playlist;
use App\Livewire\Forms\PlaylistForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShowPlaylist extends Component
{
    public PlaylistForm $form;
    public Playlist $playlist;

    public function check(): void
    {
        ProcessPlaylist::dispatch($this->playlist);
        $this->dispatch('toast', type: 'success', message: 'Checking playlist...');
    }

    public function mount(Playlist $playlist): void
    {
        $this->playlist->load('items');
        $this->form->setPlaylist($playlist);
    }

    public function render(): View
    {
        return view('livewire.playlists.show');
    }

    public function update(): void
    {
        $this->form->update();
        $this->dispatch('toast', type: 'success', message: 'Playlist saved');
    }
}
