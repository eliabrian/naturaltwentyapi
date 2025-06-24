<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Store Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Supplier Details')
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('phone')
                            ->tel(),

                        TextInput::make('email')
                            ->email(),

                        TextInput::make('address'),

                        RichEditor::make('note')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Section::make('Bank Details')
                    ->schema([
                        TextInput::make('bank_account')
                            ->label('Bank'),

                        TextInput::make('account_name')
                            ->label('Account Name'),

                        TextInput::make('account_number')
                            ->label('Account Number'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->searchable()
                    ->default('-'),

                TextColumn::make('email')
                    ->searchable()
                    ->default('-'),
            ])
            ->filters([
                //
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ComponentsGroup::make([
                    ComponentsSection::make('Supplier Details')
                        ->schema([
                            TextEntry::make('name'),
                            TextEntry::make('phone')->default('-'),
                            TextEntry::make('email')->default('-'),
                            TextEntry::make('address')->default('-'),
                        ]),
                ])
                    ->columnSpan(2),

                ComponentsGroup::make([
                    ComponentsSection::make('Bank Details')
                        ->schema([
                            TextEntry::make('bank_account')->default('-'),
                            TextEntry::make('account_name')->default('-'),
                            TextEntry::make('account_number')->default('-'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
