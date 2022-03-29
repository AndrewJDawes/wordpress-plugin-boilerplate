<?php


namespace FX_Things\Abstracts;

use WP_Post;
use Exception;

defined('ABSPATH') || exit;

/**
 * Abstract Model
 * @package 	FX_Things
 * @category 	Abstracts
 * @author 		WebFX
 */
abstract class Model
{
    public static $POST_TYPE = '';
    private int $id;
    /**
     * @param int|WP_Post|null $post Post ID or Post Object
     */
    public function __construct($post)
    {
        if (is_numeric($post)) {
            $post = get_post(intval($post));
        }
        if (!self::is_valid($post)) {
            throw new Exception('A valid WP_Post instance or post_id must be passed to constructor');
        }
        $this->id = $post->ID;
    }

    /**
     * @param int|WP_Post|null $post Post ID or Post Object
     * @return bool
     */
    public static function is_valid($post)
    {
        if (is_numeric($post)) {
            $post = get_post(intval($post));
        }
        return $post instanceof WP_Post && static::$POST_TYPE === $post->post_type;
    }

    /**
     * @return int $id
     */
    public function get_id()
    {
        return $this->id;
    }


    abstract protected static function to_array_keys();
    abstract protected function to_array_values();

    /**
     * Used when cannot instantiate class but need properties as placeholders for templating.
     * @return array
     */
    public static function to_array_empty()
    {
        return array_fill_keys(static::to_array_keys(), '');
    }

    /**
     * Get object values for templating.
     * @return array
     */
    public function to_array()
    {
        $values = array_map(function ($value) {
            return $value ?? '';
        }, $this->to_array_values());

        return array_combine(static::to_array_keys(), $values);
    }
}
