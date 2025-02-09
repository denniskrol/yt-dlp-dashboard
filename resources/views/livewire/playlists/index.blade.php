<div wire:poll.1s>
    <div class="w-full p-6">
        <div class="max-w-7xl mx-auto py-4 px-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100">
            <div>
                <x-input-label for="url" :value="__('URL')" />
                <x-text-input name="format" wire:model="form.url" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                <x-input-error class="mt-2" :messages="$errors->get('form.url')" />
            </div>
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input name="title" wire:model="form.title" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                <x-input-error class="mt-2" :messages="$errors->get('form.title')" />
            </div>
            <div>
                <x-input-label for="preset" :value="__('Preset')" />
                <select id="preset" name="preset" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="form.selectedPreset">
                    @foreach ($form->presets as $preset)
                        <option value="{{ $preset->id }}">{{ $preset->name }}</option>
                    @endforeach
                    <option value="custom">Custom</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('form.preset')" />
            </div>
            @if ($form->selectedPreset === 'custom')
                <div class="flex flex-row pb-2">
                    <div class="basis-6/12 mr-2">
                        <x-input-label for="format" :value="__('Format')" />
                        <select id="format" name="format" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="form.quality">
                            <option value="mp4">.mp4</option>
                            <option value="mp3">.mp3</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('form.format')" />
                    </div>
                    <div class="basis-6/12">
                        <x-input-label for="quality" :value="__('Quality')" />
                        @if ($form->format === 'mp4')
                            <select id="quality" name="quality" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="form.quality">
                                <option value="240">240p</option>
                                <option value="360">360p</option>
                                <option value="480">480p</option>
                                <option value="720">720p</option>
                                <option value="1080">1080p</option>
                                <option value="best">Best</option>
                            </select>
                        @elseif ($form->format === 'mp3')
                            <select id="quality" name="quality" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="form.quality">
                                <option value="128">128KB/s</option>
                                <option value="256">256KB/s</option>
                                <option value="320">320KB/s</option>
                                <option value="best">Best</option>
                            </select>
                        @endif
                        <x-input-error class="mt-2" :messages="$errors->get('form.quality')" />
                    </div>
                </div>
                <div>
                    <x-input-label for="path" :value="__('Path')" />
                    <x-text-input name="path" wire:model="form.path" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                    <x-input-error class="mt-2" :messages="$errors->get('form.path')" />
                </div>
            @endif
            <div class="flex flex-row pb-2">
                <div class="basis-6/12 mr-2">
                    <x-input-label for="auto_update" :value="__('Auto Update')" />
                    <x-check-input name="auto_update" wire:model="form.auto_update" wire:loading.class="opacity-50 border-yellow" class="placeholder:italic placeholder:text-slate-400"/>
                    <x-input-error class="mt-2" :messages="$errors->get('form.auto_update')" />
                </div>
                <div class="basis-6/12">
                    <x-input-label for="prefix_playlist_name" :value="__('Prefix filename with playlist name')" />
                    <x-check-input name="prefix_playlist_name" wire:model="form.prefix_playlist_name" wire:loading.class="opacity-50 border-yellow" class="placeholder:italic placeholder:text-slate-400"/>
                    <x-input-error class="mt-2" :messages="$errors->get('form.prefix_playlist_name')" />
                </div>
            </div>
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
                    <th class="px-6">Items</th>
                    <th class="px-6">Auto Update</th>
                    <th class="px-6">Prefix</th>
                    <th class="px-6">Checked</th>
                    <th class="px-6">Added</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($playlists as $playlist)
                    <livewire:playlists.playlist-row :$playlist :key="$playlist->id.'.'.$playlist->updated_at" />
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
