<?php

namespace HandmadeWeb\Buildy\Frontend;

use HandmadeWeb\Buildy\Buildy;
use HandmadeWeb\Illuminate\Filter;

class FrontendLoader
{
    protected static $page_results = [];

    public static function boot()
    {
        add_action('wp_enqueue_scripts', [static::class, 'wp_enqueue_scripts']);
        add_filter('the_content', [static::class, 'the_content']);

        Filter::add('illuminate_blade_view_paths', [static::class, 'bladeViewPaths'], 1);
    }

    public static function bladeViewPaths($viewPaths = [])
    {
        $additionalViewPaths = [];

        /*
         * If current theme is a child theme, then add the buildy-views folder.
         */
        if (is_child_theme()) {
            $additionalViewPaths['child-theme-buildy'] = trailingslashit(get_stylesheet_directory()).'buildy-views/';
        }

        /*
         * Add current theme (or Parent Theme) buildy-views folder
         */
        $additionalViewPaths['parent-theme-buildy'] = trailingslashit(get_template_directory()).'buildy-views/';

        /*
         * Add buildy views folder.
         */
        $additionalViewPaths['buildy-plugin'] = BUILDY_ROOT.'resources/views/';

        return array_merge($viewPaths, $additionalViewPaths);
    }

    public static function the_content($content)
    {
        /*
         * Possibly crude way of intercepting the output of the_content()
         * We intercept the_content() via a Wordpress filter.
         * Normally you would modify $content in some way and then return it as normal.
         *
         * However in this case, we don't want anything from the Wordpress text area to output.
         */
        if (isPageBuilderEnabled()) {
            if (is_admin() || defined('REST_REQUEST') && REST_REQUEST) {
                return; // Stop shortcode render on backend Or via REST.
            }

            // Get the current post.
            $post = get_post(get_the_ID());

            $thisPost = (object) [
                'ID' => $post->ID,
                'post_content' => json_decode($post->post_content),
            ];

            if (!empty($thisPost->post_content)) {
                Buildy::pushToCache($thisPost);

                Buildy::preFetchGlobals($thisPost);
    
                return Buildy::fromContent($thisPost->post_content)->render();
            }
        }


        /*
         * If the Page Builder was not enabled on this post.
         * Return the content, as is.
         */
        return $content;
    }

    public static function wp_enqueue_scripts()
    {
        wp_dequeue_script('jquery');
        wp_enqueue_script('jquery', '', [], false, false);

        /*
         * If Page Builder is marked as enabled for this page/post.
         * Enqueue the needed css and js for MVP frontend
         * Will need a button in the settings page to enable/disable this as well.
         */

        if (/*isPageBuilderEnabled() && */! get_field('disable_frontend_enqueue', 'option')) {
            // Temporary IE 11 polyfills --- These don't affect file size for non-ie browsers.
            wp_enqueue_script('ie-pollyfil', 'https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver%2CIntersectionObserverEntry%2CCustomEvent', null, null, false);
            wp_register_script('buildy-js', BUILDY_URL.'public/frontend-bundle.js', null, '1.0.0', true);
            if (function_exists('get_field')) {
                wp_localize_script('buildy-js', 'BMCB_SETTINGS', array(
                'breakpoints' => json_encode(get_field('BMCB_breakpoints', 'option')),
                'menu' => json_encode([ "breakpoint" => get_field('menu_breakpoint_size', 'option'), "open_on_click" => get_field('open_menu_items_on_click', 'option'), 'clone_menu_items' => get_field('clone_menu_items', 'option'), 'cloned_text_prefix' => get_field('menu_cloned_text_prefix', 'option'), 'move_to_nav' => get_field('move_to_nav', 'option')]),
              ));
            }
            wp_enqueue_script('buildy-js');
            wp_enqueue_style('buildy-components', BUILDY_URL.'public/frontend.css', null, '1.0.0', '');
            wp_enqueue_style('buildy-layout', BUILDY_URL.'public/buildy-layout.css', null, '1.0.0', '');
        }
    }
}
