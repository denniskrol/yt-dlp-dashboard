<div @if ($item->status !== 'done') wire:poll.1s @else wire:poll.60s @endif>
    <div class="w-full p-6">
        <div class="max-w-7xl mx-auto py-4 px-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100">
            <header class="pb-8 flex flex-row pb-2">
                <h2 class="basis-11/12 text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ $item->title }}
                </h2>
                <div class="basis-1/12 text-end">
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" title="Redownload" wire:click="redownload()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                        </svg>
                    </button>
                </div>
            </header>

            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    ID
                </div>
                <div class="basis-10/12">
                    {{ $item->id }}
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    URL
                </div>
                <div class="basis-10/12">
                    <a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a>
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Added
                </div>
                <div class="basis-10/12">
                    {{ $item->created_at->diffForHumans() }}
                </div>
            </div>
            @if ($item->playlist_id)
                <div class="flex flex-row pb-2">
                    <div class="basis-2/12">
                        Playlist
                    </div>
                    <div class="basis-10/12">
                        {{ $item->playlist->title ?? $item->playlist->url }}
                    </div>
                </div>
            @endif
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Status
                </div>
                <div class="basis-10/12">
                    {{ $item->statusText }}
                </div>
            </div>
            @if ($item->download_url)
                <div class="flex flex-row pb-2">
                    <div class="basis-2/12">
                        Download URL
                    </div>
                    <div class="basis-10/12">
                        <a href="{{ $item->download_url }}" target="_blank">{{ $item->download_url }}</a>
                    </div>
                </div>
            @endif
            @if ($item->status == 'error')
                <div class="flex flex-row pb-2">
                    <div class="basis-2/12">
                        Error
                    </div>
                    <div class="basis-10/12">
                        {{ $item->error }}
                    </div>
                </div>
            @endif
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Format
                </div>
                <div class="basis-10/12">
                    {{ $item->format }}
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Quality
                </div>
                <div class="basis-10/12">
                    {{ $item->quality }}@if ($item->quality != 'best')@if ($item->format == 'mp4')p @elseif ($item->format == 'mp3')KB/s @endif @endif
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Size
                </div>
                <div class="basis-10/12">
                    @if ($item->size) @ReadableSize($item->size)@endif
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Duration
                </div>
                <div class="basis-10/12">
                    @if ($item->duration) @ReadableTimeLength($item->duration)@endif
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Video
                </div>
                <div class="basis-10/12">
                    {{ $item->video_info }}
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Audio
                </div>
                <div class="basis-10/12">
                    {{ $item->audio_info }}
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Download Path
                </div>
                <div class="basis-10/12">
                    {{ $item->path }}
                </div>
            </div>
            @if ($item->filename)
                <div class="flex flex-row pb-2">
                    <div class="basis-2/12">
                        Pull Path
                    </div>
                    <div class="basis-10/12">
                        {{ $item->path }}{{ $item->filename }}
                    </div>
                </div>
            @endif
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Command
                </div>
                <div class="basis-10/12">
                    <span class="font-mono">{{ $item->command }}</span>
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Extractor
                </div>
                <div class="basis-10/12">
                    {{ $item->extractor }}
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Processing Time
                </div>
                <div class="basis-10/12">
                    @if ($item->processing_duration)
                        @ReadableTimeLength($item->processing_duration)
                    @endif
                </div>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-2/12">
                    Extra Flags
                </div>
                <div class="basis-10/12">
                    {{ $item->extra_flags }}
                </div>
            </div>
            @if ($item->output)
                <div>Output</div>
                <p class="font-mono">{!! nl2br($item->output) !!}</p>
            @endif
        </div>
    </div>
</div>
