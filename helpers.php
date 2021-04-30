<?php

if (! function_exists('isPageBuilderEnabled')) {
    /**
     * Undocumented function.
     *
     * @return bool
     */
    function isPageBuilderEnabled(): bool
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
}
