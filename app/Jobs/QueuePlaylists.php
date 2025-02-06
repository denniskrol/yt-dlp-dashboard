<?php

namespace App\Jobs;

use App\Models\Playlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueuePlaylists implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 60;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $playlists = Playlist::where('auto_update', true)->get();

        foreach ($playlists as $playlist) {
            ProcessPlaylist::dispatch($playlist);
        }
    }
}
