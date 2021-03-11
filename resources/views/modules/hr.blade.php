@extends('modules.common')

@php
    $moduleClasses = $bladeData->attributes->class ?? null;
    $color = $bladeData->inline->backgroundColor ?? null;
    $height = $bladeData->inline->height ?? null;
@endphp

@section('content')
    <hr style="
    @if(!empty($color))
        background-color: {{ $color }}
    @endif
    @if(!empty($height))
        height: {{ $height }}
    @endif" class="bmcb-hr {{ !empty(($moduleClasses)) ? $moduleClasses : '' }}" />
@overwrite
