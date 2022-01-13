@component('mail::message')
# Introduction

reset pass za {{$user->name}}

@component('mail::button', ['url' => '/'])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
