<div wire:poll.1s>
    <div class="w-full p-6">
        <div class="max-w-7xl mx-auto py-4 px-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100">
            <div>
                <x-input-label for="urls" :value="__('URLs')" />
                <x-textarea-input name="urls" wire:model="urls" wire:loading.class="opacity-50 border-yellow" placeholder="Paste link(s) here" class="w-full placeholder:italic placeholder:text-slate-400"/>
                <x-input-error class="mt-2" :messages="$errors->get('urls')" />
            </div>
            <div>
                <x-input-label for="preset" :value="__('Preset')" />
                <select id="preset" name="preset" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="selectedPreset">
                    @foreach ($presets as $preset)
                        <option value="{{ $preset->id }}">{{ $preset->name }}</option>
                    @endforeach
                    <option value="custom">Custom</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('preset')" />
            </div>
            @if ($selectedPreset == 'custom')
                <div>
                    <x-input-label for="format" :value="__('Format')" />
                    <x-text-input name="format" wire:model="format" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                    <x-input-error class="mt-2" :messages="$errors->get('format')" />
                </div>
                <div>
                    <x-input-label for="quality" :value="__('Quality')" />
                    @if ($format == 'mp4')
                        <select id="quality" name="quality" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="quality">
                            <option value="240">240p</option>
                            <option value="360">360p</option>
                            <option value="480">480p</option>
                            <option value="720">720p</option>
                            <option value="1080">1080p</option>
                            <option value="best">Best</option>
                        </select>
                    @elseif ($format == 'mp3')
                        <select id="quality" name="quality" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="quality">
                            <option value="128">128KB/s</option>
                            <option value="256">256KB/s</option>
                            <option value="320">320KB/s</option>
                            <option value="best">Best</option>
                        </select>
                    @endif
                    <x-input-error class="mt-2" :messages="$errors->get('quality')" />
                </div>
                <div>
                    <x-input-label for="path" :value="__('Path')" />
                    <x-text-input name="path" wire:model="path" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                    <x-input-error class="mt-2" :messages="$errors->get('path')" />
                </div>
            @endif
            <div class="pt-2">
                <x-primary-button wire:click="create()" :value="__('Add')"/>
            </div>
        </div>

        <div class="py-4 px-4 my-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100">
            <table class="table-auto w-full">
                <thead>
                <tr>
                    <th>Name</th>
                    <th class="px-6">Status</th>
                    <th class="px-6">Format</th>
                    <th class="px-6">Size</th>
                    <th class="px-6">Length</th>
                    <th class="px-6">Added</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <livewire:item-row :$item :key="$item->id.'.'.$item->updated_at" />
                @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
