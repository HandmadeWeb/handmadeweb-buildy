@extends('2.modules.common')

@section('content')
    @component('2.modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    {!! $bladeData->content->body ?? null !!}
@overwrite
