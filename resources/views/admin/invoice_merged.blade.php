<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Накладная</title>
    <style>
        body *
        {
            font-family: Courier New;
            border: 0;
            border-spacing: 0;
            font-size: 12pt;
        }

        body table {
            width: 100%;
        }

        .products
        {
            border-collapse: collapse;
            border-color: Black;
            border-style: solid;
            border-width: 3px;
            width: 100%;
        }

        .products tbody
        {
            border-collapse: collapse;
        }

        .products tr
        {
            border-color: Black;
            border-style: Solid;
        }

        .products tr td
        {
            border-style: Solid;
            border-width: 1px;
            border-color: Black;
            padding:5px;
        }
        .container {
            display: block;
            position: relative;
            width: 100%;
        }
        .first{
            position: relative;
            display: block;
            margin-bottom: 20px;
        }
        .first:last-child {
            margin-top:20px;
        }

        .left-post{
            float:left;
        }

        .text {
            display: flex;
            align-content: left;
            justify-content: left;
            flex-direction: column;
            padding-left:20px;
        }
        .below p:nth-child(2) {
            font-weight: 700;
        }
        .hr {
            border:1px solid silver;
        }
        .sign {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top:30px;
        }
        .sign .left, .sign .right {
            width: 50%;
            height: 10px;
        }
        .underline {
            display: inline-block;
            background-color: black;
            width: 50%;
            border: 1px solid #4c4c4c;
            margin-left:30px;
        }
    </style>
</head>
<body>
<h1>Товарная накладная № {{ $order->cnum }} от {{ $order->created_at->format('d.m.Y') }}</h1>
<hr>
<div class="container">
    <div class="first">
        <div class="left-post">
            <span>Поставщик: </span>
        </div>
        <span class="text">Индивидуальный предприминатель Лапкин Михаил Иванович, ОГРН 309312301200013,<br/> свидетельство № 31 001943621 от 12.01.09г.<br/>, тел. (4722) 31-61-37<br/>ИНН 312303519509</span>
    </div>
    <div class="first">
        <div class="left-post">
            <span>Покупатель:</span>
        </div>
        <span class="text">{{ $order->user->legal_name }}</span>
    </div>
    <table>
        <tbody><tr>
            <td colspan="2" style="text-align: center">
                <table class="products">
                    <tbody><tr>
                        <td>№</td>
                        <td>Товар</td>
                        <td>Кол-во</td>
                        <td>Ед.</td>
                        <td>Цена</td>
                        <td>Сумма</td>
                    </tr>
                    @foreach($plist as $item)
                        <tr>
                            <td align="center">
                                {{ $loop->iteration }}
                            </td>
                            <td align="left">
                                {{ $item->name }}
                            </td>
                            <td align="right">
                                {{ $item->count }}
                            </td>
                            <td align="left">
                                шт.
                            </td>
                            <td align="right">
                                {{ number_format($item->price_opt, 0, ',', ' ') }}
                            </td>
                            <td align="right">
                                {{ number_format($item->price_opt * $item->count, 0, ',', ' ') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody></table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;">
                Итого:&nbsp;{{ number_format($total_sum, 0, ',', ' ') }}
            </td>
        </tr>

        {{--<tr>
            <td colspan="2" style="text-align: right;">
                В том числе НДС: 1233
            </td>
        </tr>--}}
        </tbody>
    </table>
    <div class="below">
        <p>Всего наименований {{ count($plist) }},  на сумму {{ number_format($total_sum, 0, ',', ' ') }} Руб</p>
        <p>({{ mb_strtoupper(mb_substr($ntw->getCurrencyTransformer('ru')->toWords($total_sum_ntw, 'RUB'), 0, 1)) . mb_substr($ntw->getCurrencyTransformer('ru')->toWords($total_sum_ntw, 'RUB'), 1) }} 00 копеек)</p>
    </div>
    <hr class="hr">
    <div class="sign">
        <div class="left">Отпустил <span class="underline"></span></div>
        <div class="right">Получил <span class="underline"></span></div>
    </div>
</div>
</body>
</html>