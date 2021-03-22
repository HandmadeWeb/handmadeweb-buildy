<?php

namespace HandmadeWeb\Buildy\Traits;

use HandmadeWeb\Illuminate\Facades\View;

trait ContentRenderer
{
    /**
     * Undocumented function.
     *
     * @param [type] $content
     * @return string
     */
    public static function renderContent($content): string
    {
        $html = '';

        if (! empty($content)) {
            foreach ($content as $data) {
                if ($data->attributes->renderDisabled ?? false && ! empty($_GET['preview'])) {
                    continue;
                }

                $data = apply_filters('handmadeweb-buildy_filter_all_data', $data);

                /**
                 * str_replace text-module to text.
                 * Check if returned module type is section, row or column.
                 * If it is then specify that the Blade file location is in the layouts folder.
                 * Otherwise it is located in the modules folder.
                 */
                $type = str_replace('-module', '', $data->type);

                $template = $data->options->moduleStyle ?? null;

                if (! empty($template)) {
                    $data = apply_filters("handmadeweb-buildy_filter_template:{$template}", $data);
                }

                if (! empty($template)) {
                    $template = static::seoUrl($template);
                }

                $location = 'modules';

                if (in_array($type, ['section', 'row', 'column'])) {
                    $location = 'layouts';
                }

                $data = apply_filters("handmadeweb-buildy_filter_type:{$type}", $data);

                $locations = [];
                if (! empty($template)) {
                    $locations[] = "{$location}.{$type}-{$template}";
                }
                $locations[] = "{$location}.{$type}";

                $html .= View::first($locations, ['bladeData' => $data]);
            }
        }

        /*
         * Run do_shortcode on the returned HTML just incase any modules had any shortcode in them.
         */
        return do_shortcode($html);
    }

    /**
     * Undocumented function.
     *
     * @param [type] $string
     * @return string
     */
    public static function seoUrl($string): string
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", '', $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", ' ', $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", '-', $string);

        return $string;
    }
}
