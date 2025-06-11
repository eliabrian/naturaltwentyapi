<?php

namespace App\Filament\Resources\OpnameResource\Pages;

use App\Enums\OpnameStatus;
use App\Filament\Resources\OpnameResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOpnames extends ListRecords
{
    protected static string $resource = OpnameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return OpnameResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'draft' => Tab::make()->query(fn ($query) => $query->where('status', OpnameStatus::Draft->value)),
            'awaiting_review' => Tab::make('Awaiting Review')->query(fn ($query) => $query->where('status', OpnameStatus::Awating_Review->value)),
            'under_review' => Tab::make('Under Review')->query(fn ($query) => $query->where('status', OpnameStatus::Under_Review->value)),
            'approved' => Tab::make()->query(fn ($query) => $query->where('status', OpnameStatus::Approved->value)),
        ];
    }
}
