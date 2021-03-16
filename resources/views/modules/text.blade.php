@extends('modules.common')

 {{-- @section('class', 'spud') --}}

 @php
 //Content
  $bodyContent = $bladeData->content->body ?? null;
 @endphp

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    <div class="bmcb-text__description">{!! $bodyContent !!}</div>
@overwrite
