@extends('modules.common')

@php
    $location = $bladeData->content->map->location ?? null;
    $body_text = $bladeData->content->body ?? null;
    $API_KEY = get_field('google_api_key', 'option') ?? null;
@endphp

@section('content')
  @if (!empty($location) && !empty($API_KEY))
    <iframe
      class="google-map__embed"
      v-if="location"
      width="100%"
      height="400"
      frameborder="0" style="border:0"
      src="https://www.google.com/maps/embed/v1/place?key={{ $API_KEY }}&q={{ $location }}" allowfullscreen>
    </iframe>
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    @if($body_text)
      <div class="bmcb-map__description">{!! $body_text !!}</div>
    @endif
  @endif
@overwrite
