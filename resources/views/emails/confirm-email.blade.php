@component('mail::message')
# One Last Step

We just need you to confirm your meail address to prove that you're a human.

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm your Email address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
