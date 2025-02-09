<div>
    <div class="w-full p-6">
        <div class="max-w-7xl mx-auto py-4 px-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input name="name" wire:model="form.name" wire:loading.class="opacity-50 border-yellow" class="w-full placeholder:italic placeholder:text-slate-400"/>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
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
            <div class="pt-2 pb-4">
                <x-primary-button wire:click="update()" :value="__('Save')"/>
            </div>
        </div>
    </div>
</div>
