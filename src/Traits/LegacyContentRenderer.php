<?php

namespace HandmadeWeb\Buildy\Traits;

trait LegacyContentRenderer
{
    public static function renderFrontend($post_id)
    {
        return static::renderContentForId($post_id);
    }
}
