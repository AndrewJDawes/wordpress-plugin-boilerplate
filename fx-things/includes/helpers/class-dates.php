<?php

namespace FX_Things\Helpers;

use FX_Things;
use FX_Things\Traits\SingletonTrait;
use DateTime;
use DateInterval;
use DatePeriod;

defined('ABSPATH') || exit;

class Dates
{
    use SingletonTrait;
    public static function get_weekdays()
    {
        return [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];
    }
    /**
     * @param string $starts_on Day of week a calendar week should start on
     * @return DateTime
     */
    public static function find_start_of_week($starts_on = 'Sunday')
    {
        $starts_on = ucwords(strtolower($starts_on));
        $date = new DateTime('now');
        if ($date->format('l') !== $starts_on) {
            $date->modify("last $starts_on");
        }
        return $date;
    }
    /**
     * @param DateTime $start
     * @param DateTime $end Inclusive
     */
    public static function build_datetime_day_range($start, $end)
    {
        $range = [];
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($start, $interval, (clone $end)->add($interval)); // Make end inclusive.

        foreach ($daterange as $date) {
            $range[] = $date;
        }

        return $range;
    }
    /**
     * @param DateTime[] $datetimes_array Array of datetimes to format
     * @param string|null $format Format string to use to convert DateTimes. Defaults to this package's default format.
     * @return string[] Array of datetimes converted to strings
     */
    public static function format_datetimes($datetimes_array, $format = null)
    {
        if (null === $format) {
            $format = FX_Things::DATE_FORMAT;
        }
        return array_map(function ($datetime) use ($format) {
            return $datetime->format($format);
        }, $datetimes_array);
    }

    /**
     * @param string $raw_date
     * @param string $input_format DateTime format string used to parse raw date. Defaults to FX_Things::DATE_FORMAT_ACF
     * @param string $validation_regex RegEx used to extract month, day, and year from raw string to run a checkdate() call to make sure it's a valid Gregorian date. Must contain 3 capture groups. Defaults to FX_Things::DATE_FORMAT_REGEX.
     * @return string Formatted string ready to save to ACF. Empty string '' if invalid date.
     */
    public static function parse_raw_date_to_acf_format($raw_date, $input_format = null, $validation_regex = null)
    {
        if (null === $validation_regex) {
            $validation_regex = FX_Things::DATE_FORMAT_REGEX;
        }
        $date_parts_parsed = self::parse_raw_date_to_date_parts($raw_date, $validation_regex);
        if (false === $date_parts_parsed) {
            return ''; // If can't parse the date value, just return empty string.
        }
        list($month, $day, $year) = $date_parts_parsed;
        if (false === checkdate($month, $day, $year)) {
            return ''; // If can't parse the date value, just return empty string.
        }
        if (null === $input_format) {
            $input_format = FX_Things::DATE_FORMAT;
        }
        $datetime = \DateTime::createFromFormat($input_format, $raw_date);
        return $datetime ? $datetime->format(FX_Things::DATE_FORMAT_ACF) : '';
    }

    /**
     * @param string $raw_date
     * @param string $regex for parsing
     * @return int[]|false Array with 3 elements for month, day, in order as controlled by regex.
     */
    public static function parse_raw_date_to_date_parts($raw_date, $regex = null)
    {
        if (null === $regex) {
            $regex = FX_Things::DATE_FORMAT_REGEX;
        }
        preg_match($regex, $raw_date, $matches);
        if (!(is_array($matches) && 4 === count($matches))) {
            return false;
        }
        return array_map(function ($item) {
            return intval($item);
        }, array_slice($matches, 1));
    }
}
