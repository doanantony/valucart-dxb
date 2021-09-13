@extends('email.layout')

@section('title', 'You password has been changed.')

@section('content')

    <p>
        Hi {{ $customer->name }}, your valucart account information has been changed.
        If you are not the one who changed your information please report this to customer-service@valucart.com.
    </p>

    @if (!is_null($email_verification_code))
        <p>Please verify you email, your verification code is: <strong>{{ $email_verification_code }}</strong>. This code will expire in 24 hours.</p>
    @endif

@endsection
