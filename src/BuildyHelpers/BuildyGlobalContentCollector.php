<?php

namespace HandmadeWeb\Buildy\BuildyHelpers;

trait BuildyGlobalContentCollector
{
    protected $isGlobal = false;

    public function setAsGlobal(bool $isGlobal = true)
    {
        $this->isGlobal = $isGlobal;

        return $this;
    }

    public static function fromGlobalId(int $post_id)
    {
        return (new static(
                static::getContentForId($post_id, true)
            ))->setAsGlobal(true);
    }

    public static function fromGlobalContent(array $content)
    {
        return (new static($content))->setAsGlobal(true);
    }
}
