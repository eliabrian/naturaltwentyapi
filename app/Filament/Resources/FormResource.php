<?php

namespace App\Filament\Resources;

use App\Enums\FormStatus;
use App\Enums\OpnameShift;
use App\Enums\ProductLocation;
use App\Filament\Resources\FormResource\Pages;
use App\Models\Form as FormModel;
use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Store Management';

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->whereHas('user', function (Builder $query) use ($user) {
                $query->where('type', $user->type);
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Product Request Details')
                        ->schema([
                            TextInput::make('reference')
                                ->default('FP-' . random_int(100000, 999999))
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->maxLength(32)
                                ->unique(FormModel::class, 'reference', ignoreRecord: true),

                            DateTimePicker::make('form_date')
                                ->label('Product Request Date')
                                ->default(now())
                                ->required(),

                            Select::make('shift')
                                ->options(OpnameShift::class)
                                ->required(),
                        ])
                        ->columns(2),

                    Section::make('Product Request Items')
                        ->schema([
                            static::getProductsRepeater(),
                        ])
                ])->columnSpan(2),

                Group::make([
                    Section::make('Status')
                        ->schema([
                            ToggleButtons::make('status')
                                ->inline()
                                ->options(FormStatus::class)
                                ->required(),
                        ]),

                    Section::make('User')
                        ->schema([
                            Placeholder::make('created_by')
                                ->content(fn (FormModel $form): ?string => $form->user->name),

                            Placeholder::make('created_at')
                                ->content(fn (FormModel $form): ?string => $form->created_at),
                        ])
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
                TextColumn::make('form_date')
                    ->label('Request Date')->dateTime('l, d F Y'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            TextInput::make('reference')
                ->default('FP-' . random_int(100000, 999999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(FormModel::class, 'reference', ignoreRecord: true),

            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->default(Auth::user()->id)
                ->disabled()
                ->dehydrated(),

            DateTimePicker::make('form_date')
                ->label('Product Request Date')
                ->default(now())
                ->required(),

            ToggleButtons::make('status')
                ->inline()
                ->options(FormStatus::class)
                ->default(FormStatus::Draft)
                ->required(),

            Select::make('shift')
                ->options(OpnameShift::class)
                ->required(),
        ];
    }

    public static function getProductsRepeater(): Repeater
    {
        return Repeater::make('formProducts')
            ->relationship()
            ->schema([
                Select::make('product_id')
                    ->relationship(
                        name: 'product',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            $user = Auth::user();

                            if ($user?->isCashier()) {
                                return $query->where('location', '=', ProductLocation::Bar->value);
                            }

                            if ($user?->isChef()) {
                                return $query->where('location', '=', ProductLocation::Kitchen->value);
                            }

                            return $query;
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->afterStateUpdated(function ($state, Set $set) {
                        return $set('unit', Product::find($state)?->unit);
                    })
                    ->columnSpan('full'),

                TextInput::make('requested_quantity')
                    ->integer()
                    ->required(),

                Placeholder::make('unit')
                    ->label('Unit')
                    ->content(function ($get) {
                        $productId = $get('product_id');
                        return Product::find($productId)?->unit ?? '-';
                    }),

                TextInput::make('note')
                    ->label('Note'),

                Toggle::make('is_available')
                    ->label('Available')
                    ->inline(false)
                    ->hiddenOn('create')
                    ->onColor('success'),
            ])
            ->columns(2)
            ->columnSpan('full');
    }
}
