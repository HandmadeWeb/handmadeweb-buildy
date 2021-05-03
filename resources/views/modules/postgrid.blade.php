@extends('modules.common')

@php
    // Build up the attribute string
    $atts = "";
    !empty($bladeData->content->post->perPage) : $atts .= "perpage='{$bladeData->content->post->perPage}' " : null;
    !empty($bladeData->content->post->offsett) : $atts .= "offset='{$bladeData->content->post->offset}' " : null;
    !empty($bladeData->content->post->columns) : $atts .= "cols='{$bladeData->content->post->columns}' " : null;
    
    !empty($bladeData->content->post->postType) ? $atts .= "post_type='{$bladeData->content->post->postType}' " : null;
    !empty($bladeData->content->post->includeCats) : $atts .= "cats='{$bladeData->content->post->includeCats}' " : null;
    !empty($bladeData->content->post->enablePagination) : $atts .= "paged='{$bladeData->content->post->enablePagination}' " : null;
@endphp

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    {!! "[list-posts $atts]" !!}
@overwrite
