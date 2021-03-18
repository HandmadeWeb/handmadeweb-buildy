<?php

namespace HandmadeWeb\Buildy;

use HandmadeWeb\Buildy\Traits\Helpers;
use HandmadeWeb\Illuminate\Facades\View;

class BuildyFrontend
{
    use Helpers;

    public static function boot()
    {
        add_action('wp_enqueue_scripts', [static::class, 'wp_enqueue_scripts']);
        add_filter('the_content', [static::class, 'the_content']);
        static::bladeViewPaths();
    }

    public static function bladeViewPaths()
    {
        /*
         * If current theme is a child theme, then add the buildy-views folder.
         */
        if (is_child_theme()) {
            $childThemeViewsPath = trailingslashit(get_stylesheet_directory()).'buildy-views/';

            static::locationExistsOrCreate($childThemeViewsPath) ? View::addLocation($childThemeViewsPath) : null;
        }

        /*
         * Add current theme (or Parent Theme) buildy-views folder
         */
        if (true) {
            $themeViewsPath = trailingslashit(get_template_directory()).'buildy-views/';
            static::locationExistsOrCreate($themeViewsPath) ? View::addLocation($themeViewsPath) : null;
        }

        /*
         * Add buildy views folder.
         */
        View::addLocation(trailingslashit(__DIR__).'../resources/views/');
    }

    public static function renderFrontend($post_id): string
    {
        $content = static::getContent($post_id);

        /*
         * Run do_shortcode on the returned HTML just incase any modules had any shortcode in them.
         */
        return do_shortcode(static::renderContent($content));
    }

    public static function getContent($post_id)
    {
        if ($post_id !== 0) {
            $content = get_post($post_id)->post_content;

            if (! empty($content)) {
                return json_decode($content);
            }

            return [];
        }
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
        if (static::isPageBuilderEnabled()) {
            if (is_admin()) {
                return; // Stop shortcode render on backend.
            }

            // Get the current page/post id.
            $post_id = get_queried_object_id();

            /*
             * Render the page/post content via Blade
             */
            return static::renderFrontend($post_id);
        }

        /*
         * If the Page Builder was not enabled on this post.
         * Return the content, as is.
         */
        return $content;
    }

    public static function renderContent($content): string
    {
        $html = '';

        if (! empty($content)) {
            foreach ($content as $data) {
                if ($data->attributes->renderDisabled ?? false && ! empty($_GET['preview'])) {
                    continue;
                }

                $data = apply_filters('handmadeweb-buildy_filter_all_data', $data);

                /**
                 * str_replace text-module to text.
                 * Check if returned module type is section, row or column.
                 * If it is then specify that the Blade file location is in the layouts folder.
                 * Otherwise it is located in the modules folder.
                 */
                $type = str_replace('-module', '', $data->type);

                $template = $data->options->moduleStyle ?? null;

                if (! empty($template)) {
                    $data = apply_filters("handmadeweb-buildy_filter_template:{$template}", $data);
                }

                if (! empty($template)) {
                    $template = static::seoUrl($template);
                }

                $location = 'modules';

                if (in_array($type, ['section', 'row', 'column'])) {
                    $location = 'layouts';
                }

                $data = apply_filters("handmadeweb-buildy_filter_type:{$type}", $data);

                $locations = [];
                if (! empty($template)) {
                    $locations[] = "{$location}.{$type}-{$template}";
                }
                $locations[] = "{$location}.{$type}";

                $html .= view()->first($locations, ['buildy' => static::class, 'bladeData' => $data]);
            }
        }

        return $html;
    }

    public static function wp_enqueue_scripts()
    {
        wp_dequeue_script('jquery');
        wp_enqueue_script('jquery', '', [], false, false);

        /**
         * If Page Builder is marked as enabled for this page/post.
         * Enqueue the needed css and js for MVP frontend
         * Will need a button in the settings page to enable/disable this as well.
         */
        $url = plugins_url().'/buildy-wp';

        if (/*static::isPageBuilderEnabled() && */! get_field('disable_frontend_enqueue', 'option')) {
            // Temporary IE 11 polyfills --- These don't affect file size for non-ie browsers.
            wp_enqueue_script('ie-pollyfil', 'https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver%2CIntersectionObserverEntry%2CCustomEvent', null, null, false);
            wp_enqueue_script('buildy-js', "{$url}/public/frontend-bundle.js", null, '1.0.0', true);
            wp_enqueue_style('buildy-css', "{$url}/public/frontend.css", null, '1.0.0', '');
        }
    }
}
