<div id="{{ $bladeData->generatedAttributes->id ?? null }}"

    {{ !empty($bladeData->attributes->in_page_link_enabled) ? ' data-internal_link_enabled="true" ' : null }}

    {{ !empty($bladeData->attributes->in_page_link_text) ? ' data-internal_link_text="'.$bladeData->attributes->in_page_link_text.'" ' : null }}

    class="bmcb-row row {{ $bladeData->generatedAttributes->classes ?? null }}"

    style="{{ $bladeData->generatedAttributes->inline_style ?? null }}"

    {{ $bladeData->generatedAttributes->data_attributes ?? null }}
>
    @foreach($bladeData->content as $column)
        {!! $buildy->renderColumn($column) !!}
    @endforeach
</div>
