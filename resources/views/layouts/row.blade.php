@php

$moduleClasses = '';
$moduleID = '';

if (!empty($bladeData->attributes)) {
  $moduleID = $bladeData->attributes->id ?? null;
  $moduleClasses = $bladeData->attributes->class ?? null;
  $internalLinkEnabled = $bladeData->attributes->in_page_link_enabled ?? false;
  $internalLinkText = $bladeData->attributes->in_page_link_text ?? null;
  $dataAtts = $bladeData->attributes->data ?? null;
}

$moduleStyle = !empty($bladeData->options) ? $bladeData->options->moduleStyle ?? null : null;

if (!empty($moduleStyle) && $moduleStyle !== 'none') {
  $moduleStyle = strtolower(preg_replace("/\s+/", "-", $moduleStyle));
  $moduleClasses .= " module-style__$moduleStyle";
}


if (!empty($bladeData->inline)) {
  $bgImageSize = $bladeData->inline->backgroundImage->imageSize ?? "full";
  $bgImageURL = $bladeData->inline->backgroundImage->url ?? null;
  $bgImageID = $bladeData->inline->backgroundImage->imageID ?? null;
  $bgBlendMode = $bladeData->inline->backgroundImage->BlendMode ?? null;

  // CSS GRID
  $enableCSSGrid = $bladeData->inline->cssGrid->enabled ?? null;
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

$dataAttString = null;

// Add data atts to a string
if (!empty($bladeData->attributes->data) && count($bladeData->attributes->data)) {
  foreach($bladeData->attributes->data as $dataAtt) {
    $name = strtolower($dataAtt->name);
    $value = stripslashes($dataAtt->value);
    $dataAttString .= " data-{$name}='{$value}' ";
  }
}


if (!empty($enableCSSGrid) && $enableCSSGrid) {
    $gridPrefix = "grid";
    !empty($moduleClasses) ? $moduleClasses .= " $gridPrefix" : $moduleClasses = $gridPrefix;

    // This will become e.g grid-4-8
    $colClass = $gridPrefix;

    foreach($bladeData->content as $column) {
        $colClass .= "-" . $column->options->columns->xl;
    }

    $moduleClasses .= " $colClass";
}

// Temporary large/small version of text align, I'll loop this eventually
if (!empty($bladeData->inline->textAlign->xs)) {
    !empty($moduleClasses) ? $moduleClasses .= " text-{$bladeData->inline->textAlign->xs}" : $moduleClasses = "text-{$bladeData->inline->textAlign->xs}";
}
if (!empty($bladeData->inline->textAlign->xl)) {
    !empty($moduleClasses) ? $moduleClasses .= " xl:text-{$bladeData->inline->textAlign->xl}" : $moduleClasses = "xl:text-{$bladeData->inline->textAlign->xl}";
}
/* Add responsive margin/padding classes if they're set */
if (!empty($bladeData->generatedAttributes->spacing)) {
    !empty($moduleClasses) ? $moduleClasses .= " {$bladeData->generatedAttributes->spacing}" : $moduleClasses = $bladeData->generatedAttributes->spacing;
}

@endphp

<div
    @isset($moduleID) id="{{ $moduleID }}" @endisset
    @if($bladeData->attributes->in_page_link_enabled ?? false)
      data-internal_link_enabled="true" @endif
    @isset($internalLinkText) data-internal_link_text="{{ $internalLinkText }}" @endisset
    class="bmcb-row row {{ isset($moduleClasses) ? $moduleClasses : '' }}"
    style="{{ $bladeData->generatedAttributes->inline_style ?? null }} 
    {{ !empty($bladeData->inline->cssGrid->gap) ? "--col-gap: {$bladeData->inline->cssGrid->gap};" : null }} 
    {{ !empty($bgImage) ? "background-image: url($bgImage);" : null }}"
    @if(!empty($dataAttString))
      {!! $dataAttString !!}
    @endif>
    @foreach($bladeData->content as $column)
      {!! \HandmadeWeb\Buildy\Buildy::renderColumn($column) !!}
    @endforeach
</div>
