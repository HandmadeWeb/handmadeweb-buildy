
@php
  $module_link_url = $bladeData->options->module_link->url ?? null;
  $images = $bladeData->content->gallery->images ?? null;
  $gap = isset($bladeData->content->gallery->columnGap) ? "gap: {$bladeData->content->gallery->columnGap};" : "gap: 2rem";
  $cols = !empty($bladeData->content->gallery->columnCount) ? "grid-{$bladeData->content->gallery->columnCount}" : 'grid-3';
  $imageSize = $bladeData->content->gallery->imageSize ?? 'full';
  
  $is_slider = !empty($bladeData->content->gallery->isSlider) ? "bmcb-slider" : false;
  $options = $bladeData->options->slider ?? null;
  
  $is_masonry = $bladeData->content->gallery->isMasonry ?: false;

  $masonry_marginX = $bladeData->options->masonry->marginX ?: false;
  $masonry_marginY = $bladeData->options->masonry->marginY ?: false;

  $gallery_items_class = "";  

  if ($is_masonry) {
    $gallery_items_class = "is-masonry";
  }

  elseif (!$is_slider) {
    $gallery_items_class = "grid";
  }
 
@endphp

@extends('modules.common', ["customClasses" => "{$is_slider}"])

@section('content')
    @if(!empty($module_link_url))
        <a @if($module_link_new_tab) target="_blank" @endif href="{{ $module_link_url }}">
    @endif

    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent

    @if(!empty($images))
    <div class="grid-sizer"></div>
      <div 
        class="bmcb-gallery__items {{ $gallery_items_class }} {{ $cols }}" 
        @if(!empty($gap) && !$is_slider) 
          style="{{ $gap }}" 
        @endif

        @if($is_masonry) 
        @if($masonry_marginX) data-marginx="{{ $masonry_marginX }}" @endif
        @if($masonry_marginY) data-marginy="{{ $masonry_marginY }}" @endif
        @endif

        @if (isset($options) && $is_slider)
          @foreach($options as $key=>$val)
            @if($val !== '')
              data-{{ $key }}="{{ $val ? $val : 'false' }}"
            @endif
          @endforeach
        @endif

      >
        @foreach($images as $image)
          @if(!$is_slider)
            <a class="bmcb-gallery__item" href="<?= wp_get_attachment_url($image->id); ?>">
              <?= wp_get_attachment_image($image->id, $imageSize, "", array( "class" => "bmcb-image"  )); ?>
            </a>
          @else
            <?= wp_get_attachment_image($image->id, $imageSize, "", array( "class" => "bmcb-image bmcb-gallery__item bmcb-gallery__item--slide"  )); ?>
          @endif
        @endforeach
      </div>
    @endif

    @if(!empty($module_link_url))
        </a>
    @endif
@overwrite