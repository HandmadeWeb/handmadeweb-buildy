<?php

namespace HandmadeWeb\Buildy;

use HandmadeWeb\Buildy\Backend\BackendLoader;
use HandmadeWeb\Buildy\Frontend\FrontendDirectives;
use HandmadeWeb\Buildy\Frontend\FrontendFilters;
use HandmadeWeb\Buildy\Frontend\FrontendLoader;
use HandmadeWeb\Illuminate\Static\Abstract\AbstractLoaderClass;

class PluginLoader extends AbstractLoaderClass
{
    public static function init()
    {
        /*
        * Backend Actions
        */
        add_action('admin_init', [BackendLoader::class, 'admin_boot'], 10);
        add_action('init', [BackendLoader::class, 'boot'], 100);

        /*
        * Frontend Actions
        */
        add_action('init', [FrontendLoader::class, 'boot'], 100);
        add_action('init', [FrontendFilters::class, 'boot'], 1);
        add_action('init', [FrontendDirectives::class, 'boot'], 10);
    }
}
