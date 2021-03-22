@extends('modules.common')

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    @if(!empty($bladeData->content->accordion->items))
      @foreach($bladeData->content->accordion->items ?? [] as $index => $item)
          <div class="accordion" @if($index === 0) open @endif>
              <div class="accordion-title" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">{{ $item->title }}</div>
              <div class="accordion-body" role="region">{!! $item->body !!}</div>
          </div>
      @endforeach
    @endif
 @overwrite
