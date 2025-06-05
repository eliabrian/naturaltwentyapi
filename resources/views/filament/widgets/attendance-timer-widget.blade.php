<x-filament-widgets::widget>
    <x-filament::card>
        <div class="text-center space-y-4">
            <div class="text-lg font-semibold">
                Today's Time: <span id="total-time">{{ $duration }}</span>
            </div>

            @if ($isRunning)
                <div class="text-md text-gray-600">
                    Session Time: <span id="session-timer">00:00:00</span>
                </div>
                <x-filament::button wire:click="pause" color="warning">Pause</x-filament::button>
                <x-filament::button wire:click="stop" color="danger">Stop</x-filament::button>
            @else
                <x-filament::button wire:click="startOrResume" color="info">Start / Resume</x-filament::button>
            @endif
        </div>

        @if ($isRunning && $startedAt)
            <script>
                let sessionStart = new Date("{{ $startedAt->format('Y-m-d H:i:s') }}").getTime();

                function updateSessionTimer() {
                    let now = new Date().getTime();
                    let diff = now - sessionStart;

                    let hours = Math.floor(diff / (1000 * 60 * 60));
                    let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    document.getElementById("session-timer").innerText =
                        String(hours).padStart(2, '0') + ":" +
                        String(minutes).padStart(2, '0') + ":" +
                        String(seconds).padStart(2, '0');
                }

                setInterval(updateSessionTimer, 1000);
                updateSessionTimer();
            </script>
        @endif
    </x-filament::card>
</x-filament-widgets::widget>
