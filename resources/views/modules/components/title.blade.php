@php
  $title = $bladeData->content->title->value ?? null;
  $default_level = $default ?? 'h3';
  $heading_level = $bladeData->content->title->level ?? $default_level;
  $color = $bladeData->content->title->color ?? null;
  $title_id = $bladeData->content->title->id ?? null;
  $title_classes = $bladeData->content->title->class ?? "";

  if (isset($bladeData->generatedAttributes->type)) {
    $title_classes .= " bmcb-{$bladeData->generatedAttributes->type}__title";
  }

  if (!empty($headingColorClass)) {
    $title_classes .= " text-$headingColorClass";
  }

  if(!empty($color)) {
      if (strpos($color, '#') !== false) {
          $styleAtts = "color: $color;";
      } else {
          $title_classes .= ' text-' . strtolower($color);
      }
  }
@endphp
@if($title)
  <{{ $heading_level }}
  @if(!empty($title_id)) id='{{ $title_id }}' @endif
  @if(!empty($title_classes)) class='{{ $title_classes }}' @endif
  @if(!empty($styleAtts)) style='{{ $styleAtts }}' @endif>
    {!! $title !!}
  </{{ $heading_level }}>
@endif
