<?php

namespace HandmadeWeb\Buildy\Frontend;

use HandmadeWeb\Buildy\Buildy;

class FrontendShortcodes
{
    protected static $shortcodes = [
        // 'buildy_global' => 'buildy_global',
    ];

    public static function boot()
    {
        foreach (static::$shortcodes as $shortcode_name => $method_name) {
            add_shortcode($shortcode_name, [static::class, $method_name]);
        }
    }

    // public static function buildy_global($atts)
    // {
    //     if (! empty($atts['id'])) {
    //         return Buildy::fromGlobalId($atts['id']);
    //     }
    // }
}
