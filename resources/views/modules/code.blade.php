@extends('modules.common')

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    {!! $bladeData->content->body ?? null !!}
@overwrite
