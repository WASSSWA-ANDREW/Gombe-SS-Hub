@extends('layouts.admin')

@section('content')
    {{ $slot }}
@endsection

@if (isset($header))
    @section('header')
        {{ $header }}
    @endsection
@endif
