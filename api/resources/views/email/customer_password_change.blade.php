@extends('email.layout')

@section('title', 'You password has been changed.')

@section('content')

    <p>Hi {{ $customer->name }}, you password has been changed. If you are not the one who changed your password please report this to customer-service@valucart.com.</p>

@endsection
