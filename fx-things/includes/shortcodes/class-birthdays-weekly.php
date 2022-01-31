<?php

namespace FX_Things\Shortcodes;

use FX_Things;
use FX_Things\Abstracts\Shortcode;
use FX_Things\Controllers\Things;
use FX_Things\Models\Thing;
use FX_Things\Helpers\Dates;
use DateTime;
use DateInterval;

defined('ABSPATH') || exit;

class Birthdays_Weekly extends Shortcode
{
    public static $tag = 'fxc_birthdays_weekly';
    public function callback($atts, $content, $name)
    {

        $query_model_obj = Things::get_by_birthdays_this_week();
        $binned_models = self::bin_things_by_birthday_weekday($query_model_obj->models);
        return FX_Things::get_partial('birthdays-weekly', array('query' => $query_model_obj->wp_query, 'binned_models' => $binned_models));
    }
    /**
     * @param Thing[] $things
     * @return array Associative array keyed by day of week. Each item is an array with 0 or more Thing instances.
     */
    public static function bin_things_by_birthday_weekday($things)
    {
        $start = Dates::find_start_of_week();
        $interval = new DateInterval('P6D');
        $end = (clone $start)->add($interval);
        $results = array_fill_keys(Dates::format_datetimes(Dates::build_datetime_day_range($start, $end), FX_Things::DATE_FORMAT), array());
        foreach ($things as $thing) {
            $date_of_birth = $thing->get_date_of_birth();
            if (!$date_of_birth) {
                continue;
            }
            foreach (array_keys($results) as $datetime_string) {
                $datetime = DateTime::createFromFormat(FX_Things::DATE_FORMAT, $datetime_string);
                if ($datetime->format('d') === $date_of_birth->format('d')) {
                    $results[$datetime_string][] = $thing;
                }
            }
        }
        return $results;
    }
}
