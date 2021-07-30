

{{--
/**
 * @version 1.0.0
 * @since   1.0.0
 */
 --}}

@php
  //timer
  $timer = $bladeData->content->timer ?? null;

  $dataAtts = [];
  
  $dataAtts[0]['name'] = 'number-rollup-start';
  $dataAtts[0]['value'] = $timer->start ?? 0;
  $dataAtts[1]['name'] = 'number-rollup-end';
  $dataAtts[1]['value'] = $timer->end ?? 0;
  $dataAtts[2]['name'] = 'number-rollup-duration';
  $dataAtts[2]['value'] = $timer->duration ?? 3000;

  $dataAtts = json_encode($dataAtts);
  $dataAtts = json_decode($dataAtts, FALSE);

@endphp

@extends('modules.common', ['customAtts' => $dataAtts, 'customClasses' => 'number-rollup'])

@section('content')
    @component('modules.components.title', ['bladeData'=> $bladeData])@endcomponent
    {{ $timer->start }}
@overwrite

