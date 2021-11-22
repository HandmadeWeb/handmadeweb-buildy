<?php

/**
 * Plugin Name: Handmade Web - Buildy
 * Plugin URI: https://github.com/handmadeweb/handmadeweb-buildy
 * Description: Buildy
 * Author: Handmade Web
 * Version: 3.0.6
 * Author URI: https://www.handmadeweb.com.au/
 * GitHub Plugin URI: https://github.com/handmadeweb/handmadeweb-buildy
 * Primary Branch: main
 * Requires at least: 5.0
 * Requires PHP: 7.3
 * Go: Enjoy.
 */

use HandmadeWeb\Buildy\PluginLoader;

defined('ABSPATH') || die();

define('BUILDY_ROOT', trailingslashit(__DIR__));
define('BUILDY_URL', trailingslashit(plugin_dir_url(__FILE__)));

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/helpers.php';

add_action('plugins_loaded', [PluginLoader::class, 'boot'], 10);
