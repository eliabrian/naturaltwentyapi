<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'User Management';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->sortable()
                    ->date(),

                TextColumn::make('first_started_at')
                    ->label('First Start')
                    ->dateTime('H:i:s')
                    ->color(fn ($record) => $record->first_started_at?->gt(Carbon::createFromTime(9, 0)) ? 'danger' : 'success'),

                TextColumn::make('formatted_duration')
                    ->label('Total Time')
                    ->badge()
                    ->color(fn ($record) => $record->total_duration >= 4 * 3600 ? 'success' : 'warning'),

                TextColumn::make('segments_count')
                    ->counts('segments')
                    ->label('Sessions'),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User'),

                Filter::make('today')
                    ->query(fn ($query) => $query->whereDate('date', today()))
                    ->label('Today'),

                Filter::make('this_week')
                    ->query(fn ($query) => $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]))
                    ->label('This Week'),
            ])
            ->defaultSort('date', 'desc')
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
        ];
    }
}
