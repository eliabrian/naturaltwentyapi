<?php

namespace App\Filament\Widgets;

use App\Enums\EventStatus;
use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestEvents extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->heading("Today's Event")
            ->query(Event::whereToday('event_date'))
            ->defaultPaginationPageOption(5)
            ->columns([
                ImageColumn::make('image_path')->label('Image'),
                TextColumn::make('name'),
                TextColumn::make('event_date')->dateTime('M d, Y - H:i')->label('Event Date')->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(EventStatus::class),

                SelectFilter::make('room_id')
                    ->relationship('room', 'name')
                    ->label('Room')
                    ->indicator('Room'),
            ])
            ->actions([
                Action::make('open')
                    ->url(fn (Event $event): string => EventResource::getUrl('edit', ['record' => $event]))
                    ->icon('heroicon-s-arrow-top-right-on-square'),
            ]);
    }
}
