<?php

namespace FX_Things\Abstracts;

defined('ABSPATH') || exit;

abstract class Shortcode
{
    private static $instance;
    public static $tag = '';
    private function __construct()
    {
        $this->register_shortcode();
    }
    public static function get_instance()
    {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * @return string
     */
    public static function get_tag()
    {
        return static::$tag;
    }
    public function register_shortcode()
    {
        add_shortcode(self::get_tag(), [$this, 'callback']);
    }
    abstract public function callback($atts, $content, $name);
}
