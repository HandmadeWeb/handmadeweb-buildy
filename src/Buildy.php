<?php

namespace HandmadeWeb\Buildy;

use HandmadeWeb\Buildy\Traits\ContentCollector;
use HandmadeWeb\Buildy\Traits\ContentRenderer;

class Buildy
{
    use ContentCollector;
    use ContentRenderer;

    /**
     * Undocumented function.
     *
     * @param [type] $post_id
     * @return void
     */
    public static function renderContentForId($post_id)
    {
        return static::renderContent(static::getContentForId($post_id));
    }
}
