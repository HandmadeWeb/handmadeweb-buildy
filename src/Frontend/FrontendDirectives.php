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
        'buildyRenderRow' => 'buildy_render_row',
        'buildyRenderColumn' => 'buildy_render_column',
        'buildyRenderModule' => 'buildy_render_module',
        'buildyRenderContentForId' => 'buildy_render_content_for_id',
        // 'wpaction' => 'do_action',
        // 'wpfilter' => 'apply_filters',
    ];

    public static function boot()
    {
        foreach (static::$directives as $directive_name => $method_name) {
            BladeCompiler::directive($directive_name, [static::class, $method_name]);
        }
    }

    public static function buildy_render_row($expression)
    {
        return "<?php echo \HandmadeWeb\Buildy\Buildy::renderRow($expression); ?>";
    }

    public static function buildy_render_column($expression)
    {
        return "<?php echo \HandmadeWeb\Buildy\Buildy::renderColumn($expression); ?>";
    }

    public static function buildy_render_module($expression)
    {
        return "<?php echo \HandmadeWeb\Buildy\Buildy::renderModule($expression); ?>";
    }

    public static function buildy_render_content_for_id($expression)
    {
        return "<?php echo \HandmadeWeb\Buildy\Buildy::renderContentForId($expression); ?>";
    }
}
