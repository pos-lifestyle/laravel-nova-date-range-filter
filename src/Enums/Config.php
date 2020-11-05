<?php declare (strict_types=1);

namespace PosLifestyle\DateRangeFilter\Enums;

class Config
{
    public const ALLOW_INPUT = 'allowInput';
    public const DATE_FORMAT = 'dateFormat';
    public const DEFAULT_DATE = 'defaultDate';
    public const DISABLED = 'disabled';
    public const ENABLE_TIME = 'enableTime';
    public const ENABLE_SECONDS = 'enableSeconds';
    public const FIRST_DAY_OF_WEEK = 'firstDayOfWeek';
    public const LOCALE = 'locale';
    public const MAX_DATE = 'maxDate';
    public const MIN_DATE = 'minDate';
    public const PLACEHOLDER = 'placeholder';
    public const SHORTHAND_CURRENT_MONTH = 'shorthandCurrentMonth';
    public const SHOW_MONTHS = 'showMonths';
    public const TIME24HR = 'time24hr';
    public const WEEK_NUMBERS = 'weekNumbers';

    /**
     * @return string[]
     */
    public static function getProperties(): array
    {
        return [
            self::ALLOW_INPUT,
            self::DATE_FORMAT,
            self::DEFAULT_DATE,
            self::DISABLED,
            self::ENABLE_TIME,
            self::ENABLE_SECONDS,
            self::FIRST_DAY_OF_WEEK,
            self::LOCALE,
            self::MAX_DATE,
            self::MIN_DATE,
            self::PLACEHOLDER,
            self::SHORTHAND_CURRENT_MONTH,
            self::SHOW_MONTHS,
            self::TIME24HR,
            self::WEEK_NUMBERS,
        ];
    }
}
