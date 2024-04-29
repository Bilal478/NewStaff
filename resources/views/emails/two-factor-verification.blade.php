@component('mail::message')
# Two-Factor Verification Code

Hello,

Your verification code for {{ config('app.name') }} is: **{{ $verificationCode }}**

This code will expire in one minute. If you did not request this code, please ignore this email.

Thank you for using {{ config('app.name') }}.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
