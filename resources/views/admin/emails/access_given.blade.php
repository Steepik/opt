@component('mail::message')
# Здравствуйте, {{ $user->name }}!

@component('mail::panel')
Модерация рассмотрела Вашу информацию и дала доступ к оптовой платформе.
@endcomponent

@component('mail::button', ['url' => config('app.url'), 'color' => 'green'])
Перейти на сайт
@endcomponent

С уважением, {{ config('app.name') }}

@endcomponent
