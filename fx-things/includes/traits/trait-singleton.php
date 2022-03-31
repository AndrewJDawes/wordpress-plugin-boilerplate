<?php
namespace FX_Things\Traits;
trait SingletonTrait {
    private static $instance;
    public static function get_instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    protected function __construct() { }
    protected function __clone() { }
    protected function __sleep() { }
    protected function __wakeup() { }
}