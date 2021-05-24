@php
    $bgImageSize = $bladeData->inline->backgroundImage->imageSize ?? "full";
    $bgImageURL = $bladeData->inline->backgroundImage->url ?? null;
    $bgImageID = $bladeData->inline->backgroundImage->imageID ?? null;

    if ((!empty($bgImageID) && empty($bgImageURL)) && function_exists('attachment_url_to_postid')) {
    $bgImageID = attachment_url_to_postid( $bgImageURL );
    }

    if (!empty($bgImageID)) {
        $bgImageURL = wp_get_attachment_image_url( $bgImageID, $bgImageSize);
        $bgImage = $bgImageURL;
    }
@endphp

<div id="{{ $bladeData->generatedAttributes->id ?? null }}"

    {{ !empty($bladeData->attributes->in_page_link_enabled) ? ' data-internal_link_enabled="true" ' : null }}

    {{ !empty($bladeData->attributes->in_page_link_text) ? ' data-internal_link_text="'.$bladeData->attributes->in_page_link_text.'" ' : null }}

    class="bmcb-row row {{ $bladeData->generatedAttributes->classes ?? null }}"

    style="{{ $bladeData->generatedAttributes->inline_style ?? null }}"

    {{ $bladeData->generatedAttributes->data_attributes ?? null }}
>
    @foreach($bladeData->content as $column)
        @buildyRenderColumn($column)
    @endforeach
</div>
