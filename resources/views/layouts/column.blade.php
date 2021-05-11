@php

$atts = $bladeData->attributes ?? null;

if (!empty($atts)) {
  $moduleID = $atts->id ?? null;
  $moduleClasses = $atts->class ?? null;
  $dataAtts = $atts->data ?? null;
}

$spacing = $bladeData->generatedAttributes->spacing ?? null;
$dataAttString = null;

$inline = $bladeData->inline ?? null;

$moduleStyle = !empty($bladeData->options) ? $bladeData->options->moduleStyle ?? null : null;

if (!empty($moduleStyle) && $moduleStyle !== 'none') {
  $moduleStyle = strtolower(preg_replace("/\s+/", "-", $moduleStyle));
  $moduleClasses .= " module-style__$moduleStyle";
}

if (!empty($inline)) {
  $bgImage = $bladeData->inline->backgroundImage ?? null;
  $bgColor = $bladeData->inline->backgroundColor ?? "";
}

if (!empty($bgImage)) {
  $bgSize = $bgImage->backgroundSize ?? "";
  $bgPosition = $bgImage->backgroundPosition ?? "";
  $bgRepeat = $bgImage->backgroundRepeat ?? null;
  $bgImageSize = $bgImage->imageSize ?? "full";
  $bgImageURL = $bgImage->url ?? null;
  $bgImageID = $bgImage->imageID ?? null;
  $bgBlendMode = $bgImage->BlendMode ?? null;
}


if ((empty($bgImageID) && !empty($bgImageURL)) && function_exists('attachment_url_to_postid')) {
  $bgImageID = attachment_url_to_postid( $bgImageURL );
}

if (!empty($bgImageID)) {
  $bgImageURL = wp_get_attachment_image_url( $bgImageID, $bgImageSize ?? 'full');
  $bgImage = $bgImageURL;
}

if (!empty($dataAtts)) {
  foreach($dataAtts as $dataAtt) {
    $name = strtolower($dataAtt->name);
    $value = stripslashes($dataAtt->value);
    $dataAttString .= " data-{$name}='{$value}' ";
  }
}

/* Add responsive margin/padding classes if they are set */
if (!empty($spacing)) {
    !empty($moduleClasses) ? $moduleClasses .= " $spacing" : $moduleClasses = $spacing;
}

@endphp

<div @isset($moduleID) id="{{ $moduleID }}" @endisset
    class="bmcb-column col {{ $bladeData->generatedAttributes->columns }} {{ !empty($moduleClasses) ? $moduleClasses : null }}"
    style="{{ $bladeData->generatedAttributes->inline_style ?? null }} 
    {{ !empty($bgImage) ? "background-image: url($bgImage);" : null }}"
    @if(!empty($dataAttString))
      {!! $dataAttString !!}
    @endif>@foreach($bladeData->content as $module)
      {!! \HandmadeWeb\Buildy\Buildy::renderModule($module) !!}
    @endforeach</div>
