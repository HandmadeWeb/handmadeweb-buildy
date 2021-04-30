@php

$atts = $bladeData->attributes ?? null;

$moduleClasses = '';
$moduleID = '';

$internalLinkEnabled = false;

if (!empty($atts)) {
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

$spacing = !empty($bladeData->generatedAttributes) ? $bladeData->generatedAttributes->spacing : null;
$columns = collect($bladeData->content);

$inline = $bladeData->inline ?? null;

if (!empty($inline)) {
  $textAlignxs = (string) ($bladeData->inline->textAlign->xs ?? null);
  $textAlignlg = (string) ($bladeData->inline->textAlign->xl ?? null);

  $bgSize = $bladeData->inline->backgroundImage->backgroundSize ?? "";
  $bgPosition = $bladeData->inline->backgroundImage->backgroundPosition ?? "";
  $bgRepeat = $bladeData->inline->backgroundImage->backgroundRepeat ?? null;
  $bgColor = $bladeData->inline->backgroundColor ?? "";

  $bgImageSize = $bladeData->inline->backgroundImage->imageSize ?? "full";
  $bgImageURL = $bladeData->inline->backgroundImage->url ?? null;
  $bgImageID = $bladeData->inline->backgroundImage->imageID ?? null;
  $bgBlendMode = $bladeData->inline->backgroundImage->BlendMode ?? null;

  // CSS GRID
  $enableCSSGrid = $bladeData->inline->cssGrid->enabled ?? null;
  $cssGridGap = $bladeData->inline->cssGrid->gap ?? null;
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
if (!empty($dataAtts)) {
  foreach($dataAtts as $dataAtt) {
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

    foreach($columns as $column) {
        $colClass .= "-" . $column->options->columns->xl;
    }

    $moduleClasses .= " $colClass";
}

// Temporary large/small version of text align, I'll loop this eventually
if (!empty($textAlignxs)) {
    !empty($moduleClasses) ? $moduleClasses .= " text-$textAlignxs" : $moduleClasses = "text-$textAlignxs";
}
if (!empty($textAlignlg)) {
    !empty($moduleClasses) ? $moduleClasses .= " xl:text-$textAlignlg" : $moduleClasses = "xl:text-$textAlignlg";
}
/* Add responsive margin/padding classes if they're set */
if (!empty($spacing)) {
    !empty($moduleClasses) ? $moduleClasses .= " $spacing" : $moduleClasses = $spacing;
}

@endphp

<div
    @isset($moduleID) id="{{ $moduleID }}" @endisset
    @if($internalLinkEnabled)
      data-internal_link_enabled="true" @endif
    @isset($internalLinkText) data-internal_link_text="{{ $internalLinkText }}" @endisset
    class="bmcb-row row {{ isset($moduleClasses) ? $moduleClasses : '' }}"
    style="
    @if(!empty($cssGridGap)) {{ "--col-gap: $cssGridGap;" }} @endif
    @if(!empty($bgColor)) {{ "background-color: $bgColor;" }} @endif
    @if(!empty($bgImage)) {{ "background-image: url($bgImage);" }} @endif
    @if(!empty($bgSize)) {{ "background-size: $bgSize;" }} @endif
    @if(!empty($bgBlendMode)) {{ "background-blend-mode: $bgBlendMode;" }} @endif
    @if(!empty($bgPosition)) {{ "background-position: $bgPosition;" }} @endif
    @if(!empty($bgRepeat)) {{ "background-repeat: $bgRepeat;" }} @endif"
    @if(!empty($dataAttString))
      {!! $dataAttString !!}
    @endif>
    {!! $buildy->renderContent($bladeData->content) !!}
</div>
