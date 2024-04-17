@component('mail::message')
# Two-Factor Verification Code

Your verification code is: {{ $verificationCode }}

This code will expire in one minute.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
