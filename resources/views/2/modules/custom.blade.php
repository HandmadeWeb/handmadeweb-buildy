@extends('2.modules.common')

@section('content')
    @component('2.modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    <div class="bmcb-custom__description">{!! $bladeData->content->body ?? null !!}</div>
@overwrite
