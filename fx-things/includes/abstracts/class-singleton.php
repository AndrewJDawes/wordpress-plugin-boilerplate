<?php

namespace FX_Things\Abstracts;

defined('ABSPATH') || exit;

abstract class Singleton
{
    public static function get_instance()
    {
        // https://stackoverflow.com/a/47424729
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }
}
