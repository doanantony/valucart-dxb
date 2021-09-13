@extends('email.layout')

@section('title', 'Thank you for signing up.')

@section('content')

    <p>Hi {{ $customer->name }}, thank you for signing up with valucart.com. This is your email verification code: {{ $verification_code }}, the code will expire in 24 hours.</p>

@endsection
