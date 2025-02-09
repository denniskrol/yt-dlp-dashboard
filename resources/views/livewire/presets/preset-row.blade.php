<tr>
    <td class="overflow-x-hidden"><a href="/presets/{{ $preset->id }}" wire:navigate.hover>{{ $preset->name }}</a></td>
    <td class="px-6 text-center">{{ $preset->format }} / {{ $preset->quality }}@if ($preset->quality != 'best')@if ($preset->format == 'mp4')p @elseif ($preset->format == 'mp3')KB/s @endif @endif</td>
    <td class="px-6 text-center">{{ $preset->path }}</td>
    <td class="text-end">
        <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 mr-2" title="Delete" wire:click="delete()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash2" viewBox="0 0 16 16">
                <path d="M14 3a.702.702 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671L2.037 3.225A.703.703 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2zM3.215 4.207l1.493 8.957a1 1 0 0 0 .986.836h4.612a1 1 0 0 0 .986-.836l1.493-8.957C11.69 4.689 9.954 5 8 5c-1.954 0-3.69-.311-4.785-.793z"/>
            </svg>
        </button>
    </td>
</tr>
