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
        return (new static)
            ->setId($post_id)
            ->setAsGlobal(true);
    }

    public static function fromGlobalContent(array $content)
    {
        return (new static)
            ->setContent($content)
            ->setAsGlobal(true);
    }
}
