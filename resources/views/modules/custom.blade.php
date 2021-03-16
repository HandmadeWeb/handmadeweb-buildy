@extends('modules.common')

@php
//Content
$bodyContent = $bladeData->content->body ?? null;
@endphp

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    <div class="bmcb-custom__description">{!! $bodyContent !!}</div>
@overwrite
