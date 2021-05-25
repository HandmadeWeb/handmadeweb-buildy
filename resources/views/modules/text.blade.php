@extends('modules.common')

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    <div class="bmcb-text__description">{!! $bladeData->content->body ?? null !!}</div>
@overwrite