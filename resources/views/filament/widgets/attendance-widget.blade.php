@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2
                    class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white"
                >
                    {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ filament()->getUserName($user) }}
                </p>
            </div>

            @if (! $isRunning)
                <x-filament::button color="info" tag="button" icon="heroicon-s-play" wire:click="start">
                    Start Working
                </x-filament::button>
            @else
                <x-filament::button color="danger" tag="button" icon="heroicon-s-stop" wire:click="stop">
                    Stop Working
                </x-filament::button>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
