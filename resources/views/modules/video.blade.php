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
    $youtube_video_ID = array_shift($params);
    $youtube_params = http_build_query($params);
    $youtube_width = $bladeData->content->youtube->video_width ?? "100%";
    $youtube_height = $bladeData->content->youtube->video_height ?? 300;
    $youtube_allowParams = $bladeData->content->youtube->allow_params ?? false;
    $youtube_allowFullscreen = $bladeData->content->youtube->allow_fullscreen ?? false;

@endphp
@section('content')
  @if(!empty($youtube_video_ID))
    <iframe
      class="youtube-iframe"
      data-src="https://www.youtube.com/embed/{{ $youtube_video_ID }}{{ $youtube_params ? "?{$youtube_params}" : '' }}"
      width="{{ $youtube_width }}"
      height="{{ $youtube_height }}"
      frameborder="0"
      allow="{{ $youtube_allowParams }}"
      allowfullscreen>
    </iframe>
  @endif
@overwrite