<?php

namespace HandmadeWeb\Buildy\Traits;

use HandmadeWeb\Illuminate\Facades\DB;

trait ContentCollector
{
    public static function getContentForId($post_id)
    {
        if ($post_id !== 0) {
            // if (! isset(static::$page_results[$post_id])) {
            //     $post = DB::table('posts')->where('ID', $post_id)->first('post_content');
            //     static::$page_results[$post_id] = $post;
            // } else {
            //     $post = static::$page_results[$post_id];
            // }

            $post = DB::table('posts')->where('ID', $post_id)->first('post_content');

            if (! empty($post->post_content)) {
                return json_decode($post->post_content);
            }
        }

        return [];
    }
}
