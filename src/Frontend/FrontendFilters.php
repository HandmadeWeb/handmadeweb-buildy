<?php

namespace HandmadeWeb\Buildy\Frontend;

use HandmadeWeb\Illuminate\Static\Filter;
use Illuminate\Support\Str;

class FrontendFilters
{
    /**
     * Define Filter/Method's to register.
     *
     * The $filters array expects the following format:
     * 'filter_name' => 'method_name',
     *
     * Where filter_name will be used by add_filter and apply_filter function calls.
     * And method_name is the public static function that should be called for that filter.
     *
     * $data will always be passed to these filters.
     *
     * @var array
     */
    protected static $filters = [
        'buildy_filter_all_data' => 'filter_all_data',
        'buildy_filter_type:section' => 'filter_sections',
        'buildy_filter_type:row' => 'filter_rows',
    ];

    public static function boot()
    {
        foreach (static::$filters as $filter_name => $method_name) {
            Filter::add($filter_name, [static::class, $method_name], 10, 1);
        }
    }

    public static function filter_all_data($data)
    {
        /**
         * Generate the Bootstrap col-X-X classes for the current loop.
         */
        $columns = '';
        if (! empty($data->options->columns)) {
            foreach ($data->options->columns as $key => $val) {
                if (! empty($val)) {
                    // Legacy -- XS no longer exists and is defaulted to just col-val
                    if ($key == 'xs') {
                        // This is for backwards compatibility
                        $columns .= "col-{$val} ";
                    } else {
                        $columns .= "col-{$key}-{$val} ";
                    }
                }
            }
            $columns = rtrim($columns, ' ');
        }

        /**
         * Generate the spacing classes (margin/paddings) for each breakpoint size.
         */
        $spacingClasses = '';

        foreach ($data->inline->margin ?? [] as $breakpoint => $direction) {
            if (! $breakpoint || ! $direction) {
                continue;
            }

            foreach ($direction as $name => $val) {
                if (! $name || $val !== 0 && ! $val) {
                    continue;
                }

                $first_char = substr($name, 0, 1);
                $marginClasses = ($breakpoint === 'xs' ? '' : "{$breakpoint}:")."m{$first_char}-{$val}";
                if (! empty($spacingClasses)) {
                    $spacingClasses .= " {$marginClasses}";
                } else {
                    $spacingClasses = $marginClasses;
                }
            }
        }

        foreach ($data->inline->padding ?? [] as $breakpoint => $direction) {
            if (! $breakpoint || ! $direction) {
                continue;
            }

            foreach ($direction as $name => $val) {
                if (! $name || $val !== 0 && ! $val) {
                    continue;
                }
                $first_char = substr($name, 0, 1);
                $paddingClasses = ($breakpoint === 'xs' ? '' : "{$breakpoint}:")."p{$first_char}-{$val}";
                if (! empty($spacingClasses)) {
                    $spacingClasses .= " {$paddingClasses}";
                } else {
                    $spacingClasses = $paddingClasses;
                }
            }
        }

        /*
        * Append data to $data->generatedAttributes // $bladeData->generatedAttributes on the view.
        */
        $data->generatedAttributes = (object) [
            'type' => str_replace('-module', '', $data->type),
            'columns' => $columns,
            'spacing' => $spacingClasses,
        ];

        if (! empty($data->options->moduleStyle)) {
            $data->generatedAttributes->template = Str::slug($data->options->moduleStyle);
        }

        return $data;
    }

    public static function filter_sections($data)
    {
        $inline_style = null;

        if (! empty($data->inline->backgroundColor)) {
            $inline_style .= " background-color: {$data->inline->backgroundColor};";
        }

        if (! empty($data->inline->backgroundImage->backgroundSize)) {
            $inline_style .= " background-size: {$data->inline->backgroundImage->backgroundSize};";
        }

        if (! empty($data->inline->backgroundImage->BlendMode)) {
            $inline_style .= " background-blend-mode: {$data->inline->backgroundImage->BlendMode};";
        }

        if (! empty($data->inline->backgroundImage->backgroundPosition)) {
            $inline_style .= " background-position: {$data->inline->backgroundImage->backgroundPosition};";
        }

        if (! empty($data->inline->backgroundImage->backgroundRepeat)) {
            $inline_style .= " background-repeat: {$data->inline->backgroundImage->backgroundRepeat};";
        }

        if (! empty($inline_style)) {
            $data->generatedAttributes->inline_style = $inline_style;
        }

        return $data;
    }

    public static function filter_rows($data)
    {
        // Example Filter.
        return $data;
    }
}
