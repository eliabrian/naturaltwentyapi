<?php

namespace App\JsonApi\Filters;

use Carbon\Carbon;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\IsSingular;

class WhereMonth implements Filter
{
    use DeserializesValue;
    use IsSingular;

    private string $name;

    /**
     * Create a new filter.
     *
     * @param  string|null  $column
     */
    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * WhereMonth constructor.
     *
     * @param  string|null  $column
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the key for the filter.
     */
    public function key(): string
    {
        return $this->name;
    }

    /**
     * Apply the filter to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     */
    public function apply($query, $value): \Illuminate\Database\Eloquent\Builder
    {
        $value = $this->deserialize($value);
        $value = Carbon::make($value)->format('m');

        return $query->whereMonth($this->name, $value);
    }
}
