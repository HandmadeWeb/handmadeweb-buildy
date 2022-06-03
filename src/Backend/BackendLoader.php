<?php

namespace HandmadeWeb\Buildy\Backend;

use HandmadeWeb\Illuminate\Facades\Cache;
use HandmadeWeb\Illuminate\Facades\DB;

class BackendLoader
{
    public static function admin_boot()
    {
        add_action('admin_enqueue_scripts', [static::class, 'admin_enqueue_scripts']); // Enqueue admin scripts
        add_action('admin_footer', [static::class, 'admin_footer']); // Load various scripts
        add_action('admin_head', [static::class, 'admin_head']); // Load various stylsheets
        add_action('edit_form_after_editor', [static::class, 'admin_edit_form_after_editor']); // Load Buildy into backend editor
        add_filter('wp_default_editor', [static::class, 'admin_wp_default_editor']); // Update default TinyMCE editor to return HTML
        add_action('save_post_bmcb-global', [static::class, 'clear_globals_cache'], 100, 0); // Clear global Buildy cache
        add_action('acf/submit_form', [static::class, 'acf_submit_form'], 10, 2); // Return Post ID and Field Groups on Buildy ACF Form save
        
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
                  admin_ajax_url: '%s',
                  rest_api_base: '%s',
                  nonce: '%s',
                  rest_nonce: '%s',
                }
            </script>",
                admin_url('admin-ajax.php'),
                get_rest_url(),
                wp_create_nonce('ajax-nonce'),
                wp_create_nonce('wp_rest'),
            );

            echo '<script src="'.BUILDY_URL.'/buildy-wp-gui/dist/chunk-vendors.js"></script>';
            echo '<script src="'.BUILDY_URL.'/buildy-wp-gui/dist/app.js"></script>';
        }

        // Load acf_form() onto Globals > ACF Modules post type - Used to edit existing modules
        if (get_current_screen()->base == 'post' && get_current_screen()->post_type == 'bmcb-acf') {
            $postID = get_the_id();
            echo '<div id="wpcontent" class="acf-meta-box">';
            echo '<div class="bg-white border" style="position: relative; padding: 20px; border: 1px solid #eaeaea;">';
            echo '<h1 style="padding: 0 12px">'. get_the_title() .' - '. $postID .'</h1>';
            if (get_post_meta($postID, '_bmcb_is_linked', true )) {
                echo '<button class="button button-secondary" onclick="disableGlobal(event)" style="position: absolute; top: 20px; right: 20px;">Disable Global</button>';
                echo '<script>
                function disableGlobal(e) {
                    fetch("'. get_rest_url() .'bmcb/v1/acf_is_linked/post_id='. $postID .'/data_type=set")
                    .then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        if(data.body === true) {
                            e.target.remove();
                        }
                    })
                }
                </script>';
            }
            // Retrieve field groups from post meta to display form correctly
            $field_groups = get_post_meta($postID, '_bmcb_field_groups', true);
            // Use existing acf_form() function to generate existing form. HTML data will be embedded below WP post form
            acf_form(array(
            'post_id' => $postID,
            'field_groups' => $field_groups,
            'post_title' => false,
            'post_content' => false,
            'form' => true,
            'uploader' => 'wp',
            'submit_value' => __('Update meta')
            ));
            echo '</div>';
            echo '</div>';
            // Some basic styles - Can be edited at a later stage if required
            echo '<style>#wpbody { display: none; } .acf-meta-box { padding: 20px 20px 80px 20px; }</style>';
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
            // ACF form requirements - Not sure if required
            acf_form_head();
            acf_enqueue_uploader();
        }

        // Registers acf_form_head() under Globals > ACF Modules post type - Used to edit existing modules
        if ( get_current_screen()->post_type == 'bmcb-acf' ) {
            acf_form_head();
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

    public static function acf_submit_form($form, $post_id)
    {
        // Check that current post type is 'bmcb-acf' and status is to be published.
        if ($form['new_post']['post_type'] == 'bmcb-acf' && $form['new_post']['post_status'] == 'publish') {
            // Update post meta with field group IDs. Used for loading form into backend form editor
            update_post_meta($post_id, '_bmcb_field_groups', $form['field_groups']);
            // Create new blade file (if not exists)
            $field_groups = $form['field_groups'][0];
            $file = BUILDY_ROOT . "resources/views/modules/acf.blade.php";
            $newfile = get_stylesheet_directory() . "/buildy-views/modules/acf-$field_groups.blade.php";
            if(!is_file($newfile)) {
                copy($file,$newfile);
            }
            // Return JSON success daya - Post ID and Field Groups
            wp_send_json_success(array(
                'post_id' => $post_id,
                'field_groups' => $form['field_groups']
            ));
            exit;
        }
    }

    public static function boot()
    {
        //The Following registers an api route with multiple parameters.
        add_action('rest_api_init', function () {
            // Get global sections - Used for Buildy globals
            register_rest_route('bmcb/v3', '/globals', [
                'methods' => 'GET',
                'callback' => [static::class, 'get_globals'],
                'permission_callback' => '__return_true',
            ]);

            // Get module styles - Used for custom module styles
            register_rest_route('bmcb/v1', '/module_styles=(?P<module_styles>[a-zA-Z0-9-]+)', [
                'methods' => 'GET',
                'callback' => [static::class, 'get_module_styles'],
                'permission_callback' => '__return_true',
            ]);

            // Get ACF modules - Used to return dropdown of available module styles in ACF module
            register_rest_route('bmcb/v1', '/acf_modules', [
                'methods' => 'GET',
                'callback' => [static::class, 'get_acf_modules'],
                'permission_callback' => '__return_true',
            ]);

            // Get posts from 'bmcb-acf' - Used to return dropdown of available posts in ACF module for existing posts
            register_rest_route('bmcb/v1', '/acf_posts', [
                'methods' => 'GET',
                'callback' => [static::class, 'get_acf_posts'],
                'permission_callback' => '__return_true',
            ]);

            // Get acf_form() by passing post ID and field groups - Used to create new / display existing forms in ACF module
            register_rest_route('bmcb/v1', '/acf_form/post_id=(?P<postID>[a-zA-Z0-9-]+)/field_groups=(?P<fieldIDs>[0-9_,]+)/is_linked=(?P<isLinked>[a-z]+)', [
                'methods' => 'GET',
                'callback' => [static::class, 'get_acf_form'],
                'permission_callback' => '__return_true',
            ]);

             // Get acf_form() by passing post ID and field groups - Used to create new / display existing forms in ACF module
             register_rest_route('bmcb/v1', '/acf_is_linked/post_id=(?P<postID>[a-zA-Z0-9-]+)/data_type=(?P<dataType>[a-zA-Z0-9-]+)', [
                'methods' => 'GET',
                'callback' => [static::class, 'acf_is_linked'],
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

    // Get list of ACF Field Groups with 'bmcb-acf-module' label
    public static function get_acf_modules($request)
    {
        $data = array();

        // Get list of ACF Field Groups
        $field_groups = acf_get_field_groups();
        
        // Loop through array and add to field 'data'
        foreach( $field_groups as $key => $value ) {

            if ($value['location'][0][0]['param'] == 'bmcb-acf-module') {
                $data[$key]['field_group_id'] = $value['ID'];
                $data[$key]['field_group_title'] = $value['title'];
            }
                    
        }

        // Return data to AJAX to prepopulate dropdown of available module field groups - Used for new modules
        return new \WP_REST_Response(
            [
                'status' => 200,
                'response' => 'API hit success',
                'body' => $data,
            ]
        );
    }

    // Get list of 'bmcb-acf' posts - Used to display existing ACF posts
    public static function get_acf_posts($request)
    {
        $data = array();

        // Get list of ACF Field Groups
        $post_ids = get_posts(array(
            'fields' => 'ids',
            'post_type' => 'bmcb-acf',
            'posts_per_page'  => -1
        ));

        // Loop tthrough array and add to field 'data'
        foreach( $post_ids as $key => $post_id ) {
            $data[$key]['field_group_id'] = get_post_meta($post_id, '_bmcb_field_groups', true);
            $data[$key]['field_group_title'] = get_the_title($post_id) .' - '. $post_id;
        }

        // Return data to AJAX to prepopulate dropdown of available 'bmcb-acf' posts - Used for existing modules
        return new \WP_REST_Response(
            [
                'status' => 200,
                'response' => 'API hit success',
                'body' => $data,
            ]
        );
    }


    // Get acf_form() - Used to load ACF form into ACF module - Used to create new and load existing forms into page
    public static function get_acf_form($request)
    {
        // Retrieve post ID from request, otherwise create new post.
        $postID = json_decode($request['postID']) ?? 'new_post';
        // Retrieve field groups from request
        $fieldIDs = explode(',', $request['fieldIDs']);
        // Retrive linked status - Used to prevent globals from displaying form
        $isLinked = json_decode($request['isLinked']) ?? false;            

        // Use existing acf_form() function to generate new / existing form. HTML data will be embedded in AJAX return request
        ob_start();
        acf_form(array(
            'post_id' => $postID,
            'new_post' => array(
                'post_type' => 'bmcb-acf',
                'post_status' => 'publish'
            ),
            'field_groups' => $fieldIDs,
            'post_title' => false,
            'post_content' => false,
            'uploader' => 'wp',
            'form' => true,
            'uploader' => 'wp',
        )); 
        $data['form'] = ob_get_clean();

        // Update post meta with linked status. Used to display notice on globals warning users
        if ($isLinked == true || get_post_meta($postID, '_bmcb_is_linked', true ) == true) {
            update_post_meta($postID, '_bmcb_is_linked', $request['isLinked']);
            $data['is_linked'] = true;
        }

        return new \WP_REST_Response(
            [
                'status' => 200,
                'response' => 'API hit success',
                'body' => $data,
            ]
        );
    }

    public static function acf_is_linked($request) 
    {
        if( $request['dataType'] == 'set' ) {
            $data = update_post_meta($request['postID'], '_bmcb_is_linked', false);
        } else {
            $data = get_post_meta($request['postID'], '_bmcb_is_linked', true);
        }

        // Return data to AJAX to notify page global has been disabled
        return new \WP_REST_Response(
            [
                'status' => 200,
                'response' => 'API hit success',
                'body' => $data,
            ]
        );
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
        $parent = acf_add_options_page([
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
        register_post_type(
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

        register_post_type( "bmcb-acf", [
                'labels' => [
                    'name' => 'ACF Modules',
                    "all_items" => 'ACF Modules',
                    'singular_name' => 'ACF Module',
                ],
                'public' => true,
                'publicly_queryable' => false,
                'show_ui' => true,
                "show_in_menu" => "edit.php?post_type=bmcb-global",
                "show_in_nav_menus" => true,
                'has_archive' => false,
                'show_in_rest' => false,
                'rewrite' => [
                    'slug' => 'bmcb-acf',
                ],
            ]);
    }      
}
