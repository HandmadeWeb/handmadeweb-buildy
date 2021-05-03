<?php
/*
Plugin Name: Handmade Web - Buildy
Plugin URI: http://
Description: Buildy
Author: Handmade Web
Version: 3.0.0
Author URI: http://
*/

use HandmadeWeb\Buildy\PluginLoader;

defined('ABSPATH') || die();

define('BUILDY_ROOT', trailingslashit(__DIR__));
define('BUILDY_URL', trailingslashit(plugin_dir_url(__FILE__)));

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/helpers.php';

add_action('plugins_loaded', [PluginLoader::class, 'boot'], 10);
