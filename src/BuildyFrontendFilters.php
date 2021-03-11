<?php

namespace HandmadeWeb\Buildy;

class BuildyFrontendFilters
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
        //'handmadeweb-buildy_filter_all_data' => 'filter_all_data',
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
        // Example Filter.
        return $data;
    }

    public static function filter_rows($data)
    {
        // Example Filter.
        return $data;
    }
}
