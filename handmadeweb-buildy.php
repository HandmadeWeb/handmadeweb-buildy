<?php
/*
Plugin Name: Handmade Web - Buildy
Plugin URI: http://
Description: Buildy
Author: Handmade Web
Version: 3.0.0
Author URI: http://
*/

use HandmadeWeb\Buildy\Backend\BackendLoader;
use HandmadeWeb\Buildy\Frontend\FrontendDirectives;
use HandmadeWeb\Buildy\Frontend\FrontendFilters;
use HandmadeWeb\Buildy\Frontend\FrontendLoader;

defined('ABSPATH') || die();

define('BUILDY_ROOT', __DIR__);

require __DIR__.'/vendor/autoload.php';

/*
 * Backend Actions
 */
add_action('admin_init', [BackendLoader::class, 'admin_boot'], 10);
add_action('init', [BackendLoader::class, 'boot'], 100);

/*
 * Frontend Actions
 */
add_action('init', [FrontendLoader::class, 'boot'], 100);
add_action('init', [FrontendFilters::class, 'boot'], 10);
add_action('init', [FrontendDirectives::class, 'boot'], 10);
