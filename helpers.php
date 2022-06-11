<?php

use HandmadeWeb\Buildy\Buildy;

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

// AJAX to post ACF form to DB - Can possibly be placed in a different location but won't work under 'admin_boot' for some reason
add_action("wp_ajax_create_acf_module", "create_acf_module"); 
function create_acf_module() {
    if ( !wp_verify_nonce( $_POST['nonce'], "ajax-nonce")) {
        die();
    } 
    // Enqueue ACF Scripts - Possibly optional
    acf_enqueue_scripts();
    acf_enqueue_uploader();

    // Decode the posted form and prepare for submission
    $form = json_decode( acf_decrypt( $_POST['_acf_form'] ), true );

    // Submit the prepared form
    acf()->form_front->submit_form($form);

    // Return the form to action acf/submit_form
    return $form;

    wp_die();
}

// This is how we are currently handling duplication of ACF Modules. (Using yoast duplicate post)
add_action("wp_ajax_acf_duplicate_post", "acf_duplicate_post"); 
function acf_duplicate_post() {
  if ( !wp_verify_nonce( $_POST['nonce'], "ajax-nonce")) {
    return wp_die();
  } 

  if (!function_exists('duplicate_post_create_duplicate')) return null;

    $currentModuleID = get_post( $_POST['post_id']);
    $currentPostID = $_POST['current_post_id'];
    // $original_post_id = get_post_meta($currentModuleID, '_bmcb_original_post_id', true);

    $newModuleID = duplicate_post_create_duplicate($currentModuleID);
    update_post_meta($newModuleID, '_bmcb_original_post_id', $currentPostID);
    echo json_encode($newModuleID);
    
  return wp_die();
}

// Create additional location routes for ACF
add_action('acf/init', 'acf_create_location_route');
function acf_create_location_route() {

    // Create location route for 'Module' field groups for 'bmcb-acf'. Used to display dropdown of available module field groups in ACF module.
    class BMCB_ACF_Module extends ACF_Location {

        public function initialize() {
            $this->name = 'bmcb-acf-module';
            $this->label = __( "Buildy Module", 'acf' );
            $this->category = 'forms';
        }
    }
    acf_register_location_type( 'BMCB_ACF_Module' );

    // Create location route for 'Modules: Global' field groups - Used as a placeholder for adding global module components.
    class BMCB_ACF_Modules_Global extends ACF_Location {

        public function initialize() {
            $this->name = 'bmcb-acf-module-global';
            $this->label = __( "Buildy Global Modules", 'acf' );
            $this->category = 'forms';
        }
    }
    acf_register_location_type( 'BMCB_ACF_Modules_Global' );
}