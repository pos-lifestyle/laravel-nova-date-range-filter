<?php declare(strict_types=1);

namespace PosLifestyle\DateRangeFilter;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Laravel\Nova\Filters\Filter;
use PosLifestyle\DateRangeFilter\Enums\Config;

class DateRangeFilter extends Filter
{
    /**
     * @var string
     */
    public $component = 'date-range-filter';

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    protected $column;

    public function __construct(string $name, string $column = 'created_at', array $config = [])
    {
        $this->name = $name;
        $this->column = $column;

        $this->configure($config);
    }

    /**
     * @param Request $request
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $query->whereBetween(
            $this->column,
            [
                Carbon::parse($value[0]),
                Carbon::parse($value[1]),
            ]
        );

        return $query;
    }

    protected function configure(array $config): void
    {
        $this->withMeta([Config::LOCALE => config('app.locale')]);

        if (empty($config)) {
            return;
        }

        foreach ($config as $property => $value) {
            if (!in_array($property, Config::properties(), true)) {
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
}
