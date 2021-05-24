<div id="{{ $bladeData->generatedAttributes->id ?? null }}"

    class="bmcb-column col {{ $bladeData->generatedAttributes->classes ?? null }}"

    style="{{ $bladeData->generatedAttributes->inline_style ?? null }} {{ !empty($bgImage) ? "background-image: url($bgImage);" : null }}"

    {{ $bladeData->generatedAttributes->data_attributes ?? null }}
>
    @foreach($bladeData->content as $module)
        @buildyRenderModule($module)
    @endforeach
</div>
