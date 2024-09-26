<div class="w-full">
    <img src="{{ Storage::url($imagePath) }}" alt="Generated Image" class="max-w-full h-auto rounded-lg shadow-lg">
</div>

<x-filament::modal id="prompt-modal" width="md">
    <x-slot name="heading">Image Prompt</x-slot>

    <div class="space-y-2">
        <p class="text-gray-500 dark:text-gray-400">
            {{ $getRecord()->content }}
        </p>
    </div>
</x-filament::modal>