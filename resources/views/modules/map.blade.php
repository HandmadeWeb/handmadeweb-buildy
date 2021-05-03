@extends('modules.common')

@php
    $API_KEY = get_field('google_api_key', 'option');
@endphp

@section('content')
  @if (!empty($bladeData->content->map->location) && !empty($API_KEY))
    <iframe
      class="google-map__embed"
      v-if="location"
      width="100%"
      height="400"
      frameborder="0" style="border:0"
      src="https://www.google.com/maps/embed/v1/place?key={{ $API_KEY }}&q={{ $bladeData->content->map->location }}" allowfullscreen>
    </iframe>
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    @if(!empty($bladeData->content->body))
      <div class="bmcb-map__description">{!! $bladeData->content->body !!}</div>
    @endif
  @endif
@overwrite
