<?php

namespace App\Filament\Resources\GameResource\Pages;

use App\Filament\Resources\GameResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListGames extends ListRecords
{
    protected static string $resource = GameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Listen for browser events dispatched by the JavaScript
     */
    #[On('game-availability-changed')]
    public function handleGameAvailabilityChanged($game, $available): void
    {
        $title = $available ? 'Game Available' : 'Game Unavailable';
        $body = $available
            ? "$game is now available"
            : "$game is no longer available";
        $color = $available ? 'success' : 'warning';

        Notification::make()
            ->title($title)
            ->body($body)
            ->color($color)
            ->duration(5000)
            ->send();

        // Refresh the table to show updated data
        $this->resetTable();
    }
}
