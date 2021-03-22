@extends('modules.common')

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    <div class="bmcb-custom__description">{!! $bladeData->content->body ?? null !!}</div>
@overwrite
