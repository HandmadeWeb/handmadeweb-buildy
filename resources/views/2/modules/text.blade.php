@extends('2.modules.common')

@section('content')
    @component('2.modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    <div class="bmcb-text__description">{!! $bladeData->content->body ?? null !!}</div>
@overwrite
