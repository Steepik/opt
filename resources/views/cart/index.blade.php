@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="panel panel-body">
            <div class="ui three top attached steps">
                <div class="active step">
                    <i class="shopping basket icon"></i>
                    <div class="content">
                        <div class="title">Корзина</div>
                        <div class="description">Список товаров</div>
                    </div>
                </div>
            </div>
            <div class="ui attached segment">
                @if(count($products) > 0)
                    <table id="cart" class="ui selectable celled table">
                        <thead>
                        <tr>
                            <th>Товар</th>
                            <th nowrap>Цена (Розница)</th>
                            <th nowrap>Цена (Оптом)</th>
                            <th>Остаток</th>
                            <th>Кол-во</th>
                            <th class="text-center">Итого</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-cart">
                        <?php $total_price = 0;?>
                        @foreach($products as $product)
                            <tr align="center">
                                <td>
                                    <div class="col-sm-4">
                                        <div class="image-season">
                                            @if($product[0]->tseason == 'Зимняя')
                                                <img src="https://torgshina.com/image/icons/winter.png" />
                                            @endif
                                            @if($product[0]->tseason == 'Летняя')
                                                <img src="https://torgshina.com/image/icons/sun.png" />
                                            @endif
                                            @if($product[0]->tseason == 'Всесезонная')
                                                <img src="https://torgshina.com/image/icons/winsun.png" alt="всесезонные шины"/><br/>
                                            @endif
                                            @if($product[0]->spike)
                                                <img src="https://torgshina.com/image/icons/ship.png" />
                                            @endif
                                        </div>
                                        <img src="{{ asset('images/' . $product[0]->image) }}.jpg" alt="{{ $product[0]->name }}" class="img-responsive"/>
                                    </div>
                                    <h4 class="cart-product-name">{{ $product[0]->name }}</h4>
                                </td>
                                <td>{{ $product[0]->price_roz }}p</td>
                                <td>{{ $product[0]->price_opt }}p</td>
                                <td>
                                    @if($product[0]->quantity > 8)
                                        > 8
                                    @else
                                        {{ $product[0]->quantity }}
                                    @endif
                                </td>
                                <td data-th="Quantity" class="product-count">
                                    <form action="{{ route('refresh') }}" method="post" class="cart_form_action">
                                        <input type="number" name="count" class="form-control text-center p-count" value="{{ $product['count'] }}">
                                        <input type="hidden" name="id" class="id" value="{{ $product['id']}}">
                                        <input type="hidden" name="product_id" class="pid" value="{{ $product[0]->tcae}}">
                                        <input type="hidden" name="ptype" value="{{ $product['ptype'] }}">
                                        <input type="hidden" name="action" value="" class="btn-action">
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                                <td data-th="Subtotal" class="text-center">{{ ($product[0]->price_opt * $product['count']) }}p</td>
                                <td class="actions" data-th="">
                                    <div class="ui buttons">
                                        <button class="ui standart button refresh">Пересчитать</button>
                                        <div class="or" data-text="|"></div>
                                        <button class="ui negative button delete-prod">Удалить</button>
                                    </div>
                                </td>
                            </tr>
                            <?php $total_price += ($product[0]->price_opt * $product['count']); ?>
                        @endforeach
                        @if(Session::has('error-refresh'))
                            <div class="ui negative message">
                                <i class="close icon"></i>
                                <div class="header">
                                    Ошибка перерасчета
                                </div>
                                <p>Количество товара не должно превышать остаток и не быть меньше или равняться нулю
                                </p>
                            </div>
                        @endif
                        </tbody>
                    </table>
                    <table style="width:100%">
                        <tr>
                            <td>
                                <a href="/" class="ui basic secondary button"><i class="cart icon"></i>Продолжить покупки</a>
                                <a href="/make_order" class="ui basic positive button"><i class="payment icon"></i>Оформить заказ</a>
                            </td>
                            <td class="text-center"><strong>Итого: {{ $total_price }}p</strong></td>
                        </tr>
                    </table>
                @else
                    <div class="ui icon message">
                        <i class="announcement icon"></i>
                        <div class="content">
                            <div class="header">
                                Ваша корзина пуста!
                            </div>
                            <p>Не теряйте времени</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop