<?php

namespace FX_Things\Abstracts;

use WP_Query;
use stdClass;

defined('ABSPATH') || exit;

abstract class Controller
{
    public static $model = '';
    protected function __construct()
    {
        self::add_wp_hooks();
    }
    private static function add_wp_hooks()
    {
        add_action('init', [static::class, 'register_post_type']);
        add_action('acf/init', [static::class, 'register_acf_fields']);
    }

    abstract public static function register_post_type();
    abstract public static function register_acf_fields();

    /**
     * Wrapper for fetching a WP_Query
     * @param 	array	$query_args 	Args for order query
     * @return stdClass Object with properties for $obj->wp_query to get WP_Query and $obj->models to get Models.
     */
    public static function get_query_obj(array $query_args = [])
    {
        $obj = new stdClass();
        $default_args = [
            'post_type' => static::$model::$POST_TYPE,
            'posts_per_page' => -1,
            'nopaging' => true,
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $args = wp_parse_args($query_args, $default_args);

        if (empty($args['meta_query'])) {
            $args['meta_query'] = array();
        }

        $obj->wp_query = new WP_Query($args);
        $obj->models =
            self::posts_to_models($obj->wp_query->posts);
        return $obj;
    }

    /**
     * @return array
     */
    public static function posts_to_models($posts)
    {
        return array_map(function ($post) {
            return self::post_to_model($post);
        }, $posts);
    }

    public static function post_to_model($post)
    {
        return new static::$model($post);
    }
}
