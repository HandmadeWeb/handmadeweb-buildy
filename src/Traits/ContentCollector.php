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
            $post = static::$cache[$post_id] ?? DB::table('posts')->where('ID', $post_id)->first('post_content');

            if (! empty($post->post_content)) {

                // Cache of globals
                $globals = collect(json_decode($post->post_content))->where('type', 'global-module');
                if ($globals->count() > 0) {
                    $globalsToQuery = [];
                    foreach ($globals as $global) {
                        if (! empty($global->content->id) && ! isset(static::$cache[$global->content->id])) {
                            $globalsToQuery[] = $global->content->id;
                        }
                    }

                    if (! empty($globalsToQuery)) {
                        $globals = DB::table('posts')->where('post_type', 'bmcb-global')->whereIn('ID', $globalsToQuery)->get(['ID', 'post_content']);

                        foreach ($globals as $global) {
                            static::$cache[$global->ID] = $global;
                        }
                    }
                }
                // /Cache of globals

                if (! isset(static::$cache[$post_id])) {
                    static::$cache[$post_id] = $post;
                }

                return json_decode($post->post_content);
            }
        }

        return [];
    }
}
