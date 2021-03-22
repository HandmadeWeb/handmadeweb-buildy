@extends('modules.common')

@section('content')
    {{-- If the heading level has been selected use that level of heading --}}
    @component('modules.components.title', ['bladeData'=> $bladeData, 'default' => 'h1', 'is_heading' => true])@endcomponent
@overwrite
