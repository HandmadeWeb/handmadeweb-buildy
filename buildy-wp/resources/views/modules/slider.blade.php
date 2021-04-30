@extends('modules.common')

@php
  //  Accordion
  $items = $bladeData->content->slider->items ?? null;
  $options = $bladeData->options->slider ?? null;
  // print_r($options);
@endphp

 @section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent

    <div class="bmcb-slider__slides"
    @if (isset($options))
      @foreach($options as $key=>$val)
        @if($val !== '')
          data-{{ $key }}="{{ $val ? $val : 'false' }}"
        @endif
      @endforeach
    @endif

    >
      @foreach($items as $index=>$item)
      @php
        $imageSize = (!empty($item->image->imageSize)) ? $item->image->imageSize : "full";
        $imageID = $item->image->imageID ?? null;
        @endphp
      <div class="bmcb-slider__slide" role="option" tabindex="-1" aria-selected="false" @if($index === 0) open @endif>
        @php echo wp_get_attachment_image($imageID, $imageSize, "", array('class' => 'bmcb-slider__slide-image')); @endphp
        @if (!empty($item->title) || !empty($item->body))
        <div class="bmcb-slider__slide-content">
          <div class="bmcb-slider__slide-title">{{ $item->title }}</div>
          <div class="bmcb-slider__slide-body" role="region">{!! $item->body !!}</div>
        </div>
        @endif
      </div>
      @endforeach
    </div>
    @if(!empty($bladeData->options->slider->arrow_nav))
      <div class="bmcb-slider__navigation-arrows">
        <div class="bmcb-slider__arrow-prev">
          <i class="fa fa-chevron-left"></i>
        </div>
        <div class="bmcb-slider__arrow-next">
          <i class="fa fa-chevron-right"></i>
        </div>
      </div>
    @endif
 @overwrite
