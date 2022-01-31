<?php


namespace FX_Things\Models;

use FX_Things\Abstracts\Model;
use DateTime;
use FX_Things;
use WP_Error;

defined('ABSPATH') || exit;


/**
 * Thing
 * 
 * Model for dealing with common information for an individual Thing
 *
 * @package 	FX_Things
 * @category 	Models
 * @author 		WebFX
 */
class Thing extends Model
{
    public static $POST_TYPE = 'fx_thing';
    public static $acf_key_date_of_birth = 'fxc_date_of_birth';
    public static $acf_key_name_first = 'fxc_name_first';
    public static $acf_key_name_last = 'fxc_name_last';

    public function get_name_first()
    {
        if (!property_exists($this, 'name_first')) {
            $raw = get_field(self::$acf_key_name_first, $this->get_id(), false);
            $this->name_first = empty($raw) ? null : $raw;
        }
        return $this->name_first;
    }

    public function set_name_first($name)
    {
        if (!update_field(self::$acf_key_name_first, $name, $this->get_id())) {
            return false;
        }
        $this->name_first = $name;
        $this->update_title();
        return true;
    }

    public function get_name_last()
    {
        if (!property_exists($this, 'name_last')) {
            $raw = get_field(self::$acf_key_name_last, $this->get_id(), false);
            $this->name_last = empty($raw) ? null : $raw;
        }
        return $this->name_last;
    }

    public function set_name_last($name)
    {
        if (!update_field(self::$acf_key_name_last, $name, $this->get_id())) {
            return false;
        }
        $this->name_last = $name;
        $this->update_title();
        return true;
    }

    /**
     * @return string
     */
    public function get_name_full()
    {
        return ((string) $this->get_name_first()) . ' ' . ((string) $this->get_name_last());
    }

    /**
     * @return int|WP_Error
     */
    private function update_title()
    {
        return wp_update_post(array('ID' => $this->get_id(), 'post_title' => trim($this->get_name_full())));
    }

    /**
     * @return DateTime|null
     */
    public function get_date_of_birth()
    {
        if (!property_exists($this, 'date_of_birth')) {
            $raw = get_field(self::$acf_key_date_of_birth, $this->get_id(), false);
            $this->date_of_birth =
                DateTime::createFromFormat(FX_Things::DATE_FORMAT_ACF, $raw) ?: null;
        }
        return clone $this->date_of_birth;
    }

    public function set_date_of_birth()
    {
    }

    /**
     * @return int|null
     */
    public function get_age_years()
    {
        $date_of_birth = $this->get_date_of_birth();
        if (null === $date_of_birth) {
            return null;
        }
        $today = new DateTime('now');
        $diff = $date_of_birth->diff($today);
        if ($diff->invert) {
            return null;
        }
        return $diff->y;
    }

    /**
     * Internal method used to define keys for to_array methods.
     * @return array
     */
    protected static function to_array_keys()
    {
        return array(
            'id',
            'name_full',
            'date_of_birth',
        );
    }

    /**
     * Internal method used to define values for to_array methods.
     * @return array
     */
    protected function to_array_values()
    {
        return array(
            $this->get_id(),
            $this->get_name_full(),
            $this->get_date_of_birth(),
        );
    }
}
