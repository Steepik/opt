<html><head>
    <title>Счет № {{ $products['cnum'] }}</title>

    <style type="text/css">
        body *
        {
            font-family: Courier New;
            border: 0;
            border-spacing: 0;
            font-size: 12pt;
        }

        body table
        {
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
        }
        .a-l
        {
            text-align: left;
        }
    </style>
<body style="margin: auto; padding: 10px;">

<table>
    <tbody><tr>
        <td colspan="2" style="font-size: 1.7em; font-weight: bold; text-align: center;">
            ВНИМАНИЕ! ПЕРЕЧИСЛЕНИЯ ОСУЩЕСТВЛЯТЬ СТРОГО ПО<br>
            РЕКВИЗИТАМ, УКАЗАННЫМ В ДАННОМ СЧЕТЕ!
        </td>
    </tr>




    <tr>
        <td align="right">Поставщик&nbsp;&nbsp;:</td>
        <td align="left">&nbsp;<b>ИП Лапкин М.И.</b></td>
    </tr>
    <tr>
        <td align="right">ИНН&nbsp;&nbsp;:</td>
        <td align="left">&nbsp;312303519509&nbsp;&nbsp;ОКПО 0162784635</td>
    </tr>
    <tr>
        <td align="right">Адрес&nbsp;&nbsp;:</td>
        <td align="left">&nbsp;308001, г.Белгород, ул.Волчанская, д.139, кор.5 </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            Телефон/Факс&nbsp;&nbsp;:
        </td>
        <td class="a-l">&nbsp;+7(4722) 414-494</td>
    </tr>
    <tr>
        <td style="text-align: right;">
            Банковские реквизиты&nbsp;&nbsp;:
        </td>
        <td class="a-l">
            &nbsp;Р/С 40802810100020000475<br>
            &nbsp;В ПАО УКБ "Бегородсоцбанк" г.Белгород&nbsp;&nbsp;БИК 041403701
        </td>
    </tr>
    </tbody></table>

<br>
<br>

<table style="width: 100%">
    <tbody><tr>
        <td colspan="2" style="text-align: center;">
            СЧЕТ N <b>{{ $products['cnum'] }}</b> от
            {{ $products['time'] }}.				<b>
                оплатить до
                {{ $products['uptime'] }} г.
            </b>
        </td>
    </tr>
    </tbody></table>

<br>
<br>

<table style="width: 95%">
    <tbody><tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;Плательщик: <b>{{ Auth::user()->name }}</b></td>
        <td style="text-align: right;">Тел.:<b>+7(4722) 414-494</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    </tbody></table>

<table>
    <tbody><tr>
        <td colspan="2" style="text-align: center">
            <table class="products">
                <tbody><tr>
                    <td>Наименование</td>
                    <td>Код САЕ</td>
                    <td>Ед</td>
                    <td>Кол</td>
                    <td>Цена</td>
                    <td>Сумма, руб</td>
                </tr>
                @foreach($products['products'] as $product)
                    <tr>
                        <td>
                            {{ $product->name }}
                        </td>
                        <td>
                            {{ $product->tcae }}
                        </td>
                        <td>
                            шт.
                        </td>
                        <td>
                            {{ $product->count }}
                        </td>
                        <td>
                            {{ number_format($product->price_percent, 0, ',', ' ') }}
                        </td>
                        <td>
                            {{ number_format($product->price_percent * $product->count, 0, ',', ' ') }}
                        </td>
                    </tr>
                @endforeach
                </tbody></table>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: right;">
            Итого:&nbsp;{{ number_format($products['price'], 0) }}&nbsp;
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: right;">
            <?php $nds = (($products['price']) / 1.18) * 0.18; ?>
            В том числе НДС: {{ number_format($nds, 2) }}&nbsp;
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: left;">
            <?php $sum = $products['price'] * 100; ?>
            &nbsp;&nbsp;&nbsp;&nbsp;Всего к оплате:&nbsp;{{ mb_strtoupper(mb_substr($ntw->getCurrencyTransformer('ru')->toWords($sum, 'RUB'), 0, 1)) . mb_substr($ntw->getCurrencyTransformer('ru')->toWords($sum, 'RUB'), 1) }} 00 копеек
        </td>
    </tr>

    <tr>
        <td colspan="2">
            &nbsp;&nbsp;&nbsp;&nbsp;Менеджер:
            Лапкин Роман Милайлович
        </td>
    </tr>

    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            Выписка накладных на получение товара производится в Офисе по адресу : г.Белгород, ул.Волчанская, д.139, кор.5
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            При оформлении документов в офисе и получении товара на складе
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            необходимо иметь при себе доверенность либо печать и ПАСПОРТ!
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            Склад работает с 9:00 до 18:00 без обеда
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            Офис работает с 9:00 до 18:00 без обеда
        </td>
    </tr>

    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>

    <tr>

        <td colspan="2" style="text-align: center;">
            <img src="{{ asset('img/stamp.jpg') }}" alt="Печать">

        </td>

    </tr>


    </tbody></table>


</body></html>