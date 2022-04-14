@component('mail::message')
# We are sad to see you go 😔

Hello {{$user->name}}, you have successfully been unsubscribed
from {{$website->name}} you will no longer receive posts from
this website

Thanks,<br>
{{ config('app.name') }}
@endcomponent
