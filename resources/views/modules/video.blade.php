@extends('modules.common')

@php
    // $map = [
    //     "primary" => "primary",
    //     "success" => "green",
    //     "info" => "lightgray",
    //     "warning" => "orange",
    //     "danger" => "red",
    //     "light" => "lightgray",
    //     "dark" => "gray",
    //     "link" => "link"
    // ];

    $youtube_url = $bladeData->content->youtube->video_url ?? null;
    parse_str(parse_url($youtube_url)['query'], $params);
    $youtube_video_ID = $params['v'];
    $youtube_width = $bladeData->content->youtube->video_width ?? "100%";
    $youtube_height = $bladeData->content->youtube->video_height ?? 300;
    $youtube_allowParams = $bladeData->content->youtube->allow_params ?? false;
    $youtube_allowFullscreen = $bladeData->content->youtube->allow_fullscreen ?? false;

@endphp

@section('content')
  @if(!empty($youtube_video_ID))
    <iframe
      width="{{ $youtube_width }}"
      height="{{ $youtube_height }}"
      src="https://www.youtube.com/embed/{{ $youtube_video_ID }}"
      frameborder="0"
      allow="{{ $youtube_allowParams }}"
      {{-- @if($youtube_allowFullscreen) allowfullscreen @endif --}}
      allowfullscreen>
    </iframe>
  @endif
@overwrite
