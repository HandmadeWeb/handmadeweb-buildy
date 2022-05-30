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
}