<?php

namespace App\Filament\Resources;

use App\Enums\OpnameShift;
use App\Enums\OpnameStatus;
use App\Filament\Resources\OpnameResource\Pages;
use App\Filament\Resources\OpnameResource\Widgets\OpnameStats;
use App\Models\Opname;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OpnameResource extends Resource
{
    protected static ?string $model = Opname::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Store Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Opname Details')
                        ->schema([
                            TextInput::make('reference')
                                ->default('OP-'.random_int(100000, 999999))
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->maxLength(32)
                                ->unique(Opname::class, 'reference', ignoreRecord: true),

                            DateTimePicker::make('opname_date')
                                ->label('Opname Date')
                                ->default(now())
                                ->required(),

                            Select::make('shift')
                                ->options(OpnameShift::class)
                                ->required(),
                        ])
                        ->columns(2),

                    Section::make('Opname Items')
                        ->schema([
                            static::getProductsRepeater(),
                        ]),

                ])->columnSpan(2),

                Group::make([
                    Section::make('Status')
                        ->schema([
                            ToggleButtons::make('status')
                                ->inline()
                                ->options(OpnameStatus::class)
                                ->required(),
                        ]),

                    Section::make('User')
                        ->schema([
                            Placeholder::make('created_by')
                                ->content(fn (Opname $opname): ?string => $opname->user->name),

                            Placeholder::make('created_at')
                                ->content(fn (Opname $opname): ?string => $opname->created_at),
                        ]),
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('shift'),
                TextColumn::make('opname_date')
                    ->label('Opname Date')->dateTime('M d, Y - H:i:s'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(OpnameStatus::class),

                SelectFilter::make('opname_date')
                    ->label('Opname Date')
                    ->options([
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        'a_week' => 'Last 7 Days',
                        'a_month' => 'Last 30 Days',
                        'a_year' => 'Last 1 Year',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $now = Carbon::now();

                        return match ($data['value']) {
                            'today' => $query->whereDate('opname_date', $now->toDateString()),
                            'yesterday' => $query->whereDate('opname_date', $now->copy()->subDay()->toDateString()),
                            'a_week' => $query->where('opname_date', '>=', $now->copy()->subDays(7)),
                            'a_month' => $query->where('opname_date', '>=', $now->copy()->subDays(30)),
                            'a_year' => $query->where('opname_date', '>=', $now->copy()->subYear()),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListOpnames::route('/'),
            'create' => Pages\CreateOpname::route('/create'),
            'edit' => Pages\EditOpname::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            TextInput::make('reference')
                ->default('OP-'.random_int(100000, 999999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Opname::class, 'reference', ignoreRecord: true),

            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->default(Auth::user()->id)
                ->disabled()
                ->dehydrated(),

            DateTimePicker::make('opname_date')
                ->label('Opname Date')
                ->default(now())
                ->required(),

            ToggleButtons::make('status')
                ->inline()
                ->options(OpnameStatus::class)
                ->default(OpnameStatus::Draft)
                ->required(),

            Select::make('shift')
                ->options(OpnameShift::class)
                ->required(),
        ];
    }

    public static function getProductsRepeater(): Repeater
    {
        return Repeater::make('opnameProducts')
            ->relationship()
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->afterStateUpdated(function ($state, Set $set) {
                        return $set('system_quantity', Product::find($state)?->stock ?? 0);
                    }),

                TextInput::make('system_quantity')
                    ->integer()
                    ->disabled()
                    ->dehydrated()
                    ->label('System Quantity'),

                TextInput::make('counted_quantity')
                    ->integer()
                    ->live(onBlur: false, debounce: 500)
                    ->label('Counted Quantity')
                    ->disabled(function (Get $get) {
                        return empty($get('product_id'));
                    })
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $system = $get('system_quantity');

                        return $set('difference', $state - $system);
                    })
                    ->required(),

                TextInput::make('difference')
                    ->integer()
                    ->disabled()
                    ->dehydrated()
                    ->label('Difference')
                    ->required(),

                TextInput::make('note')
                    ->label('Note')
                    ->columnSpan('full'),
            ])
            ->columns(2)
            ->columnSpan('full');
    }

    public static function getWidgets(): array
    {
        return [
            OpnameStats::class,
        ];
    }
}
