<x-filament::page>
    <x-filament::card>
        <form wire:submit.prevent="processLogic">
            {{ $this->form }}
        </form>
    </x-filament::card>
</x-filament::page>