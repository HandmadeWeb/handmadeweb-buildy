@extends('modules.common')

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    @if(!empty($bladeData->content->body))
        {!! $bladeData->content->body !!}
    @endif
@overwrite
