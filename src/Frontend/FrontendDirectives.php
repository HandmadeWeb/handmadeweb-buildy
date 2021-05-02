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
        'wpaction' => 'do_action',
        'wpfilter' => 'apply_filters',
    ];

    public static function boot()
    {
        foreach (static::$directives as $directive_name => $method_name) {
            BladeCompiler::directive($directive_name, [static::class, $method_name]);
        }
    }

    /**
     * @action( 'example_action', $args );
     *
     * @param string $tag     The name of the action hook.
     * @param mixed  ...$args Additional parameters to pass to the callback functions.
     * @return mixed action( 'example_action', $args );
     */
    public static function do_action($expression)
    {
        return "<?php do_action({$expression}); ?>";
    }

    /**
     * @filter( 'example_filter', 'filter me', $arg1, $arg2 );
     *
     * @param string $tag     The name of the filter hook.
     * @param mixed  $value   The value to filter.
     * @param mixed  ...$args Additional parameters to pass to the callback functions.
     * @return mixed echo apply_filters( 'example_filter', 'filter me', $arg1, $arg2 );
     */
    public static function apply_filters($expression)
    {
        return "<?php echo apply_filter({$expression}); ?>";
    }
}
