@extends('email.layout')

@section('title', 'Verify your email address.')

@section('content')

    <p>Hi {{ $customer->name }}, this is your email verification code: {{ $verification_code }}. This code will expire in 24 hours.</p>

@endsection
