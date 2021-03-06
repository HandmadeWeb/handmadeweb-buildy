<?php

namespace HandmadeWeb\Buildy\Backend;

use HandmadeWeb\Illuminate\Facades\Cache;
use HandmadeWeb\Illuminate\Facades\DB;

class BackendLoader
{
    public static function admin_boot()
    {
        add_action('admin_enqueue_scripts', [static::class, 'admin_enqueue_scripts']);
        add_action('admin_footer', [static::class, 'admin_footer']);
        add_action('admin_head', [static::class, 'admin_head']);
        add_action('edit_form_after_editor', [static::class, 'admin_edit_form_after_editor']);
        add_filter('wp_default_editor', [static::class, 'admin_wp_default_editor']);
        add_action('save_post_bmcb-global', [static::class, 'clear_globals_cache'], 100, 0);
    }

    public static function clear_globals_cache()
    {
        Cache::forget('Buildy_GlobalsCache');
    }

    public static function admin_footer()
    {
        /*
         * If Page Builder is marked as enabled for this page/post.
         * Include the needed CSS/JS files.
         */
        if (isPageBuilderEnabled()) {
            wp_localize_script('hmw-child-frontend-scripts', 'global_vars', [
                'admin_ajax_url' => admin_url('admin-ajax.php'),
            ]);

            echo sprintf(
                "
            <script type='text/javascript'>
                var global_vars = {
                  rest_api_base: '%s'
                }
            </script>",
                get_rest_url()
            );

            echo '<script src="'.BUILDY_URL.'/buildy-wp-gui/dist/chunk-vendors.js"></script>';
            echo '<script src="'.BUILDY_URL.'/buildy-wp-gui/dist/app.js"></script>';
        }
    }

    public static function admin_head()
    {
        /*
         * If Page Builder is marked as enabled for this page/post.
         * Include the needed CSS/JS files.
         */
        if (isPageBuilderEnabled()) {
            $url = BUILDY_URL.'buildy-wp';

            echo '<link href="'.BUILDY_URL.'/buildy-wp-gui/dist/app.css" rel="stylesheet">';
        }
    }

    public static function admin_enqueue_scripts()
    {
        // Load jQuery in the header rather than footer.
        wp_dequeue_script('jquery');
        wp_enqueue_script('jquery', '', [], false, false);

        /*
         * If Page Builder is marked as enabled for this page/post.
         * Enqueue the needed scripts to allow the Media Library to function in the builder.
         */
        if (is_admin() && isPageBuilderEnabled()) {
            wp_enqueue_media();
        }
    }

    public static function admin_edit_form_after_editor($post)
    {
        /*
         * Define if this post is a global or not.
         */
        if ($post->post_type === 'bmcb-global') {
            $isGlobal = true;
        } else {
            $isGlobal = false;
        }

        if (isPageBuilderEnabled()) {
            /*
             * Create config array for the Page Builder.
             */

             if (function_exists("get_field")) {
               $overwrite_mode = get_field('overwrite_mode', 'option');
             }

            $config = json_encode([
                'post_id' => $post->ID,
                'post_type' => $post->post_type,
                'isGlobal' => $isGlobal,
                'theme_colours' => $theme_colours ?? null,
                'overwrite_mode' => $overwrite_mode ?? false,
                'is_admin' => current_user_can('administrator'),
                'site_url' => get_site_url(),
                'registered_image_sizes' => static::get_all_image_sizes(),
                'registered_post_types' => get_post_types(['_builtin' => false]),
                //'global_api' => get_rest_url(get_current_blog_id(), 'wp/v2/bmcb-global'),
                'global_api' => get_rest_url(get_current_blog_id(), 'bmcb/v3/globals?doing_wp_cron'),
            ]);

            // This script contains the Config Array for the Page Builder.
            echo "<script id='config' type='application/json'>{$config}</script>";

            // This Div Loads Vue
            echo '<div id="app"></div>';

            // This style hides the Wordpress text editor.
            echo '<style>#postdivrich { display: none !important; }</style>';
        }
    }

    public static function admin_wp_default_editor($r)
    {
        if (isPageBuilderEnabled()) {
            return 'html'; // HTML / Text tab in TinyMCE
        }

        return $r;
    }

    public static function boot()
    {
        //The Following registers an api route with multiple parameters.
        add_action('rest_api_init', function () {
            register_rest_route('bmcb/v3', '/globals', [
                'methods' => 'GET',
                'callback' => [static::class, 'get_globals'],
                'permission_callback' => '__return_true',
            ]);

            register_rest_route('bmcb/v1', '/module_styles=(?P<module_styles>[a-zA-Z0-9-]+)', [
                'methods' => 'GET',
                'callback' => [static::class, 'get_module_styles'],
                'permission_callback' => '__return_true',
            ]);
        });

        // Must register custom post types first
        static::custom_post_types_register_globals();

        // Must register custom post types first
        static::acf_add_options_pages();
        static::acf_add_fields();
    }

    public static function get_globals($data)
    {
        $globals = Cache::rememberForever('Buildy_GlobalsCache', function () {
            return DB::table('posts')->where('post_type', 'bmcb-global')->where('post_status', 'publish')->get(['ID', 'post_title']);
        });

        return $globals->map(function ($global) {
            return [
                'id' => $global->ID,
                'title' => $global->post_title,
            ];
        });
    }

    public static function get_module_styles($request)
    {
        if (! is_wp_error($request)) {
            $module_type = $request['module_styles'];

            $data = get_field("module_styles_{$module_type}", 'option') ?? '';

            return new \WP_REST_Response(
                [
                    'status' => 200,
                    'response' => 'API hit success',
                    'body' => $data,
                ]
            );
        }
    }

    /**
     * Get all the registered image sizes along with their dimensions.
     *
     * @global array $_wp_additional_image_sizes
     *
     * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
     * @return array $image_sizes The image sizes
     */
    public static function get_all_image_sizes()
    {
        global $_wp_additional_image_sizes;

        $default_image_sizes = ['thumbnail', 'medium', 'large'];

        foreach ($default_image_sizes as $size) {
            $image_sizes[$size]['width'] = intval(get_option("{$size}_size_w"));
            $image_sizes[$size]['height'] = intval(get_option("{$size}_size_h"));
            $image_sizes[$size]['crop'] = get_option("{$size}_crop") ? get_option("{$size}_crop") : false;
        }

        if (isset($_wp_additional_image_sizes) && count($_wp_additional_image_sizes)) {
            $image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
        }

        return $image_sizes;
    }

    public static function acf_add_fields()
    {
        if (function_exists('acf_add_local_field_group')) {
            $acfFiles = glob(BUILDY_ROOT.'acf/*.json');

            foreach ($acfFiles as $acfFile) {
                if ($acfFileContent = file_get_contents($acfFile)) {
                    $acfFieldGroups = json_decode($acfFileContent, true);
                    if (! empty($acfFieldGroups)) {
                        foreach ($acfFieldGroups as $acfFieldGroup) {
                            $isPageBuilderField = false;

                            foreach ($acfFieldGroup['fields'] as $field) {
                                if ($field['name'] === 'BMCB_use_PageBuilder') {
                                    $isPageBuilderField = true;
                                }
                            }

                            if ($isPageBuilderField) {
                                $post_types = get_field('BMCB_post_types', 'option');
                                if (! empty($post_types)) {
                                    foreach ($post_types as $post_type) {
                                        $acfFieldGroup['location'][][] = [
                                            'param' => 'post_type',
                                            'operator' => '==',
                                            'value' => strtolower(trim($post_type['BMCB_post_type'])),
                                        ];
                                    }
                                }
                            }

                            acf_add_local_field_group($acfFieldGroup);
                        }
                    }
                }
            }
        }
    }

    public static function acf_add_options_pages()
    {

        // Check function exists.
        if (! function_exists('acf_add_options_page')) {
            return false;
        }

        // register options pages.
        acf_add_options_page([
            'page_title' => 'Buildy Settings',
            'menu_title' => 'Buildy Settings',
            'menu_slug' => 'bmcb-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
            'autoload' => false,
        ]);
    }

    public static function custom_post_types_register_globals()
    {
        return register_post_type(
            'bmcb-global',
            [
                'labels' => [
                    'name' => 'Globals',
                    'singular_name' => 'Global',
                ],
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'has_archive' => false,
                'show_in_rest' => false,
                'rewrite' => [
                    'slug' => 'bmcb-globals',
                ],
                'menu_icon' => 'dashicons-admin-site-alt2',
            ]
        );
    }
}
