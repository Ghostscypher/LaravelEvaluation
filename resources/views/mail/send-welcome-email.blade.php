@component('mail::message')
# Thanks for subscribing 🥳

Hello {{$user->name}}, thank you for subscribing to posts from {{$website->name}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
