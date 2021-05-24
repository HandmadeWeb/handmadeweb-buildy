<?php

namespace HandmadeWeb\Buildy\BuildyHelpers;

use HandmadeWeb\Illuminate\Facades\View;
use HandmadeWeb\Illuminate\Filter;

trait BuildyRenderer
{
    protected $withFilters = true;

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $html = '';

        if (! empty($this->content)) {
            foreach ($this->content as $data) {
                if ($data->type === 'section-module') {
                    $html .= $this->renderSection($data);
                } elseif ($data->type === 'global-module') {
                    $html .= $this->renderGlobal($data);
                }
            }
        }

        return $html;
    }

    /* Parts */
    public function renderGlobal($data)
    {
        return $this->renderView($this->apply_filters($data));
    }

    public function renderSection($data)
    {
        return $this->renderView($this->apply_filters($data));
    }

    public function renderRow($data)
    {
        return $this->renderView($this->apply_filters($data));
    }

    public function renderColumn($data)
    {
        return $this->renderView($this->apply_filters($data));
    }

    public function renderModule($data)
    {
        return $this->renderView($this->apply_filters($data));
    }

    /* // Parts */

    protected function renderView($data)
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

        // Fallback if no view exists.
        $locations[] = 'moduleNotFound';

        return View::first($locations, ['buildy' => $this, 'bladeData' => $data])->render();
    }

    public function withFilters(bool $withFilters = true)
    {
        $this->withFilters = $withFilters;

        return $this;
    }

    protected function apply_filters($data)
    {
        if ($this->withFilters) {
            if ($data->attributes->renderDisabled ?? false && ! empty($_GET['preview'])) {
                return;
            }

            $data = Filter::run('buildy_filter_all_data', $data);
            $data = Filter::run("buildy_filter_type:{$data->generatedAttributes->type}", $data);

            if (! empty($data->options->moduleStyle)) {
                $data = Filter::run("buildy_filter_template:{$data->options->moduleStyle}", $data);
            }
        }

        return $data;
    }
}
