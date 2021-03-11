@php
$moduleClasses = $bladeData->attributes->class ?? "";
$spacing = $bladeData->generatedAttributes->spacing ?? null;
$dataAtts = $bladeData->attributes->data ?? null;
$dataAttString = null;

$bgSize = $bladeData->inline->backgroundImage->backgroundSize ?? "";
$bgPosition = $bladeData->inline->backgroundImage->backgroundPosition ?? "";
$bgRepeat = $bladeData->inline->backgroundImage->backgroundRepeat ?? null;
$bgColor = $bladeData->inline->backgroundColor ?? "";
$bgImageSize = $bladeData->inline->backgroundImage->imageSize ?? "full";
$bgImageURL = $bladeData->inline->backgroundImage->url ?? null;
$bgImageID = $bladeData->inline->backgroundImage->imageID ?? null;

$moduleStyle = !empty($bladeData->options) ? $bladeData->options->moduleStyle ?? null : null;

if (!empty($moduleStyle) && $moduleStyle !== 'none') {
  $moduleStyle = strtolower(preg_replace("/\s+/", "-", $moduleStyle));
  $moduleClasses .= " module-style__$moduleStyle";
}

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
// Add data atts to a string
if (!empty($dataAtts)) {
  foreach($dataAtts as $dataAtt) {
    $name = strtolower($dataAtt->name);
    $value = stripslashes($dataAtt->value);
    $dataAttString .= " data-{$name}='{$value}' ";
  }
}

/* Add responsive margin/padding classes if they're set */
if ($spacing) {
    !empty($moduleClasses) ? $moduleClasses .= " $spacing" : $moduleClasses = $spacing;
}
@endphp
@if(!empty($bladeData->content->id))
    <div
    style="
    @if(!empty($bgColor)) {{ "background-color: $bgColor;" }} @endif
    @if(!empty($bgImage)) {{ "background-image: url($bgImage);" }} @endif
    @if(!empty($bgSize)) {{ "background-size: $bgSize;" }} @endif
    @if(!empty($bgPosition)) {{ "background-position: $bgPosition;" }} @endif
    @if(!empty($bgRepeat)) {{ "background-repeat: $bgRepeat;" }} @endif"
    class="bmcb-global-wrapper {{ isset($moduleClasses) ? $moduleClasses : '' }}"
    @if(!empty($dataAttString))
      {!! $dataAttString !!}
    @endif>
      {!! $buildy->renderFrontend($bladeData->content->id) !!}
    </div>
@endif
