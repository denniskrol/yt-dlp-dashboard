<?php

namespace App\Jobs;

use Log;
use Throwable;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class DownloadItem implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 1800;

    protected $item, $status = 0;

    public function __construct(Item $item) {
        $this->item = $item;
        $this->item->status = 'queued';
        $this->item->save();
    }

    public function handle() {
        $scriptStartTime = microtime(true);
        if (!file_exists($this->item->path)) {
            $this->item->status = 'error';
            $this->item->error = 'Path doesnt exist';
            $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
            $this->item->save();

            return;
        }

        $this->item->job_uuid = $this->job->uuid();
        $this->item->status = 'getting_info';
        $this->item->output = null;
        $this->item->error = null;
        $this->item->save();

        $domain = parse_url($this->item->url, PHP_URL_HOST);

        $command = config('app.youtube-dl_path').' -j "'.$this->item->url.'" -o "';
        // Prefix filename with playlist name
        if (($this->item->playlist) && ($this->item->playlist->prefix_playlist_name)) {
            $command .= '['.$this->item->playlist->title.'] ';
        }
        $command .= '%(title)s-%(id)s.%(ext)s" --no-playlist --no-progress --no-mtime';
        if (isset(config('app.proxy')['*'])) {
            $command .= ' --proxy "'.config('app.proxy')['*'].'"';
        }
        else if (isset(config('app.proxy')[$domain])) {
            $command .= ' --proxy "'.config('app.proxy')[$domain].'"';
        }
        if (config('app.is_yt-dlp')) {
            //$command .= ' --parse-metadata " :%(automatic_captions)s"';
        }
        $command .= ' 2>&1';

        $output = null;
        exec($command, $output, $return);

        if ($return != 0) {
            if ((isset($output[0])) && (strpos($output[0], 'This video has been removed by the user') !== false)) {
                $this->item->status = 'error';
                $this->item->error = 'This video has been removed by the user';
                $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
                $this->item->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
                $this->item->save();
            }
            elseif ((isset($output[0])) && (strpos($output[0], 'This video is private') !== false)) {
                $this->item->status = 'error';
                $this->item->error = 'This video is private';
                $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
                $this->item->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
                $this->item->save();
            }
            elseif ((isset($output[0])) && (strpos($output[0], 'This video has been disabled') !== false)) {
                $this->item->status = 'error';
                $this->item->error = 'This video has been disabled';
                $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
                $this->item->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
                $this->item->save();
            }
            elseif ((isset($output[2])) && (strpos($output[2], 'This video may be inappropriate for some users') !== false)) {
                $this->item->status = 'error';
                $this->item->error = 'This video may be inappropriate for some users.';
                $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
                $this->item->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
                $this->item->save();
            }
            else {
                $this->item->status = 'error';
                $this->item->error = $output[0] ?? 'Unknown error';
                $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
                $this->item->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
                $this->item->save();
            }

            return;
        }

        $json = json_decode(end($output));

        if (!$json) {
            $this->item->status = 'error';
            $this->item->error = 'Failed to get media information';
            $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
            $this->item->output = iconv('UTF-8', 'UTF-8//IGNORE', end($output));
            $this->item->save();

            return;
        }

        $this->item->url = $json->webpage_url ?? $this->item->url;
        $this->item->filename = $json->_filename;
        $this->item->extractor = $json->extractor;
        $this->item->title = $json->title;
        $this->item->duration = $json->duration ?? null;

        $this->item->status = 'downloading';
        $this->item->save();

        $command = config('app.youtube-dl_path').' "'.$this->item->url.'" -o "'.$this->item->path;
        // Prefix filename with playlist name
        if (($this->item->playlist) && ($this->item->playlist->prefix_playlist_name)) {
            $command .= '['.$this->item->playlist->title.'] ';
        }
        $command .= '%(title)s-%(id)s.%(ext)s" --no-playlist --no-progress --no-mtime';
        if (isset(config('app.proxy')['*'])) {
            $command .= ' --proxy "'.config('app.proxy')['*'].'"';
        }
        else if (isset(config('app.proxy')[$domain])) {
            $command .= ' --proxy "'.config('app.proxy')[$domain].'"';
        }
        if ($this->item->format == 'mp4') {
            if ($this->item->quality != 'best') {
                $command .= ' -f "bestvideo[height<='.$this->item->quality.']+bestaudio"';
            }
            $command .= ' --merge-output-format '.$this->item->format;
        }
        else if ($this->item->format == 'mp3') {
            $command .= ' --extract-audio --audio-format mp3';
            if ($this->item->quality != 'best') {
                $command .= ' --audio-quality '.$this->item->quality.'K';
            }
        }
        $command .= ' 2>&1';
        $this->item->command = $command;
        $output = null;
        exec($command, $output, $return);

        if ($return != 0) {
            $this->item->status = 'error';
            $this->item->error = 'Download failed';
            $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
            $this->item->output = implode(PHP_EOL, $output);
            $this->item->save();

            return;
        }

        // Make sure we save the correct extension, even after FFMpeg conversion
        $this->item->filename = str_replace(pathinfo($this->item->path.$this->item->filename, PATHINFO_EXTENSION), $this->item->format, $this->item->filename);
        $this->item->size = filesize($this->item->path.$this->item->filename);
        $this->item->status = 'done';
        $this->item->output = iconv('UTF-8', 'UTF-8//IGNORE', implode(PHP_EOL, $output));
        $this->item->save();

        $command = config('app.ffprobe_path').' "'.$this->item->path.$this->item->filename.'" -v quiet -print_format json -show_streams';
        $output = null;
        exec($command, $output, $return);

        $audioInfo = 'No audio';
        $videoInfo = 'No video';

        if ($return == 0) {
            $json = json_decode(implode('', $output));

            foreach ($json->streams as $stream) {
                if ($stream->codec_type == 'audio') {
                    $audioInfo = round($stream->bit_rate / 1024).'kb/s @ '.round(($stream->sample_rate / 1000), 1).'kHz ('.$stream->channels.' channels)';
                }
                elseif ($stream->codec_type == 'video') {
                    $frameRate = explode('/', $stream->avg_frame_rate);
                    $fps = round(($frameRate[0] / $frameRate[1]), 3);
                    $videoInfo = $stream->width.'x'.$stream->height.' @ '.$fps.'fps';
                }
            }
        }

        $this->item->audio_info = $audioInfo;
        $this->item->video_info = $videoInfo;
        $this->item->processing_duration = (round((microtime(true) - $scriptStartTime), 2));
        $this->item->save();
    }

    public function failed(Throwable $exception) {
        $this->item->status = 'error';
        $this->item->error = $exception->getMessage();
        $this->item->save();
    }
}
