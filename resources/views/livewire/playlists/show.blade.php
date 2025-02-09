<div @if ($playlist->status !== 'done') wire:poll.1s @else wire:poll.60s @endif>
    <div class="w-full p-6">
        <div class="max-w-7xl mx-auto py-4 px-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100">
            <div>
                <x-input-label for="url" :value="__('URL')" />
                <x-text-input name="url" wire:model="form.url" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                <x-input-error class="mt-2" :messages="$errors->get('form.url')" />
            </div>
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input name="title" wire:model="form.title" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-6/12 mr-2">
                    <x-input-label for="format" :value="__('Format')" />
                    <select id="format" name="format" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model.live="form.format">
                        <option value="mp4">.mp4</option>
                        <option value="mp3">.mp3</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('format')" />
                </div>
                <div class="basis-6/12">
                    <x-input-label for="quality" :value="__('Quality')" />
                    <select id="quality" name="quality" class="w-full p-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs" wire:model="form.quality">
                        @if ($form->format === 'mp4')
                            <option value="240">240p</option>
                            <option value="360">360p</option>
                            <option value="480">480p</option>
                            <option value="720">720p</option>
                            <option value="1080">1080p</option>
                            <option value="best" selected>Best</option>
                        @elseif ($form->format === 'mp3')
                            <option value="128">128KB/s</option>
                            <option value="256">256KB/s</option>
                            <option value="320">320KB/s</option>
                            <option value="best" selected>Best</option>
                        @endif
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('quality')" />
                </div>
            </div>
            <div>
                <x-input-label for="path" :value="__('Path')" />
                <x-text-input name="path" wire:model="form.path" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                <x-input-error class="mt-2" :messages="$errors->get('path')" />
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-6/12 mr-2">
                    <x-input-label for="auto_update" :value="__('Auto Update')" />
                    <x-check-input name="auto_update" wire:model="form.auto_update" wire:loading.class="opacity-50 border-yellow" class="placeholder:italic placeholder:text-slate-400"/>
                    <x-input-error class="mt-2" :messages="$errors->get('auto_update')" />
                </div>
                <div class="basis-6/12">
                    <x-input-label for="prefix_playlist_name" :value="__('Prefix filename with playlist name')" />
                    <x-check-input name="prefix_playlist_name" wire:model="form.prefix_playlist_name" wire:loading.class="opacity-50 border-yellow" class="placeholder:italic placeholder:text-slate-400"/>
                    <x-input-error class="mt-2" :messages="$errors->get('prefix_playlist_name')" />
                </div>
            </div>
            <div class="pt-2 pb-4">
                <x-primary-button wire:click="update()" :value="__('Save')"/>
                <x-primary-button wire:click="check()" :value="__('Check')"/>
            </div>
            <div class="flex flex-row pb-2">
                <div class="basis-6/12 mr-2">
                    <div class="flex flex-row pb-2">
                        <div class="basis-4/12">
                            Added
                        </div>
                        <div class="basis-8/12">
                            {{ $playlist->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="flex flex-row pb-2">
                        <div class="basis-4/12">
                            Checked
                        </div>
                        <div class="basis-8/12">
                            {{ $playlist->checked_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="flex flex-row pb-2">
                        <div class="basis-4/12">
                            Items
                        </div>
                        <div class="basis-8/12">
                            {{ $playlist->items->count() }} / {{ $playlist->playlist_items_count }}
                        </div>
                    </div>
                </div>
                <div class="basis-6/12 mr-2">
                    <div class="flex flex-row pb-2">
                        <div class="basis-4/12">
                            Status
                        </div>
                        <div class="basis-8/12">
                            {{ $playlist->statusText }}
                        </div>
                    </div>
                    @if ($playlist->status == 'error')
                        <div class="flex flex-row pb-2">
                            <div class="basis-4/12">
                                Error
                            </div>
                            <div class="basis-8/12">
                                {{ $playlist->error }}
                            </div>
                        </div>
                    @endif
                    <div class="flex flex-row pb-2">
                        <div class="basis-4/12">
                            Extractor
                        </div>
                        <div class="basis-8/12">
                            {{ $playlist->extractor }}
                        </div>
                    </div>
                    <div class="flex flex-row pb-2">
                        <div class="basis-4/12">
                            Processing Time
                        </div>
                        <div class="basis-8/12">
                            @if ($playlist->processing_duration)
                                @ReadableTimeLength($playlist->processing_duration)
                            @endif
                        </div>
                    </div>
                    @if ($playlist->output)
                        <div>Output</div>
                        <p class="font-mono">{!! nl2br($playlist->output) !!}</p>
                    @endif
                </div>
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
                @foreach ($playlist->items as $item)
                    <livewire:items.item-row :$item :key="$item->id.'.'.$item->updated_at" />
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
