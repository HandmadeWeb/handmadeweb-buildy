<?php

namespace HandmadeWeb\Buildy\Frontend;

use HandmadeWeb\Illuminate\Facades\BladeCompiler;

class FrontendDirectives
{
    /**
     * Define Directives to register.
     *
     * The $directives array expects the following format:
     * 'directive_name' => 'method_name',
     *
     * Where directive_name will be used by BladeCompiler::directive to register the directive with Blade.
     * And method_name is the public static function that should be called for that directive.
     *
     * $expression will always be passed to these directives.
     *
     * @var array
     */
    protected static $directives = [
        // 'wpaction' => 'do_action',
        // 'wpfilter' => 'apply_filters',
    ];

    public static function boot()
    {
        foreach (static::$directives as $directive_name => $method_name) {
            BladeCompiler::directive($directive_name, [static::class, $method_name]);
        }
    }
}
