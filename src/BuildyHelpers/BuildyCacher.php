<?php

namespace HandmadeWeb\Buildy\BuildyHelpers;

use HandmadeWeb\Illuminate\Facades\DB;

trait BuildyCacher
{
    protected static $cache = [];

    public static function pushToCache($post)
    {
        if (! empty($post->ID) && ! isset(static::$cache[$post->ID])) {
            static::$cache[$post->ID] = $post;
        }
    }

    public static function preFetchGlobals($post)
    {
        if (! empty($post->post_content)) {
            $globals = collect($post->post_content)->where('type', 'global-module');
            if ($globals->count() > 0) {
                $globalsToFetch = [];
                foreach ($globals as $global) {
                    if (! empty($global->content->id) && ! isset(static::$cache[$global->content->id])) {
                        $globalsToFetch[] = $global->content->id;
                    }
                }

                if (! empty($globalsToFetch)) {
                    $globals = DB::table('posts')->whereIn('ID', $globalsToFetch)->where('post_type', 'bmcb-global')->get(['ID', 'post_content']);

                    foreach ($globals as $global) {
                        $global->post_content = json_decode($global->post_content);
                        static::pushToCache($global);
                    }
                }
            }

            return $globals->count();
        }
    }
}
