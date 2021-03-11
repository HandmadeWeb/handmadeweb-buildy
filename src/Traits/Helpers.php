<?php

namespace HandmadeWeb\Buildy\Traits;

trait Helpers
{
    public static function isPageBuilderEnabled(): bool
    {
        /*
        * Check if current page is in the Wordpress Admin section.
        * Check to see if the current screen is the general pages overview/list.
        * If it is, cancel out as we do not want to load the builder.
        */
        if (is_admin()) {
            $screen = get_current_screen();
            if (! empty($screen->base) && $screen->base === 'edit') {
                return false;
            }
        }

        /*
        * Check to see if PageBuilder is enabled
        */

        if (function_exists('get_field') && get_field('BMCB_use_PageBuilder')) {
            if (is_admin()) {
                wp_enqueue_editor();
            }

            return true;
        }

        return false;
    }

    public static function locationExistsOrCreate(string $location): bool
    {
        return is_dir($location) ?: mkdir($location, 0755, true);
    }

    public static function seoUrl($string): string
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", '', $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", ' ', $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", '-', $string);

        return $string;
    }
}
