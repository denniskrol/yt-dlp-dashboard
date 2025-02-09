<?php

namespace App\Livewire\Playlists;

use App\Jobs\ProcessPlaylist;
use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PlaylistRow extends Component
{
    public Playlist $playlist;

    public function delete(): void
    {
        $this->playlist->delete();
        $this->dispatch('toast', type: 'success', message: 'Playlist deleted');
    }

    public function check(): void
    {
        ProcessPlaylist::dispatch($this->playlist);
        $this->dispatch('toast', type: 'success', message: 'Checking playlist...');
    }

    public function render(): View
    {
        return view('livewire.playlists.playlist-row');
    }
}
