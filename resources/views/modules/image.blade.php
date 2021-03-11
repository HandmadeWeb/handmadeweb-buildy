@extends('modules.common')

@php
    $module_link_url = $bladeData->options->module_link->url ?? null;
    $module_link_new_tab = $bladeData->options->module_link->new_tab ?? null;

    $width = $bladeData->content->image->width ?? null;
    if (!empty($width)) {
      $width = "width: $width;";
    }

    $maxWidth = $bladeData->content->image->maxWidth ?? null;
    if (!empty($maxWidth)) {
      $maxWidth = "max-width: $maxWidth;";
    }

    $height = $bladeData->content->image->height ?? null;
    if (!empty($height)) {
      $height = "height: $height;";
    }

    $maxHeight = $bladeData->content->image->maxHeight ?? null;
    if (!empty($maxHeight)) {
      $maxHeight = "max-height: $maxHeight;";
    }

    $objectFit = $bladeData->content->image->objectFit ?? null;
    if (!empty($objectFit)) {
      $objectFit = "object-fit: $objectFit;";
    }

    $objectPosition = $bladeData->content->image->objectPosition ?? null;
    if (!empty($objectPosition)) {
      $objectPosition = "object-position: $objectPosition;";
    }

    $imageURL = $bladeData->content->image->url ?? null;
    $imageSize = $bladeData->content->image->imageSize ?? "full";
    $imageID = $bladeData->content->image->imageID ?? null;

    if ((!$imageID && $imageURL) && function_exists('attachment_url_to_postid')) {
      $imageID = attachment_url_to_postid( $imageURL );
    }
@endphp

@section('content')
    @if(!empty($module_link_url))
        <a @if($module_link_new_tab) target="_blank" @endif href="{{ $module_link_url }}">
    @endif
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    @if(!empty($imageID) && function_exists('wp_get_attachment_image'))
        @php echo wp_get_attachment_image($imageID, $imageSize, "", array( "class" => "bmcb-image", "style" => "$width $maxWidth $height $maxHeight $objectFit $objectPosition" )); @endphp
    @else
        <img style="{{ $width }} {{ $maxWidth }} {{ $height }} {{ $objectFit }} {{ $objectPosition }}"
        @if(!empty($imageURL)) src="{{ $imageURL }}" @endif />
    @endif
    @if(!empty($module_link_url))
        </a>
    @endif
@overwrite
