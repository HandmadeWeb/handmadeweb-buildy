<div id="{{ $bladeData->generatedAttributes->id ?? null }}"

    {{ !empty($bladeData->attributes->in_page_link_enabled) ? ' data-internal_link_enabled="true" ' : null }}

    {{ !empty($bladeData->attributes->in_page_link_text) ? ' data-internal_link_text="'.$bladeData->attributes->in_page_link_text.'" ' : null }}
    
    class="bmcb-section {{ $bladeData->generatedAttributes->classes ?? null }}"

    style="{{ $bladeData->generatedAttributes->inline_style ?? null }}"

    {{ $bladeData->generatedAttributes->data_attributes ?? null }}
>
    @if ($bladeData->options->inner_container ?? false)
        <div class="container">
    @endif
        @foreach($bladeData->content as $row)
            {!! $buildy->renderRow($row) !!}
        @endforeach
    @if ($bladeData->options->inner_container ?? false )
        </div>
    @endif
</div>