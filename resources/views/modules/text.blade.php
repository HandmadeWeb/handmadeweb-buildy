@extends('modules.common')

@section('content')
    <div class="bmcb-text__description">{!! $bladeData->content->body ?? null !!}</div>
@overwrite
