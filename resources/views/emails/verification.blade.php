@component('mail::message')
# Email Verification

Please enter code below to verify your email.

@component('mail::panel')
    {{ $code }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
