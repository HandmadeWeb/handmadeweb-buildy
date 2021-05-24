<?php

namespace HandmadeWeb\Buildy\BuildyHelpers;

use HandmadeWeb\Illuminate\Facades\DB;

trait BuildyContentCollector
{
    use BuildyCacher;
    use BuildyGlobalContentCollector;

    public static function fromId(int $post_id)
    {
        return (new static)
            ->setId($post_id)
            ->setAsGlobal(false);
    }

    public static function fromContent(array $content)
    {
        return (new static)
            ->setContent($content)
            ->setAsGlobal(false);
    }

    protected static function getContentForId(int $post_id, bool $isGlobal = false)
    {
        if (! empty(static::$cache[$post_id])) {
            $post = static::$cache[$post_id];
        } else {
            $query = DB::table('posts')->where('ID', $post_id);
            if ($isGlobal) {
                $query->where('post_type', 'bmcb-global');
            }

            $post = $query->first(['ID', 'post_content']);
            $post->post_content = json_decode($post->post_content);
        }

        if (! empty($post->post_content)) {
            static::pushToCache($post);
            static::preFetchGlobals($post);

            return static::$cache[$post->ID]->post_content;
        }

        return [];
    }
}
