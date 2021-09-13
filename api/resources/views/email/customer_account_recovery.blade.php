@extends('email.layout')

@section('title', 'Recover you account.')

@section('content')

    <p>Hi {{ $customer->name }}, use code: {{ $code }}, to recover you valucart account.</p>

@endsection
