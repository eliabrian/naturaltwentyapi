<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Enums\EventStatus;
use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'new' => Tab::make()->query(fn ($query) => $query->where('status', EventStatus::New->value)),
            'progress' => Tab::make('In Progress')->query(fn ($query) => $query->where('status', EventStatus::Progress->value)),
            'done' => Tab::make()->query(fn ($query) => $query->where('status', EventStatus::Done->value)),
            'cancelled' => Tab::make()->query(fn ($query) => $query->where('status', EventStatus::Cancelled->value)),
        ];
    }
}
