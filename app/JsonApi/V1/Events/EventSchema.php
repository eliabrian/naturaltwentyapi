<?php

namespace App\JsonApi\V1\Events;

use App\JsonApi\Filters\WhereMonth;
use App\JsonApi\Filters\WhereName;
use App\Models\Event;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class EventSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = Event::class;

    /**
     * The maximum include path depth.
     */
    protected int $maxDepth = 3;

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('name')->sortable(),
            Str::make('slug'),
            Str::make('image_path')->serializeUsing(static fn ($value) => asset($value)),
            Boolean::make('is_published'),
            Str::make('status'),
            Str::make('description'),
            BelongsTo::make('room')->readOnly(),
            DateTime::make('event_date')->sortable(),
            DateTime::make('created_at')->sortable()->readOnly(),
            DateTime::make('updated_at')->sortable()->readOnly(),
        ];
    }

    /**
     * Get the resource filters.
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            WhereName::make('name'),
            WhereMonth::make('event_date'),
            Where::make('status')->eq(),
            Where::make('is_published')->asBoolean(),
        ];
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
