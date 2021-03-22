<?php

namespace HandmadeWeb\Buildy\Traits;

use HandmadeWeb\Illuminate\Facades\DB;

trait ContentCollector
{
    protected static $cache = [];

    /**
     * Undocumented function.
     *
     * @param [type] $post_id
     * @return void
     */
    public static function getContentForId($post_id)
    {
        if ($post_id !== 0) {
            if (! empty(static::$cache[$post_id])) {
                $post = static::$cache[$post_id];
            } else {
                $post = DB::table('posts')->where('ID', $post_id)->first(['ID', 'post_content']);
                $post->post_content = json_decode($post->post_content);
            }

            if (! empty($post->post_content)) {
                static::pushToCache($post);
                static::preFetchGlobals($post);

                return static::$cache[$post->ID]->post_content;
            }
        }

        return [];
    }

    protected static function pushToCache($post)
    {
        if (! empty($post->ID) && ! isset(static::$cache[$post->ID])) {
            static::$cache[$post->ID] = $post;
        }
    }

    protected static function preFetchGlobals($post)
    {
        $globals = collect($post->post_content)->where('type', 'global-module');
        if ($globals->count() > 0) {
            $globalsToFetch = [];
            foreach ($globals as $global) {
                if (! empty($global->content->id) && ! isset(static::$cache[$global->content->id])) {
                    $globalsToFetch[] = $global->content->id;
                }
            }

            if (! empty($globalsToFetch)) {
                $globals = DB::table('posts')->whereIn('ID', $globalsToFetch)->get(['ID', 'post_content']);

                foreach ($globals as $global) {
                    $global->post_content = json_decode($global->post_content);
                    static::pushToCache($global);
                }
            }
        }

        return $globals->count();
    }
}
