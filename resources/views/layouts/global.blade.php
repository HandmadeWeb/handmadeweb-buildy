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

    // Text colours
    $colors = $bladeData->inline->color ?? null;
    if (!empty($colors)) {
        forEach($colors as $key=>$val) {
            if (strtolower($val) !== 'none') {
                if ($key !== 'xs') {
                    isset($moduleClasses) ? $moduleClasses .= " $key:text-$val" : $moduleClasses = "$key:text-$val";
                } else {
                    isset($moduleClasses) ? $moduleClasses .= " text-$val" : $moduleClasses = "text-$val";
                }
            }
        }
    }
@endphp

@if(!empty($bladeData->content->id))
  <div id="{{ $bladeData->generatedAttributes->id ?? null }}"

      class="bmcb-global-wrapper {{ $bladeData->generatedAttributes->classes ?? null }} {{ $moduleClasses ?? null }}"

      style="{{ $bladeData->generatedAttributes->inline_style ?? null }} {{ !empty($bgImage) ? "background-image: url($bgImage);" : null }}"

      {{ $bladeData->generatedAttributes->data_attributes ?? null }}
  >
    @buildyRenderContentForId($bladeData->content->id)
  </div>
@endif