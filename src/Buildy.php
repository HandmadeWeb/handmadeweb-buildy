<?php

namespace HandmadeWeb\Buildy;

use HandmadeWeb\Buildy\BuildyHelpers\BuildyContentCollector;
use HandmadeWeb\Buildy\BuildyHelpers\BuildyRenderer;

class Buildy
{
    use BuildyContentCollector;
    use BuildyRenderer;

    protected $content = [];
    protected $post_id = null;

    public function setContent(array $content)
    {
        $this->content = $content;

        return $this;
    }

    public function setId(int $post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    // public function __construct()
    // {

    //     // if (! $isGlobal) {
    //     //     return Cache::rememberForever("Buildy_BladeCache-ID:{$post_id}", function () use ($post_id) {
    //     //         $content = static::renderContent(static::getContentForId($post_id));

    //     //         if (class_exists(LSConf::class) && LSConf::val(LSBase::O_OPTM_HTML_MIN)) {
    //     //             $content = LSOptimizer::get_instance()->html_min($content);
    //     //         }

    //     //         return $content;
    //     //     });
    //     // }
    // }
}
