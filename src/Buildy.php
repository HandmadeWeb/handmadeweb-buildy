<?php

namespace HandmadeWeb\Buildy;

use HandmadeWeb\Buildy\Traits\ContentCollector;
use HandmadeWeb\Buildy\Traits\ContentRenderer;
use HandmadeWeb\Illuminate\Facades\Cache;
use HandmadeWeb\Illuminate\Facades\View;
use LiteSpeed\Base as LSBase;
use LiteSpeed\Conf as LSConf;
use LiteSpeed\Optimizer as LSOptimizer;

class Buildy
{
    use ContentCollector;
    use ContentRenderer;

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

    public static function renderContent(array $content): string
    {
        $html = '';

        if (! empty($content)) {
            foreach ($content as $data) {
                if ($data->attributes->renderDisabled ?? false && ! empty($_GET['preview'])) {
                    continue;
                }

                if ($data->type === 'section-module') {
                    $html .= static::renderSection($data);
                } elseif ($data->type === 'global-module') {
                    //$html .= '[buildy-global '.compact($data).']';
                    $html .= static::renderGlobal($data);
                }
            }
        }

        return $html;
    }

    public static function renderGlobal($data): string
    {
        $data = static::apply_filters($data);

        return view('modules.global', ['bladeData' => $data])->render();
    }

    public static function renderSection($data): string
    {
        $type = str_replace('-module', '', $data->type);
        $data = static::apply_filters($data);

        $template = $data->options->moduleStyle ?? null;

        if (! empty($template)) {
            $data = apply_filters("handmadeweb-buildy_filter_template:{$template}", $data);
        }

        if (! empty($template)) {
            $template = static::seoUrl($template);
        }

        $location = 'layouts';

        $locations = [];
        if (! empty($template)) {
            $locations[] = "{$location}.{$type}-{$template}";
        }
        $locations[] = "{$location}.{$type}";

        return View::first($locations, ['bladeData' => $data]);
    }

    public static function renderRow($data): string
    {
        $data = static::apply_filters($data);

        return view('layouts.row', ['bladeData' => $data])->render();
    }

    public static function renderColumn($data): string
    {
        $data = static::apply_filters($data);

        return view('layouts.column', ['bladeData' => $data])->render();
    }

    public static function renderModule($data): string
    {
        $type = str_replace('-module', '', $data->type);
        $data = static::apply_filters($data);

        return view("modules.{$type}", ['bladeData' => $data])->render();
    }

    protected static function apply_filters($data)
    {
        $type = str_replace('-module', '', $data->type);
        $data = apply_filters('handmadeweb-buildy_filter_all_data', $data);
        $data = apply_filters("handmadeweb-buildy_filter_type:{$type}", $data);

        return $data;
    }
}
