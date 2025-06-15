<?php

namespace App\Filament\Resources;

use App\Enums\ProductLocation;
use App\Enums\ProductType;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Widgets\ProductStats;
use App\Models\Product;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-m-squares-2x2';

    protected static ?string $navigationGroup = 'Store Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make()
                        ->schema([
                            TextInput::make('name')->required(),

                            RichEditor::make('note'),
                        ]),

                    Section::make('Pricing')
                        ->schema([
                            TextInput::make('price')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->integer()
                                ->prefix('Rp')
                                ->required()
                                ->columnSpan('full'),
                        ])
                        ->columns(2),

                    Section::make('Inventory')
                        ->schema([
                            TextInput::make('sku')
                                ->label('SKU (Stock Keeping Unit)')
                                ->required(),

                            TextInput::make('unit')->required(),

                            TextInput::make('stock')
                                ->integer()
                                ->required(),

                            TextInput::make('security_stock')
                                ->label('Security Stock')
                                ->hintIcon('heroicon-m-question-mark-circle', 'The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.')
                                ->integer()
                                ->required(),
                        ])
                        ->columns(2),
                ])
                    ->columnSpan(2),

                Group::make([
                    Section::make('Relationship')
                        ->schema([
                            Select::make('type')
                                ->options(ProductType::class),

                            Select::make('location')
                                ->options(ProductLocation::class),

                            Select::make('supplier_id')
                                ->relationship('supplier', 'name')
                                ->preload()
                                ->searchable()
                                ->required(),
                        ]),
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('location')->badge(),
                TextColumn::make('stock')
                    ->badge()
                    ->color(function ($record, $state) {
                        switch ($state) {
                            case $state == $record->security_stock:
                                return 'warning';
                                break;

                            case $state < $record->security_stock:
                                return 'danger';
                                break;

                            default:
                                return 'success';
                                break;
                        }
                    }),
                TextColumn::make('unit'),
            ])
            ->groups([
                'type',
                'supplier.name',
            ])
            ->filters([
                SelectFilter::make('location')
                    ->options(ProductLocation::class),

                SelectFilter::make('stock_alert')
                    ->label('Stock Level')
                    ->options([
                        'low' => 'Low or Critical Stock',
                    ])
                    ->query(function (Builder $query, $state) {
                        if ($state['value'] === 'low') {
                            return $query->whereColumn('stock', '<=', 'security_stock');
                        }

                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }
}
