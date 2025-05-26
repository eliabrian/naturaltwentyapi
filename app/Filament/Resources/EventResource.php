<?php

namespace App\Filament\Resources;

use App\Enums\EventStatus;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Event Management';

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
                                ->directory('event-images')
                                ->maxSize(10240)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->required(),
                        ])->collapsible(),
                ])
                ->columnSpan(2),

                Group::make([
                    Section::make('Relationship')
                        ->schema([
                            Select::make(name: 'room_id')
                                ->relationship('room', 'name')
                                ->preload()
                                ->searchable()
                                ->required(),
                        ]),
                    Section::make('Status')
                        ->schema([
                            Toggle::make('is_published')
                                ->helperText('This event will be hidden from the events page.')
                                ->label('Visible')
                                ->onColor('success')
                                ->onIcon('heroicon-o-eye')
                                ->offIcon('heroicon-o-eye-slash'),

                            ToggleButtons::make('status')
                                ->options(EventStatus::class)
                                ->inline()
                                ->default(EventStatus::New)
                                ->required(),

                            DateTimePicker::make('event_date')
                                ->label('Event Date')
                                ->seconds(false)
                                ->default(now())
                                ->native(false)
                                ->closeOnDateSelection()
                                ->timezone('Asia/Jakarta')
                                ->required()
                        ])
                ])
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')->label('Image'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('event_date')->dateTime('M d, Y - H:i')->label('Event Date')->sortable(),
                TextColumn::make('status')->badge(),
                IconColumn::make('is_published')->label('Visibility'),
            ])
            ->filters([
                SelectFilter::make('room_id')
                    ->relationship('room', 'name')
                    ->label('Room')
                    ->indicator('Room'),
                Filter::make('event_date')
                    ->form([
                        DateTimePicker::make('event_start')
                            ->label('Event from')
                            ->seconds(false),

                        DateTimePicker::make('event_end')
                            ->label('Event until')
                            ->seconds(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['event_start'] ?? null,
                                fn (Builder $query, $date): Builder => $query
                                    ->where('event_date', '>=', $date)
                            )
                            ->when(
                                $data['event_end'] ?? null,
                                fn (Builder $query, $date): Builder => $query
                                    ->where('event_date', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['event_start'] ?? null) {
                            $indicators['event_start'] = "Event from: " . Carbon::parse($data['event_start'])->format('M d, Y - H:i');
                        }

                        if ($data['event_end'] ?? null) {
                            $indicators['event_end'] = "Event until: " . Carbon::parse($data['event_end'])->format('M d, Y - H:i');
                        }

                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
                    ComponentsSection::make()
                        ->schema([
                            TextEntry::make('name'),
                            TextEntry::make('slug'),
                            TextEntry::make('status')
                                ->badge(),
                            TextEntry::make('room.name')
                                ->badge(),
                            TextEntry::make('description')
                                ->html()
                                ->columnSpan('full'),
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
                                ->alignCenter()
                        ]),

                    ComponentsSection::make('Status')
                        ->schema([
                            IconEntry::make('is_published')
                                ->boolean()
                                ->label('Visibility'),

                            TextEntry::make('event_date')->dateTime('M d, Y - H:i')
                        ])
                ])
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
