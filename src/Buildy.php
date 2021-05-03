<?php

namespace HandmadeWeb\Buildy;

use HandmadeWeb\Buildy\Traits\ContentCollector;
use HandmadeWeb\Illuminate\Facades\Cache;
use HandmadeWeb\Illuminate\Facades\View;
use HandmadeWeb\Illuminate\Static\Filter;
use LiteSpeed\Base as LSBase;
use LiteSpeed\Conf as LSConf;
use LiteSpeed\Optimizer as LSOptimizer;

class Buildy
{
    use ContentCollector;

    public static function renderContentForId($post_id)
    {
        // return Cache::rememberForever("Buildy_BladeCache-ID:{$post_id}", function () use ($post_id) {
        //     $content = static::renderContent(static::getContentForId($post_id));

        //     if (class_exists(LSConf::class) && LSConf::val(LSBase::O_OPTM_HTML_MIN)) {
        //         $content = LSOptimizer::get_instance()->html_min($content);
        //     }

        //     return $content;
        // });

        return static::renderContent(static::getContentForId($post_id));
    }

    public static function renderContent(array $content)
    {
        $html = '';

        if (! empty($content)) {
            foreach ($content as $data) {
                if ($data->type === 'section-module') {
                    $html .= static::renderSection($data);
                } elseif ($data->type === 'global-module') {
                    $html .= static::renderGlobal($data);
                }
            }
        }

        return $html;
    }

    public static function renderGlobal($data)
    {
        return static::renderView(static::apply_filters($data));
    }

    public static function renderSection($data)
    {
        return static::renderView(static::apply_filters($data));
    }

    public static function renderRow($data)
    {
        return static::renderView(static::apply_filters($data));
    }

    public static function renderColumn($data)
    {
        return static::renderView(static::apply_filters($data));
    }

    public static function renderModule($data)
    {
        return static::renderView(static::apply_filters($data));
    }

    protected static function apply_filters($data)
    {
        if ($data->attributes->renderDisabled ?? false && ! empty($_GET['preview'])) {
            return;
        }

        $data = Filter::run('buildy_filter_all_data', $data);
        $data = Filter::run("buildy_filter_type:{$data->generatedAttributes->type}", $data);

        if (! empty($data->options->moduleStyle)) {
            $data = Filter::run("buildy_filter_template:{$data->options->moduleStyle}", $data);
        }

        return $data;
    }

    protected static function renderView($data)
    {
        if (empty($data)) {
            return;
        }

        $location = 'modules';
        if (in_array($data->generatedAttributes->type, ['section', 'row', 'column', 'global'])) {
            $location = 'layouts';
        }

        $locations = [];
        if (! empty($data->generatedAttributes->template)) {
            $locations[] = "{$location}.{$data->generatedAttributes->type}-{$data->generatedAttributes->template}";
        }
        $locations[] = "{$location}.{$data->generatedAttributes->type}";

        return View::first($locations, ['bladeData' => $data]);
    }
}
