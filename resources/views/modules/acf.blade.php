@extends('modules.common')

@php
$ID = $bladeData->content->acfForm->post_id;
$field_groups = $bladeData->content->acfForm->field_groups;
@endphp

@section('content')

Create template file under child theme: /buildy-views/modules/acf-{{ $field_groups[0] }}.blade.php

@overwrite