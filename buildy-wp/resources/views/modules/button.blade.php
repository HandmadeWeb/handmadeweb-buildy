@extends('modules.common')

@php
    $buttonURL = (string) $bladeData->content->button->url ?? null;
    if (isset($buttonURL) && preg_match("/^\d+$/", $buttonURL)) {
        $buttonURL = get_permalink($buttonURL);
    }
    $color = $bladeData->content->button->colour ?? null;
    $bgColor = $bladeData->content->button->backgroundColor ?? null;
    $target = $bladeData->content->button->target ?? null;
    $size = ($bladeData->content->button->size && $bladeData->content->button->size !== 'Initial') ? $bladeData->content->button->size ?? null : null;
    $manualStyle = $bladeData->content->button->manualStyle ?? null;
    $buttonStyle = $bladeData->content->button->buttonStyle ?? null;
    $manualStylesSet = $manualStyle || isset($bgColor) || isset($buttonStyle);
@endphp

@section('content')
    <a
    class="btn
    @if($manualStylesSet)
      @if(isset($bgColor))
          bg-{{ $bgColor }}
      @endif
      @isset($color)
        text-{{ $color }}
      @endisset
    @elseif(isset($buttonStyle)) 
      btn--{{ $buttonStyle }}      
    @endif    
    @isset($size)
        btn--{{ $size }}
    @endisset
    "
    @isset($target)
        target="{{ $target }}"
    @endisset
    href="{{ isset($buttonURL) ? $buttonURL : '#' }}">{{ $bladeData->content->button->text }}</a>
@overwrite
