<?php

namespace HandmadeWeb\Buildy\Frontend;

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
        'handmadeweb-buildy_filter_all_data' => 'filter_all_data',
        'handmadeweb-buildy_filter_type:row' => 'filter_rows',
    ];

    public static function boot()
    {
        foreach (static::$filters as $filter_name => $method_name) {
            add_filter($filter_name, [static::class, $method_name], 10, 1);
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
        $margins = collect($data->inline->margin ?? []);
        $paddings = collect($data->inline->padding ?? []);
        $spacingClasses = '';

        foreach ($margins as $breakpoint => $direction) {
            $direction = collect($direction ?? []);
            if (! $breakpoint || ! $direction) {
                continue;
            }
            foreach ($direction as $name => $val) {
                if (! $name || $val !== 0 && ! $val) {
                    continue;
                }
                $first_char = substr($name, 0, 1);
                $marginClasses = ($breakpoint === 'xs' ? '' : "{$breakpoint}:")."m{$first_char}-{$val}";
                if (isset($spacingClasses)) {
                    $spacingClasses .= " {$marginClasses}";
                } else {
                    $spacingClasses = $marginClasses;
                }
            }
        }

        foreach ($paddings as $breakpoint => $direction) {
            $direction = collect($direction);

            if (! $breakpoint || ! $direction) {
                continue;
            }

            foreach ($direction as $name => $val) {
                if (! $name || $val !== 0 && ! $val) {
                    continue;
                }
                $first_char = substr($name, 0, 1);
                $paddingClasses = ($breakpoint === 'xs' ? '' : "{$breakpoint}:")."p{$first_char}-{$val}";
                if (isset($spacingClasses)) {
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
            'columns' => $columns,
            'spacing' => $spacingClasses,
        ];

        return $data;
    }

    public static function filter_rows($data)
    {
        // Example Filter.
        return $data;
    }
}
