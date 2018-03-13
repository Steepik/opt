@component('mail::message')
# Здравствуйте, {{ $order->user->name }}!
Модерация проверила заказ №{{ $order->cnum }} на наличие шин на складе.<br/>
Пожалуйста, проверьте статус заказа на сайте.
<br/>
@component('mail::promotion')
    | Наименование  | Кол-во        | Итого  |
    | ------------- |:-------------:| --------:|
    | {{ $product->name }} | {{ $order->count }} | {{ $order->count * $product->price_opt }} |
@endcomponent

@component('mail::button', ['url' => url('/order/' . $order->id)])
Открыть заказ
@endcomponent

C уважением, {{ config('app.name') }}
@endcomponent
