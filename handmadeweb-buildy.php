<?php
/*
Plugin Name: Handmade Web - Buildy
Plugin URI: http://
Description: Buildy
Author: Handmade Web
Version: 3.0.0
Author URI: http://
*/

defined('ABSPATH') || die();

use HandmadeWeb\Buildy\BuildyBackend;
use HandmadeWeb\Buildy\BuildyFrontend;

require_once __DIR__.'/vendor/autoload.php';

add_action('admin_init', [BuildyBackend::class, 'admin_boot']);
add_action('init', [BuildyBackend::class, 'boot'], 100);
add_action('init', [BuildyFrontend::class, 'boot'], 100);
