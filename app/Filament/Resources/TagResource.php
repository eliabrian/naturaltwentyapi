<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers\GamesRelationManager;
use App\Models\Tag;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Game Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema(components: [
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

                        ColorPicker::make('color')
                            ->required(),
                    ])->columns(columns: 2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'name')->searchable(),
                TextColumn::make(name: 'slug'),
                ColorColumn::make(name: 'color'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
                    ->hidden(fn (?Tag $tag) => ! auth()->user()->can('delete', $tag)),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GamesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
