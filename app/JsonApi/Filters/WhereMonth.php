<?php

namespace App\JsonApi\Filters;

use Carbon\Carbon;
use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\IsSingular;

class WhereMonth implements Filter
{
    use DeserializesValue;
    use IsSingular;

    /**
     * @var string
     */
    private string $name;

    /**
     * Create a new filter.
     *
     * @param string $name
     * @param string|null $column
     * @return WhereMonth
     */
    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * WhereMonth constructor.
     *
     * @param string $name
     * @param string|null $column
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->name;
    }

    /**
     * Apply the filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query, $value): \Illuminate\Database\Eloquent\Builder
    {
        $value = $this->deserialize($value);
        $value = Carbon::make($value)->format('m');

        return $query->whereMonth($this->name, $value);
    }
}
