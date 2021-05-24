<?php

namespace HandmadeWeb\Buildy;

use HandmadeWeb\Buildy\BuildyHelpers\BuildyContentCollector;
use HandmadeWeb\Buildy\BuildyHelpers\BuildyRenderer;
use HandmadeWeb\Illuminate\Facades\Cache;
use LiteSpeed\Base as LSBase;
use LiteSpeed\Conf as LSConf;
use LiteSpeed\Optimizer as LSOptimizer;

class Buildy
{
    use BuildyContentCollector;
    use BuildyRenderer;

    protected $content;

    public function __construct(array $content = [])
    {
        $this->content = $content;

        // if (! $isGlobal) {
        //     return Cache::rememberForever("Buildy_BladeCache-ID:{$post_id}", function () use ($post_id) {
        //         $content = static::renderContent(static::getContentForId($post_id));

        //         if (class_exists(LSConf::class) && LSConf::val(LSBase::O_OPTM_HTML_MIN)) {
        //             $content = LSOptimizer::get_instance()->html_min($content);
        //         }

        //         return $content;
        //     });
        // }
    }
}
