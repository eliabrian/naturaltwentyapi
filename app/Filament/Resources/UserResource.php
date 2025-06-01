<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                TextInput::make(name: 'name')
                    ->required(),

                TextInput::make(name: 'email')
                    ->label('Email Address')
                    ->required()
                    ->email(),

                Select::make(name: 'role_id')
                    ->options(UserRole::class)
                    ->searchable()
                    ->label('Role')
                    ->required()
                    ->exists(table: 'roles', column: 'id'),

                TextInput::make(name: 'password')
                    ->required(condition: fn (string $context): bool => $context === 'create')
                    ->password()
                    ->revealable()
                    ->confirmed()
                    ->dehydrateStateUsing(
                        callback: fn ($state): string => Hash::make(value: $state)
                    )
                    ->dehydrated(
                        condition: fn ($state): bool => filled(value: $state)
                    ),

                TextInput::make(name: 'password_confirmation')
                    ->password()
                    ->revealable(),
            ])
            ->columns(columns: 1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make(name: 'name')
                    ->searchable(),
                TextColumn::make(name: 'email')
                    ->searchable(),
                TextColumn::make(name: 'role_id')
                    ->badge()
                    ->label('Role'),
            ])
            ->filters(filters: [
                SelectFilter::make(name: 'role_id')
                    ->options(UserRole::class)
                    ->label('Role'),
            ])
            ->actions(actions: [
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions(actions: [
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
                    ->hidden(fn (?User $user) => ! auth()->user()->can('delete', $user)),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(components: [
                TextEntry::make('name'),

                TextEntry::make('email')
                    ->label('Email Address'),

                TextEntry::make(name: 'role_id')
                    ->badge()
                    ->label('Role'),

                TextEntry::make('created_at')
                    ->label('Joined At')
                    ->date(),
            ])
            ->columns(columns: 1);
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
            'index' => Pages\ListUsers::route('/'),
            // 'view' => Pages\ViewUser::route('/{record}')
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
