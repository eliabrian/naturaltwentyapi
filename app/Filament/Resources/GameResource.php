<?php

namespace App\Filament\Resources;

use App\Enums\GameDifficulty;
use App\Filament\Resources\GameResource\Pages;
use App\Models\Game;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Game Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make()
                        ->schema([
                            TextInput::make(name: 'name')
                                ->live(onBlur: false, debounce: 500)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                    if (($get('slug') ?? '') !== Str::slug($old)) {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                })
                                ->required(),

                            TextInput::make(name: 'slug')
                                ->required(),

                            RichEditor::make('description')
                                ->required()
                                ->columnSpan(2),
                        ])
                        ->columns(2),

                    Section::make('Image')
                        ->schema([
                            FileUpload::make('image_path')
                                ->hiddenLabel()
                                ->image()
                                ->imageEditor()
                                ->preserveFilenames()
                                ->directory('game-images')
                                ->maxSize(10240)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->required(),
                        ])->collapsible(),

                    Section::make('Game Details')
                        ->schema([
                            TextInput::make('player_min')
                                ->label('Minimum Players')
                                ->suffix('players')
                                ->numeric()
                                ->required()
                                ->gt(0)
                                ->rules(rules: ['integer']),

                            TextInput::make('player_max')
                                ->label('Maximum Players')
                                ->suffix('players')
                                ->numeric()
                                ->required()
                                ->gt(0)
                                ->rules(rules: ['integer']),

                            TextInput::make('age')
                                ->label('Age (years)')
                                ->numeric()
                                ->required()
                                ->gt(0)
                                ->rules(rules: ['integer']),

                            TextInput::make('duration')
                                ->label('Duration')
                                ->suffix('minutes')
                                ->numeric()
                                ->required()
                                ->gt(0)
                                ->rules(rules: ['integer']),
                        ])
                        ->columns(2),
                ])
                    ->columnSpan(2),

                Group::make([
                    Section::make('Relationships')
                        ->schema([
                            Select::make(name: 'difficulty')
                                ->options(GameDifficulty::class)
                                ->searchable()
                                ->required(),

                            Select::make(name: 'tags')
                                ->relationship('tags', 'name')
                                ->preload()
                                ->multiple()
                                ->required(),
                        ]),

                    Section::make()
                        ->schema([
                            Placeholder::make('created_at')
                                ->label('Created At')
                                ->content(fn (Game $game): ?string => $game->created_at?->diffForHumans()),

                            Placeholder::make('updated_at')
                                ->label('Last Modified At')
                                ->content(fn (?Game $game): ?string => $game->updated_at?->diffForHumans()),
                        ])
                        ->hidden(fn (?Game $game) => empty($game->toArray())),
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ToggleColumn::make('is_available')->label('Status'),
                ImageColumn::make('image_path')->label('Image'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('difficulty')->badge(),
            ])
            ->filters([
                SelectFilter::make(name: 'difficulty')
                    ->options(GameDifficulty::class)
                    ->searchable(),

                SelectFilter::make(name: 'tags')
                    ->relationship('tags', 'name')
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->hidden(fn (?Game $game) => ! auth()->user()->can('delete', $game)),
            ])
            ->poll()
            ->deferLoading();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ComponentsGroup::make([
                    ComponentsSection::make()
                        ->schema([
                            TextEntry::make('name'),
                            TextEntry::make('slug'),
                            TextEntry::make('description')
                                ->html()
                                ->columnSpan('full'),
                        ])
                        ->columns(2),

                    ComponentsSection::make('Game Details')
                        ->schema([
                            TextEntry::make('player_min')
                                ->label('Minimum Player'),

                            TextEntry::make('player_max')
                                ->label('Minimum Player'),

                            TextEntry::make('age')
                                ->label('Age (years)'),

                            TextEntry::make('duration')
                                ->label('Duration (minutes)'),
                        ])
                        ->columns(2),
                ])
                    ->columnSpan(2),

                ComponentsGroup::make([
                    ComponentsSection::make('Image')
                        ->schema([
                            ImageEntry::make('image_path')
                                ->hiddenLabel()
                                ->extraImgAttributes([
                                    'loading' => 'lazy',
                                ])
                                ->alignCenter(),
                        ]),
                    ComponentsSection::make('Relationships')
                        ->schema([
                            TextEntry::make('difficulty')
                                ->badge(),
                            TextEntry::make('tags.name')
                                ->badge(),
                        ]),

                    ComponentsSection::make()
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Created At')
                                ->since(),

                            TextEntry::make('updated_at')
                                ->label('Last Modified At')
                                ->since(),
                        ]),
                ]),
            ])
            ->columns(3);
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
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
            'view' => Pages\ViewGame::route('/{record}'),
        ];
    }
}
