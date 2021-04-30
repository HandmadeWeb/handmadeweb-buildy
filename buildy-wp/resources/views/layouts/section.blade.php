@php

$atts = $bladeData->attributes ?? null;

$moduleClasses = "";
$moduleID = "";

$internalLinkEnabled = false;

if (!empty($atts)) {
  $moduleID = $bladeData->attributes->id ?? null;
  $moduleClasses = $bladeData->attributes->class ?? null;
  $internalLinkEnabled = $bladeData->attributes->in_page_link_enabled ?? null;
  $internalLinkText = $bladeData->attributes->in_page_link_text ?? null;
  $dataAtts = $bladeData->attributes->data ?? null;
}

$options = $bladeData->options ?? null;

if (!empty($options)) {
  $moduleStyle = $bladeData->options->moduleStyle ?? null ?? null;
  $boxed = (!empty($bladeData->options->layout_boxed) && $bladeData->options->layout_boxed) ? 'container' : 'container-fluid';
}

if (!empty($moduleStyle) && $moduleStyle !== 'none') {
  $moduleStyle = strtolower(preg_replace("/\s+/", "-", $moduleStyle));
  $moduleClasses .= " module-style__$moduleStyle";
}

$inline = $bladeData->inline ?? null;

if (!empty($inline)) {
  $bgSize = $bladeData->inline->backgroundImage->backgroundSize ?? "";
  $bgPosition = $bladeData->inline->backgroundImage->backgroundPosition ?? "";
  $bgRepeat = $bladeData->inline->backgroundImage->backgroundRepeat ?? null;
  $bgColor = $bladeData->inline->backgroundColor ?? "";
  $bgImageSize = $bladeData->inline->backgroundImage->imageSize ?? "full";
  $bgBlendMode = $bladeData->inline->backgroundImage->BlendMode ?? null;
  $bgImageURL = $bladeData->inline->backgroundImage->url ?? null;
  $bgImageID = $bladeData->inline->backgroundImage->imageID ?? null;
}

if ((empty($bgImageID) && !empty($bgImageURL)) && function_exists('attachment_url_to_postid')) {
  $bgImageID = attachment_url_to_postid( $bgImageURL );
}

if (!empty($bgImageID)) {
  $bgImageURL = wp_get_attachment_image_url( $bgImageID, $bgImageSize);
  $bgImage = $bgImageURL;
}

$internalLinkTarget = !empty($internalLinkText) ? preg_replace("/\W|_/",'',$internalLinkText) : null;
if (isset($internalLinkTarget)) {
  $moduleID = $internalLinkTarget;
}

$spacing = $bladeData->generatedAttributes->spacing ?? null;
$dataAttString = null;

// Add data atts to a string
if (!empty($dataAtts)) {
  foreach($dataAtts as $dataAtt) {
    $name = strtolower($dataAtt->name);
    $value = stripslashes($dataAtt->value);
    $dataAttString .= " data-{$name}='{$value}' ";
  }
}

/* Add responsive margin/padding classes if they're set */
if (!empty($spacing)) {
    !empty($moduleClasses) ? $moduleClasses .= " $spacing" : $moduleClasses = $spacing;
}
@endphp

{{-- @include('widgets.WP_Widget_Categories') --}}

<div
    @isset($moduleID) id="{{ $moduleID }}" @endisset
    @if(!empty($internalLinkEnabled) && $internalLinkEnabled)
        data-internal_link_enabled="true"
    @endif
    @isset($internalLinkText) data-internal_link_text="{{ $internalLinkText }}" @endisset
    class="bmcb-section {{ $boxed ? $boxed : '' }} {{ isset($moduleClasses) ? $moduleClasses : '' }}"
    style="
    @if(!empty($bgColor)) {{ "background-color: $bgColor;" }} @endif
    @if(!empty($bgImage)) {{ "background-image: url($bgImage);" }} @endif
    @if(!empty($bgSize)) {{ "background-size: $bgSize;" }} @endif
    @if(!empty($bgBlendMode)) {{ "background-blend-mode: $bgBlendMode;" }} @endif
    @if(!empty($bgPosition)) {{ "background-position: $bgPosition;" }} @endif
    @if(!empty($bgRepeat)) {{ "background-repeat: $bgRepeat;" }} @endif"
    @if(!empty($dataAttString))
      {!! $dataAttString !!}
    @endif>
    @if (!empty($options) ? $options->inner_container ?? false : false)
        <div class="container">
    @endif
        {!! $buildy->renderContent($bladeData->content) !!}
    @if (!empty($options) ? $options->inner_container ?? false : false)
        </div>
    @endif
</div>
