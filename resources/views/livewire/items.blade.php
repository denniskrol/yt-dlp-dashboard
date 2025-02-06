<div wire:poll.1s>
    <div class="w-full p-6">
        <div class="py-4 px-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100">
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
                    <livewire:item :$item :key="$item->id.'.'.$item->status" />
                @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $items->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
