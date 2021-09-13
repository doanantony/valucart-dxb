Hi {{ $customer->name }}, your valucart account information has been changed.
If you are not the one who changed your information please report this to customer-service@valucart.com.

@if (!is_null($email_verification_code))
    Please verify you email, your verification code is: {{ $email_verification_code }}. This code will expire in 24 hours.
@endif