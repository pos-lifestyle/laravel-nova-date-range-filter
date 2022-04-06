<?php

namespace PosLifestyle\DateRangeFilter;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use PosLifestyle\DateRangeFilter\Enums\Config;

class DateRangeFilter extends Filter
{
    /**
     * @var string
     */
    public $component = 'date-range-filter';
    
    public function __construct( public $name = 'Created at', public string $column = Model::CREATED_AT, public array $config = [])
    {
        $this->configure();
    }
    
    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value): Builder
    {
        $query->whereBetween(
            $this->column,
            [
                Carbon::createFromFormat('Y-m-d', $value[0])->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $value[1])->endOfDay(),
            ]
        );
        
        return $query;
    }

    protected function configure(): void
    {
        if (empty($this->config)) {
            return;
        }

        foreach ($this->config as $property => $value) {
            if (!in_array($property, Config::getProperties(), true)) {
                throw new InvalidArgumentException('Invalid property: ' . $property);
            }

            $this->withMeta([$property => $value]);
        }
    }

    public function options(Request $request): array
    {
        return [];
    }

    public function key(): string
    {
        return parent::key() . '_' . $this->column;
    }

    /**
     * @return string[]
     */
    public function default(): array
    {
        return array_key_exists(Config::DEFAULT_DATE, $this->config)
            ? $this->config[Config::DEFAULT_DATE]
            : [];
    }
}
