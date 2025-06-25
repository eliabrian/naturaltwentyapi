<?php

namespace App\Filament\Resources;

use App\Enums\UserType;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->searchable()->sortable(),
                TextColumn::make('user.type')->badge()->label('Type'),
                TextColumn::make('group_date')->label('Date')->sortable()
                    ->summarize(
                        Summarizer::make()
                            ->label('Total Days')
                            ->using(function (QueryBuilder $query) {
                                return $query->count('ended_at');
                            })
                    ),
                TextColumn::make('started_at')->dateTime('H:i:s')->color(function ($record) {
                    $date = Carbon::parse($record->started_at);
                    $max = Carbon::create($date)->setTime(9, 0, 0);

                    return $date->gt($max) ? 'danger' : 'success';
                }),
                TextColumn::make('ended_at')->dateTime('H:i:s'),
                TextColumn::make('duration')
                    ->formatStateUsing(function ($state) {
                        $hours = floor($state / 3600);
                        $minutes = floor(($state % 3600) / 60);
                        $seconds = $state % 60;

                        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                    })
                    ->summarize(
                        Summarizer::make()
                            ->label('Total Duration')
                            ->using(function (QueryBuilder $query) {
                                $totalDurationInSecods = $query->sum('duration');
                                $hours = floor($totalDurationInSecods / 3600);
                                $minutes = floor(($totalDurationInSecods % 3600) / 60);
                                $seconds = $totalDurationInSecods % 60;
                                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                            })
                    )
            ])
            ->filters([
                SelectFilter::make('user.name')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('user_type')
                    ->options(UserType::class)
                    ->searchable()
                    ->query(function (Builder $query, $state) {
                        if ($state['value']) {
                            $query->whereHas('user', function ($q) use ($state) {
                                $q->where('type', $state['value']);
                            });
                        }
                    }),

                Filter::make('attendance_date')
                    ->form([
                        DatePicker::make('attendance_start')
                            ->native(false)
                            ->label('Attendance from')
                            ->closeOnDateSelection(),

                        DatePicker::make('attendance_end')
                            ->native(false)
                            ->label('Attendance until')
                            ->closeOnDateSelection(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['attendance_start'] ?? null,
                                fn (Builder $query, $date): Builder => $query
                                    ->where('started_at', '>=', Carbon::parse($date)->setTime(0, 0, 0))
                            )
                            ->when(
                                $data['attendance_end'] ?? null,
                                fn (Builder $query, $date): Builder => $query
                                    ->where('started_at', '<=', Carbon::parse($date)->setTime(23, 59, 59))
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['attendance_start'] ?? null) {
                            $indicators['attendance_start'] = 'Attendance from: '.Carbon::parse($data['attendance_start'])->format('M d, Y');
                        }

                        if ($data['attendance_end'] ?? null) {
                            $indicators['attendance_end'] = 'Attendance until: '.Carbon::parse($data['attendance_end'])->format('M d, Y');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc');
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
