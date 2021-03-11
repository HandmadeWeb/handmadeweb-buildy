@extends('modules.common')

@php
$type = $bladeData->content->post->postType;
$perpage = $bladeData->content->post->perPage;
$cols = $bladeData->content->post->columns;
$offset = $bladeData->content->post->offset;
$cat_in = $bladeData->content->post->includeCats;
$paged = $bladeData->content->post->enablePagination;

// Build up the attribute string
$atts = "";
    if(!empty($perpage)) : $atts .= "perpage='$perpage' "; endif;
    if(!empty($offset)) : $atts .= "offset='$offset' "; endif;
    if(!empty($cols)) : $atts .= "cols='$cols' "; endif;
    if(!empty($type)) : $atts .= "post_type='$type' "; endif;
    if(!empty($cat_in)) : $atts .= "cats='$cat_in' "; endif;
    if(!empty($paged)) : $atts .= "paged='$paged' "; endif;
@endphp

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    {!! "[list-posts $atts]" !!}
@overwrite
