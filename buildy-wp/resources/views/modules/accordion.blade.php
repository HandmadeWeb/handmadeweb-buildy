@extends('modules.common')

@php
    //  Accordion
    $items = $bladeData->content->accordion->items ?? null;
@endphp

 @section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    @if(!empty($items))
      @foreach($items as $index=>$item)
          <div class="accordion" @if($index === 0) open @endif>
              <div class="accordion-title" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">{{ $item->title }}</div>
              <div class="accordion-body" role="region">{!! $item->body !!}</div>
          </div>
      @endforeach
    @endif
 @overwrite
