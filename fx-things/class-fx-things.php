<?php

defined('ABSPATH') || exit;

if (!class_exists('FX_Things')) {
    class FX_Things
    {
        private static $instance;
        private static $plugin_url = null;
        private static $plugin_path = null;
        public static $log_enabled = true;
        const DATE_FORMAT = 'n/j/Y';
        const DATE_FORMAT_ACF = 'Ymd';
        const DATE_FORMAT_REGEX = '/([\d]{1,2})\/([\d]{1,2})\/([\d]{4})/';
        private function __construct()
        {
            self::require();
            self::instantiate();
        }
        public static function get_instance()
        {
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        public static function get_plugin_url()
        {
            if (null === self::$plugin_url) {
                self::$plugin_url = trailingslashit(plugin_dir_url(__FILE__));;
            }
            return self::$plugin_url;
        }
        public static function get_plugin_path()
        {
            if (null === self::$plugin_path) {
                self::$plugin_path = trailingslashit(plugin_dir_path(__FILE__));
            }
            return self::$plugin_path;
        }
        private static function require()
        {
            self::require_files_in_dir(self::get_plugin_path() . 'includes/abstracts/');
            self::require_files_in_dir(self::get_plugin_path() . 'includes/models/');
            self::require_files_in_dir(self::get_plugin_path() . 'includes/controllers/');
            self::require_files_in_dir(self::get_plugin_path() . 'includes/shortcodes/');
            self::require_files_in_dir(self::get_plugin_path() . 'includes/helpers/');
        }
        private static function instantiate()
        {
            FX_Things\Controllers\Things::get_instance();
            FX_Things\Shortcodes\Birthdays_Weekly::get_instance();
        }
        public static function require_files_in_dir($dir)
        {
            $scanned_dir = array_diff(scandir($dir), array('..', '.'));
            foreach ($scanned_dir as $file) {
                require_once $dir . $file;
            }
        }
        public static function get_template($name, $args = array())
        {
            ob_start();
            include self::get_plugin_path() . 'templates/' . $name . '.php';
            return ob_get_clean();
        }
        public static function get_partial($name, $args = array())
        {
            ob_start();
            include self::get_plugin_path() . 'templates/partials/' . $name . '.php';
            return ob_get_clean();
        }
        public static function debug_log()
        {
            if (self::$log_enabled) {
                $log_location = self::get_plugin_path() . 'debug.log';
                $datetime = new DateTime('NOW');
                $timestamp = $datetime->format('Y-m-d H:i:s');
                $args = func_get_args();
                $formatted = array_map(function ($item) {
                    return print_r($item, true);
                }, $args);
                array_unshift($formatted, $timestamp);
                $joined = implode(' ', $formatted) . "\n";
                error_log($joined, 3, $log_location);
            }
        }
    }
}

function FX_Things()
{
    return FX_Things::get_instance();
}

FX_Things();
