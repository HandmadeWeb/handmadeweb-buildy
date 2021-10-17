@extends('modules.common')

@php
  if (!function_exists('getVimeoVideoIdFromUrl')) {
    function getVimeoVideoIdFromUrl($url = '') {
      $regs = array();
      $id = '';
      if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
        $id = $regs[3];
      }
      return $id;
    }
  }

    $video_url = $bladeData->content->youtube->video_url ?? null;

    $video_width = $bladeData->content->youtube->video_width ?? "100%";
    $video_height = $bladeData->content->youtube->video_height ?? 300;
    $video_allowParams = $bladeData->content->youtube->allow_params ?? false;
    $video_allowFullscreen = $bladeData->content->youtube->allow_fullscreen ?? false;

    // Check if video is youtube or vimeo
    if (strpos($video_url, 'youtube') !== false) {
      parse_str(parse_url($video_url)['query'], $params);
      $video_params = http_build_query($params);
      $video_ID = array_shift($params);
      $embed_url = "https://www.youtube.com/embed/{$video_ID}";
        if ($video_params) {
          $embed_url .= "?{$video_params}";
        }
    } elseif (strpos($video_url, 'vimeo') !== false) {
      $video_ID = getVimeoVideoIdFromUrl($video_url);
      $embed_url = "https://player.vimeo.com/video/{$video_ID}";
    } else {
        $video_type = null;
    }
    
@endphp
@section('content')
  @if(!empty($video_ID))
    <iframe
      class="youtube-iframe"
      data-src="{{ $embed_url }}"
      width="{{ $video_width }}"
      height="{{ $video_height }}"
      frameborder="0"
      allow="{{ $video_allowParams }}"
      allowfullscreen>
    </iframe>
  @endif
@overwrite