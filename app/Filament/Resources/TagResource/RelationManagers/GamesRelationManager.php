<?php

namespace App\Filament\Resources\TagResource\RelationManagers;

use App\Filament\Resources\GameResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GamesRelationManager extends RelationManager
{
    protected static string $relationship = 'games';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return GameResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image_path')->label('Image'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('difficulty')->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
