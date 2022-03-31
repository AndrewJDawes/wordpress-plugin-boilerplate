<?php

namespace FX_Things\Abstracts;

defined('ABSPATH') || exit;

abstract class Shortcode
{
    public static $tag = '';
    protected function __construct()
    {
        $this->register_shortcode();
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
