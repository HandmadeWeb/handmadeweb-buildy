@extends('modules.common')

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    <nav class="tabs-menu"  >
        <ul class="tabs-menu-tabs">
        @foreach($bladeData->content->tabs->items ?? [] as $index=>$item)
            <li class="tabs-menu-item @if($index === 0) is-active @endif"><a href="#{{ str_replace(' ', '-', $item->title) }}" class="tab-title">{{ $item->title }}</a></li>
        @endforeach
        </ul>
    </nav>
    <div class="tabs-content">
        @foreach($bladeData->content->tabs->items ?? [] as $index=>$item)
            <div class="tab-body @if($index === 0) is-active @endif" data-target="{{ str_replace(' ', '-', $item->title) }}" >{!! $item->body !!}</div>
        @endforeach
    </div>
@overwrite