<?php

namespace App\Jobs;

use Log;
use App\Models\Playlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueuePlaylists implements ShouldQueue{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 60;

    public function __construct() {

    }

    public function handle() {
        $playlists = Playlist::where('auto_update', true)->get();

        foreach ($playlists as $playlist) {
            ProcessPlaylist::dispatch($playlist)->onQueue(config('queue.connections.'.config('queue.default').'.queue').'-playlists');
        }
    }
}
