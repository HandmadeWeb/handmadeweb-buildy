 @php

$atts = $bladeData->attributes ?? null;
$template = $bladeData->options->template ?? null;
$moduleClasses = "";
$moduleID = "";

$internalLinkEnabled = false;

if (!empty($atts)) {
  $moduleID = $bladeData->attributes->id ?? null;
  $moduleClasses .= $bladeData->attributes->class ?? null;
  $dataAtts = $bladeData->attributes->data ?? null;
  $internalLinkEnabled = $bladeData->attributes->in_page_link_enabled ?? null;
  $internalLinkText = $bladeData->attributes->in_page_link_text ?? null;
}

$options = $bladeData->options ?? null;
$moduleStyle = $options ? $bladeData->options->moduleStyle ?? null : null;

if (isset($moduleStyle) && $moduleStyle !== 'none') {
  $moduleStyle = strtolower(preg_replace("/\s+/", "-", $moduleStyle));
  $moduleClasses .= " module-style__$moduleStyle";
}

$dataAttString = null;

// Add data atts to a string
if (isset($dataAtts)) {
  foreach($dataAtts as $dataAtt) {
    $name = strtolower($dataAtt->name);
    $value = stripslashes($dataAtt->value);
    $dataAttString .= " data-{$name}='{$value}' ";
  }
}

$inline = $bladeData->inline ?? null;

if (!empty($inline)) {
  $colors = $bladeData->inline->color ?? null;
  $textAlign = $bladeData->inline->textAlign ?? null;
  $bgSize = $bladeData->inline->backgroundImage->backgroundSize ?? "";
  $bgPosition = $bladeData->inline->backgroundImage->backgroundPosition ?? "";
  $bgRepeat = $bladeData->inline->backgroundImage->backgroundRepeat ?? null;
  $bgColor = $bladeData->inline->backgroundColor ?? "";
  $bgImageSize = $bladeData->inline->backgroundImage->imageSize ?? "full";
  $bgImageURL = $bladeData->inline->backgroundImage->url ?? null;
  $bgImageID = $bladeData->inline->backgroundImage->imageID ?? null;
}

if ((!isset($bgImageID) && isset($bgImageURL)) && function_exists('attachment_url_to_postid')) {
  $bgImageID = attachment_url_to_postid( $bgImageURL );
}

if (isset($bgImageID)) {
  $bgImageURL = wp_get_attachment_image_url( $bgImageID, $bgImageSize);
  $bgImage = $bgImageURL;
}


// Temporary large/small version of text align, I'll loop this eventually
if (isset($textAlign)) {
    foreach($textAlign as $bp=>$val) {
        if (!empty($val)) {
            if ($bp === 'xs') {
                isset($moduleClasses) ? $moduleClasses .= " text-$val" : $moduleClasses = "text-$val";
            } else {
                isset($moduleClasses) ? $moduleClasses .= " $bp:text-$val" : $moduleClasses = "$bp:text-$val";
            }
        }
    }
}

$moduleType = str_replace("-module", '', $bladeData->type);
if (!empty($moduleType)) {
  if (!empty($template)) {
    $moduleClasses .= " bmcb-{$moduleType} bmcb-{$moduleType}--{$template}";
  } else {
    $moduleClasses .= " bmcb-{$moduleType}";
  }
}
$internalLinkTarget = isset($internalLinkText) ? preg_replace("/\W|_/",'',$internalLinkText) : null;
if (isset($internalLinkTarget)) {
  $moduleID = $internalLinkTarget;
}

$spacing = $bladeData->generatedAttributes->spacing ?? null;

/* Add responsive margin/padding classes if they're set */
if ($spacing) {
    isset($moduleClasses) ? $moduleClasses .= " $spacing" : $moduleClasses = $spacing;
}

if (isset($colors)) {
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

if (isset($customClasses)) {
  $moduleClasses .= " " . trim($customClasses);
}

 @endphp
<div
    {{-- If ID is set --}}
    @isset($moduleID) id="{{ $moduleID }}" @endisset

    {{-- If Internal Link is set (override ID) --}}
    @if(isset($internalLinkEnabled) && $internalLinkEnabled)
        data-internal_link_enabled="true"
        @isset($internalLinkText)
            data-internal_link_text="{{ $internalLinkText }}"
        @endisset
    @endif

    @if($bladeData->options->isToggle ?? null)
    data-isToggle
    @endif

    {{-- Classes --}}
    class="bmcb-module {{ isset($moduleClasses) ? $moduleClasses : '' }}
    @yield('class')"

    style="
    @if(!empty($bgColor)) {{ "background-color: $bgColor;" }} @endif
    @if(!empty($bgImage)) {{ "background-image: url($bgImage);" }} @endif
    @if(!empty($bgSize)) {{ "background-size: $bgSize;" }} @endif
    @if(!empty($bgPosition)) {{ "background-position: $bgPosition;" }} @endif
    @if(!empty($bgRepeat)) {{ "background-repeat: $bgRepeat;" }} @endif"

    @if($moduleType === 'slider' || $moduleType === 'accordion' || $moduleType === 'tab')
      role="listbox"
    @endif

    {{-- Data Attributes --}}
    @if($dataAttString)
      {!! $dataAttString !!}
    @endif>
    @yield('content')
</div>
