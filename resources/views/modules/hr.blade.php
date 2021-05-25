@extends('modules.common')

@section('content')
    <hr style="
    @if(!empty($bladeData->inline->backgroundColor))
        background-color: {{ $bladeData->inline->backgroundColor }}
    @endif
    @if(!empty($bladeData->inline->height))
        height: {{ $bladeData->inline->height }}
    @endif" class="bmcb-hr {{ $bladeData->attributes->class ?? null }}" />
@overwrite
