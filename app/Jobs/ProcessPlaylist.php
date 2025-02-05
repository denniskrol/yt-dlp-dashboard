<?php

namespace App\Jobs;

use Log;
use App\Models\Item;
use App\Models\Playlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPlaylist implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 900;

    protected $playlist, $status = 0;

    public function __construct(Playlist $playlist) {
        $this->playlist = $playlist;
        $this->playlist->status = 'queued';
        $this->playlist->save();
    }

    public function handle() {
        $scriptStartTime = microtime(true);

        $this->playlist->status = 'getting_info';
        $this->playlist->output = null;
        $this->playlist->error = null;
        $this->playlist->save();

        $domain = parse_url($this->playlist->url, PHP_URL_HOST);

        $command = config('app.youtube-dl_path').' -J "'.$this->playlist->url.'" --yes-playlist --flat-playlist --ignore-errors';
        if (isset(config('app.proxy')['*'])) {
            $command .= ' --proxy "'.config('app.proxy')['*'].'"';
        }
        else if (isset(config('app.proxy')[$domain])) {
            $command .= ' --proxy "'.config('app.proxy')[$domain].'"';
        }
        $command .= ' 2>&1';

        $output = null;
        exec($command, $output, $return);

        if ($return != 0) {
            if ((isset($output[0])) && (strpos($output[0], 'The playlist does not exist') !== false)) {
                $this->playlist->status = 'error';
                $this->playlist->error = 'The playlist does not exist';
                $this->playlist->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
                $this->playlist->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
                $this->playlist->save();
            }
            else {
                $this->playlist->status = 'error';
                $this->playlist->error = 'Unknown error';
                $this->playlist->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
                $this->playlist->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
                $this->playlist->save();
            }

            return;
        }

        $json = json_decode(end($output));

        if (!$json) {
            $this->playlist->status = 'error';
            $this->playlist->error = 'Failed to get playlist information';
            $this->playlist->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
            $this->playlist->output = iconv('UTF-8', 'UTF-8//IGNORE', end($output));
            $this->playlist->save();

            return;
        }

        $this->playlist->url = $json->webpage_url ?? $this->playlist->url;
        $this->playlist->extractor = $json->extractor;
        $this->playlist->title = $json->title ?? $this->playlist->title;
        $this->playlist->playlist_items_count = count($json->entries);
        $this->playlist->save();

        foreach ($json->entries as $entry) {
            if ($entry->ie_key == 'Youtube') {
                $entry->url = 'https://www.youtube.com/watch?v='.$entry->id;
            }

            if (Item::where('playlist_id', $this->playlist->id)->where('url', $entry->url)->withTrashed()->exists()) {
                continue;
            }

            $item = Item::create([
                'format' => $this->playlist->format,
                'path' => $this->playlist->path,
                'quality' => $this->playlist->quality,
                'url' => $entry->url,
                'playlist_id' => $this->playlist->id
            ]);

            DownloadItem::dispatch($item)->onQueue(config('queue.connections.'.config('queue.default').'.queue').'-items');
        }

        $this->playlist->status = 'processed';
        $this->playlist->checked_at = time();
        $this->playlist->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
        $this->playlist->save();
    }
}
