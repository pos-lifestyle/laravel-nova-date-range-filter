<?php declare(strict_types=1);

namespace PosLifestyle\DateRangeFilter;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * @var array
     */
    protected $config;

    public function __construct(string $name = 'Created at', string $column = Model::CREATED_AT, array $config = [])
    {
        $this->name = $name;
        $this->column = $column;
        $this->config = $config;

        $this->configure();
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
                $value[0],
                $value[1],
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
            if ($property == 'dateFormat') {
                $jsDateFormat = $this->convertPHPToMomentFormat($value);
                $this->withMeta(['jsDateFormat' => $jsDateFormat]);
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
    public function default()
    {
        return array_key_exists(Config::DEFAULT_DATE, $this->config)
            ? $this->config[Config::DEFAULT_DATE]
            : [];
    }

    function convertPHPToMomentFormat($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];
        $momentFormat = strtr($format, $replacements);
        return $momentFormat;
    }
}
