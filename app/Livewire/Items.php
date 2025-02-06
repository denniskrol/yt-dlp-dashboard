<?php

namespace App\Livewire;

use App\Jobs\DownloadItem;
use App\Models\Item;
use App\Models\Preset;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public string $format = 'mp4';
    public string $path;
    public Collection $presets;
    public string $quality = 'best';
    protected array $rules = [
        'format' => 'required|string',
        'path' => 'required|string',
        'quality' => 'required|string',
        'urls' => 'required|string|min:6',
    ];
    public string $selectedPreset = 'custom';
    public string $urls;

    public function create(): void
    {
        if (mb_substr($this->path, -1) != DIRECTORY_SEPARATOR) {
            $this->path .= DIRECTORY_SEPARATOR;
        }

        $this->validate();

        $itemsAdded = 0;
        $urls = preg_split('/\r\n|[\r\n]/', $this->urls);
        foreach ($urls as $url) {
            if (!Str::startsWith($url, 'http')) {
                continue;
            }

            $item = Item::create([
                'format' => $this->format,
                'path' => $this->path,
                'quality' => $this->quality,
                'url' => $url,
            ]);

            DownloadItem::dispatch($item);

            $itemsAdded++;
        }

        $this->reset(['urls']);
        $this->dispatch('toast', type: 'success', message: $itemsAdded.' '.Str::plural('item', $itemsAdded).' added');
    }

    public function mount(): void
    {
        $this->path = config('app.media_download_path');

        $this->presets = Preset::orderBy('name')->get();
        $preset = $this->presets->first();

        if ($preset) {
            $this->format = $preset->format;
            $this->quality = $preset->quality;
            $this->path = $preset->path;
            $this->selectedPreset = $preset->id;
        }
    }

    public function render(): View
    {
        return view('livewire.items')
            ->with([
                'items' => Item::latest()->paginate(50)
            ]);
    }

    public function updatedSelectedPreset($value): void
    {
        if ($value == 'custom') {
            $this->reset(['format', 'quality']);
            $this->path = config('app.media_download_path');
        }
        else {
            $preset = $this->presets->where('id', $value)->first();
            $this->format = $preset->format;
            $this->quality = $preset->quality;
            $this->path = $preset->path;
        }
    }
}
