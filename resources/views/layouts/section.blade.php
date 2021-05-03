@php

$moduleClasses = "";
$moduleID = "";

if (!empty($bladeData->attributes)) {
  $moduleID = $bladeData->attributes->id ?? null;
  $moduleClasses = $bladeData->attributes->class ?? null;
  $internalLinkText = $bladeData->attributes->in_page_link_text ?? null;
}

if (!empty($bladeData->options)) {
  $moduleStyle = $bladeData->options->moduleStyle ?? null ?? null;
}

if (!empty($moduleStyle) && $moduleStyle !== 'none') {
  $moduleStyle = strtolower(preg_replace("/\s+/", "-", $moduleStyle));
  $moduleClasses .= " module-style__$moduleStyle";
}

if (!empty($bladeData->inline)) {
  $bgImageURL = $bladeData->inline->backgroundImage->url ?? null;
  $bgImageID = $bladeData->inline->backgroundImage->imageID ?? null;
}

if ((empty($bgImageID) && !empty($bgImageURL)) && function_exists('attachment_url_to_postid')) {
  $bgImageID = attachment_url_to_postid( $bgImageURL );
}

if (!empty($bgImageID)) {
  $bgImageURL = wp_get_attachment_image_url( $bgImageID, $bladeData->inline->backgroundImage->imageSize ?? 'full');
  $bgImage = $bgImageURL;
}

$internalLinkTarget = !empty($internalLinkText) ? preg_replace("/\W|_/",'',$internalLinkText) : null;
if (isset($internalLinkTarget)) {
  $moduleID = $internalLinkTarget;
}

$dataAttString = null;

// Add data atts to a string
if (!empty($bladeData->attributes->data)) {
  foreach($bladeData->attributes->data ?? [] as $dataAtt) {
    $dataAttString .= ' data-'.strtolower($dataAtt->name).'='.stripslashes($dataAtt->value).' ';
  }
}

/* Add responsive margin/padding classes if they're set */
if (!empty($bladeData->generatedAttributes->spacing)) {
    !empty($moduleClasses) ? $moduleClasses .= " {$bladeData->generatedAttributes->spacing}" : $moduleClasses = $bladeData->generatedAttributes->spacing;
}
@endphp

<div
    @isset($moduleID) id="{{ $moduleID }}" @endisset
    @if($bladeData->attributes->in_page_link_enabled ?? false)
        data-internal_link_enabled="true"
    @endif
    @isset($internalLinkText) data-internal_link_text="{{ $internalLinkText }}" @endisset
    class="bmcb-section {{ (!empty($bladeData->options->layout_boxed) && $bladeData->options->layout_boxed) ? 'container' : 'container-fluid' }} {{ isset($moduleClasses) ? $moduleClasses : '' }}"
    style="
    @if(!empty($bladeData->inline->backgroundColor)) {{ "background-color: {$bladeData->inline->backgroundColor};" }} @endif
    @if(!empty($bgImage)) {{ "background-image: url($bgImage);" }} @endif
    @if(!empty($bladeData->inline->backgroundImage->backgroundSize)) {{ "background-size: {$bladeData->inline->backgroundImage->backgroundSize};" }} @endif
    @if(!empty($bladeData->inline->backgroundImage->BlendMode)) {{ "background-blend-mode: {$bladeData->inline->backgroundImage->BlendMode};" }} @endif
    @if(!empty($bladeData->inline->backgroundImage->backgroundPosition)) {{ "background-position: {$bladeData->inline->backgroundImage->backgroundPosition};" }} @endif
    @if(!empty($bladeData->inline->backgroundImage->backgroundRepeat)) {{ "background-repeat: {$bladeData->inline->backgroundImage->backgroundRepeat};" }} @endif"
    @if(!empty($dataAttString))
      {!! $dataAttString !!}
    @endif>
    @if ($bladeData->options->inner_container ?? false)
        <div class="container">
    @endif
    @foreach($bladeData->content as $row)
      {!! \HandmadeWeb\Buildy\Buildy::renderRow($row) !!}
    @endforeach
    @if ($bladeData->options->inner_container ?? false )
        </div>
    @endif
</div>
