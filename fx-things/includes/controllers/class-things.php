<?php

namespace FX_Things\Controllers;

use DateInterval;
use FX_Things\Abstracts\Controller;
use FX_Things\Traits\SingletonTrait;
use FX_Things\Models\Thing;
use FX_Things\Helpers\Dates;
use DateTime;
use FX_Things;
use stdClass;

defined('ABSPATH') || exit;

class Things extends Controller
{
    use SingletonTrait;
    public static $model = Thing::class;
    protected function __construct()
    {
        parent::__construct();
        self::add_wp_hooks();
    }
    private static function add_wp_hooks()
    {
        add_filter('pmxi_custom_field', [self::class, 'pmxi_custom_field'], 10, 6);
    }
    public static function register_post_type()
    {
        $labels = array(
            'name'                  => _x('Things', 'Post type general name', 'textdomain'),
            'singular_name'         => _x('Thing', 'Post type singular name', 'textdomain'),
            'menu_name'             => _x('Things', 'Admin Menu text', 'textdomain'),
            'name_admin_bar'        => _x('Thing', 'Add New on Toolbar', 'textdomain'),
            'add_new'               => __('Add New', 'textdomain'),
            'add_new_item'          => __('Add New Thing', 'textdomain'),
            'new_item'              => __('New Thing', 'textdomain'),
            'edit_item'             => __('Edit Thing', 'textdomain'),
            'view_item'             => __('View Thing', 'textdomain'),
            'all_items'             => __('All Things', 'textdomain'),
            'search_items'          => __('Search Things', 'textdomain'),
            'parent_item_colon'     => __('Parent Things:', 'textdomain'),
            'not_found'             => __('No things found.', 'textdomain'),
            'not_found_in_trash'    => __('No things found in Trash.', 'textdomain'),
            'featured_image'        => _x('Thing Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'archives'              => _x('Thing archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
            'insert_into_item'      => _x('Insert into thing', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
            'uploaded_to_this_item' => _x('Uploaded to this thing', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
            'filter_items_list'     => _x('Filter things list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
            'items_list_navigation' => _x('Things list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
            'items_list'            => _x('Things list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'rewrite'            => array('slug' => 'thing'),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        );
        /**
         * @link https://stackoverflow.com/a/1279124
         */
        register_post_type(self::$model::$POST_TYPE, $args);
    }

    public static function register_acf_fields()
    {

        if (function_exists('acf_add_local_field_group')) :

            acf_add_local_field_group(array(
                'key' => 'group_61f41d53e3df5',
                'title' => 'Thing Info',
                'fields' => array(
                    array(
                        'key' => 'field_61f4632c7c52c',
                        'label' => 'First Name',
                        'name' => self::$model::$acf_key_name_first,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_61f463447c52d',
                        'label' => 'Last Name',
                        'name' => self::$model::$acf_key_name_last,
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_61f41d95f0ce7',
                        'label' => 'Date of Birth',
                        'name' => self::$model::$acf_key_date_of_birth,
                        'type' => 'date_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'display_format' => FX_Things::DATE_FORMAT,
                        'return_format' => FX_Things::DATE_FORMAT,
                        'first_day' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => self::$model::$POST_TYPE,
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));

        endif;
    }
    /**
     * @see wp-content/plugins/wp-all-import-pro/models/import/record.php::process
     */
    public static function pmxi_custom_field($value, $pid, $m_key, $cf_original_value, $existing_meta_keys, $id)
    {
        switch ($m_key) {
            case self::$model::$acf_key_date_of_birth:
                $value = self::pmxi_custom_field_date_of_birth($value, $pid, $m_key, $cf_original_value, $existing_meta_keys, $id);
                break;
            default:
                break;
        }
        return $value;
    }
    /**
     * @see wp-content/plugins/wp-all-import-pro/models/import/record.php::process
     */
    public static function pmxi_custom_field_date_of_birth($value, $pid, $m_key, $cf_original_value, $existing_meta_keys, $id)
    {
        return Dates::parse_raw_date_to_acf_format($value, FX_Things::DATE_FORMAT, FX_Things::DATE_FORMAT_REGEX); // Returns empty string if can't parse.
    }

    /**
     * Birthday is NOT to be confused with Date of Birth.
     * @param DateTime $start
     * @param DateTime $end
     * @return stdClass Object with properties for $obj->wp_query to get WP_Query and $obj->models to get Models.
     */
    public static function get_by_birthday_range($start, $end)
    {
        // Return Format
        // Build RegEx from DateTimes.
        $date_range = Dates::build_datetime_day_range($start, $end);
        $formatted_range = Dates::format_datetimes($date_range, 'md'); // ACF is in Ymd format - we just want to drop year entirely and compare against the end.
        $regex = implode('|', $formatted_range) . '$';
        $args = array();
        // Please note that the value is always saved as Ymd (YYYYMMDD) in the database.
        $args['meta_query'] = array(
            'relation' => 'AND',
            array(
                'key'     => self::$model::$acf_key_date_of_birth,
                'compare' => '<=',
                'value'   => $end->format(FX_Things::DATE_FORMAT_ACF),
            ),
            array(
                'compare' => 'REGEXP',
                'key' => self::$model::$acf_key_date_of_birth,
                'value' => $regex,
            )
        );
        return self::get_query_obj($args);
    }

    /**
     * @return stdClass Object with properties for $obj->wp_query to get WP_Query and $obj->models to get Models.
     */
    public static function get_by_birthdays_this_week()
    {
        $start = Dates::find_start_of_week();
        $interval = new DateInterval('P6D');
        $end = (clone $start)->add($interval);
        return self::get_by_birthday_range($start, $end);
    }
}
