<?php

namespace HandmadeWeb\Buildy\Frontend;

use HandmadeWeb\Illuminate\Filter;
use Illuminate\Support\Str;

class FrontendFilters
{
    /**
     * Define Filter/Method's to register.
     *
     * The $filters array expects the following format:
     * 'filter_name' => 'method_name',
     *
     * Where filter_name will be used by Filter::add() and Filter::run() function calls.
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
        //'buildy_filter_type:column' => 'filter_columns',
        //'buildy_filter_type:global' => 'filter_globals',
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
            'id' => $data->attributes->id ?? null,
            'classes' => '',
            'type' => str_replace('-module', '', $data->type),
            'columns' => $columns,
            'spacing' => $spacingClasses,
        ];

        // Classes
        if (! empty($data->attributes->class)) {
            $data->generatedAttributes->classes = $data->attributes->class;
        }

        if (! empty($data->options->moduleStyle) && $data->options->moduleStyle !== 'none') {
            $data->generatedAttributes->classes .= ' module-style__'.strtolower(preg_replace("/\s+/", '-', $data->options->moduleStyle));
        }
        /* Add responsive margin/padding classes if they're set */
        if (! empty($data->generatedAttributes->spacing)) {
            $data->generatedAttributes->classes .= " {$data->generatedAttributes->spacing}";
        }
        // /Classes

        if (! empty($data->attributes->in_page_link_text)) {
            $data->generatedAttributes->id = preg_replace("/\W|_/", '', $data->attributes->in_page_link_text);
        }

        if (! empty($data->options->moduleStyle)) {
            $data->generatedAttributes->template = Str::slug($data->options->moduleStyle);
        }

        // Inline Style
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

        // Inline Image
        if (! empty($data->inline)) {
            $bgImageURL = $data->inline->backgroundImage->url ?? null;
            $bgImageID = $data->inline->backgroundImage->imageID ?? null;
        }

        if (! empty($data->inline->backgroundImage->imageID)) {
            $bgImageID = $data->inline->backgroundImage->imageID;
        } elseif (! empty($data->inline->backgroundImage->url) && function_exists('attachment_url_to_postid')) {
            $bgImageID = attachment_url_to_postid($bgImageURL);
        }

        if (! empty($bgImageID)) {
            $bgImage = wp_get_attachment_image_url($bgImageID, $data->inline->backgroundImage->imageSize ?? 'full');
        }

        if (! empty($bgImage)) {
            $inline_style .= " background-image: url($bgImage);";
        }
        // /Inline Image

        if (! empty($inline_style)) {
            $data->generatedAttributes->inline_style = $inline_style;
        }
        // /Inline Style

        // Data Attributes
        if (! empty($data->attributes->data) && is_iterable($data->attributes->data)) {
            $data->generatedAttributes->data_attributes = '';

            foreach ($data->attributes->data as $dataAtt) {
                $data->generatedAttributes->data_attributes .= ' data-'.strtolower($dataAtt->name).'='.stripslashes($dataAtt->value).' ';
            }
            $data->generatedAttributes->data_attributes = trim($data->generatedAttributes->data_attributes);
        }
        // /Data Attributes

        return $data;
    }

    public static function filter_sections($data)
    {
        $data->generatedAttributes->classes .= ($data->options->layout_boxed ?? null) ? ' container' : ' container-fluid';

        return $data;
    }

    public static function filter_rows($data)
    {
        if ($data->inline->cssGrid->enabled ?? false) {
            $gridPrefix = 'grid';

            $data->generatedAttributes->classes .= " {$gridPrefix}";

            // This will become e.g grid-4-8
            $colClass = $gridPrefix;

            foreach ($data->content as $column) {
                if (! empty($column->options->columns->xl)) {
                    $colClass .= '-'.$column->options->columns->xl;
                }
            }

            $data->generatedAttributes->classes .= " {$colClass}";
        }

        if (! empty($data->inline->textAlign->xs)) {
            $data->generatedAttributes->classes .= " text-{$data->inline->textAlign->xs}";
        }
        if (! empty($data->inline->textAlign->xl)) {
            $data->generatedAttributes->classes .= " xl:text-{$data->inline->textAlign->xl}";
        }

        return $data;
    }

    // public static function filter_columns($data)
    // {
    //     // Example Filter.
    //     return $data;
    // }

    // public static function filter_globals($data)
    // {
    //     // Example Filter.
    //     return $data;
    // }
}
