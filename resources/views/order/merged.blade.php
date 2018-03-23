@extends('layouts.app')

@section('content')
    <div class="ui container">
        <div class="ui segments">
            <div class="ui segment">
                <p>Информация о заказе @if($products['sid'] == 2 or $products['sid'] == 5 and $products['sid'] != 4 and $products['sid'] != 6 and Auth::user()->payment_type == 0) <a href="/bill-m/{{ $products['oid'] }}" class="btn link">Печать Счета</a> @endif</p>
            </div>
            <div class="ui secondary segment">
                <div class="ui cards top-info">
                    <div class="card">
                        <div class="content">
                            <div class="header text-center">Заказ № {{ $products['cnum'] }}</div><br/>
                            <div class="meta">
                                @if($products['sid'] == 1)
                                    <span class="ui button basic blue" style="width:100%;">{!! $products['status'] !!}</span>
                                @elseif($products['sid'] == 2)
                                    <span class="ui button basic green" style="width:100%;">{!! $products['status'] !!}</span>
                                @elseif($products['sid']== 3)
                                    <span class="ui button basic red" style="width:100%;">{!! $products['status'] !!}</span>
                                @elseif($products['sid']== 4)
                                    <span class="ui button basic teal" style="width:100%;">{!! $products['status'] !!}</span>
                                @elseif($products['sid']== 5)
                                    <span class="ui button basic green" style="width:100%;">{!! $products['status'] !!}</span>
                                @endif
                            </div>
                            <p></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="content">
                            <div class="header text-center">Дата</div><br/>
                            <div class="meta">
                                <span>Дата создания: {{ $products['time'] }}</span><br/>
                            </div>
                            <p></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="content">
                            <div class="header text-center">Информация</div><br/>
                            <div class="meta">
                                <span>Общее кол-во: {{ $products['count'] }}</span><br/>
                                <span>Сумма: {{ $products['price'] }}p</span><br/>
                            </div>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="ui cards">
                    <div class="card">
                        <div class="content">
                            <div class="header text-center">Информация о заказе</div><br/>
                            <div class="meta">
                                <span>Вид отгрузки: Самовывоз</span><br/>
                                <span>Контактное лицо: {{ Auth::user()->name }}</span><br/>
                                <span>Телефон: {{ Auth::user()->phone }}</span><br/>
                            </div>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="ui horizontal divider">
                    Товар
                </div>
                @if(Session::has('info-msg') and !empty(Session::get('info-msg')))
                    <div class="ui blue message">
                        <i class="close icon"></i>
                        <div class="header">
                            Информация
                        </div>
                        <p>
                        <ul>
                            <li>{{ Session::get('info-msg') }}</li>
                        </ul>
                        </p>
                    </div>
                @endif
                <table id="cart" class="ui celled table">
                    <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Бренд</th>
                        <th>Цена(Розница)</th>
                        <th>Цена(Оптом)</th>
                        <th nowrap>Кол-во</th>
                        <th class="text-center">Итого</th>
                    </tr>
                    </thead>
                    <tbody class="tbody-cart">
                    <?php $total_price = 0;?>
                    @foreach($products['products'] as $product)
                    <tr align="center">
                        <td>
                            <div class="col-sm-3">
                                <div class="image-season">
                                    @if($product->tseason == 'Зимняя')
                                        <img src="https://torgshina.com/image/icons/winter.png" alt="зимние шины"/><br/>
                                    @endif
                                    @if($product->tseason == 'Летняя')
                                        <img src="https://torgshina.com/image/icons/sun.png" alt="летние шины"/><br/>
                                    @endif
                                    @if($product->tseason == 'Всесезонная')
                                        <img src="https://torgshina.com/image/icons/winsun.png" alt="всесезонные шины"/><br/>
                                    @endif
                                    @if($product->spike)
                                        <img src="https://torgshina.com/image/icons/ship.png" alt="шипованные шины"/>
                                    @endif
                                </div>
                                <img src="{{ asset('images/' . $product->image) }}.jpg" alt="{{ $product->name }}" class="order-img light img-responsive"/>
                            </div>
                            @php
                                $image_id = \App\Tire::brandImage($product->brand->name)->first();
                            @endphp
                            <img src="//torgshina.com/image/manufacturer/{{ $image_id->manufacturer_id }}.jpg" width="80" alt="{{ $product->brand->name }}">
                            <h4 class="order-product-name-inside">{{ $product->name }}</h4>
                        </td>
                        <td>{{ $product->brand->name }}</td>
                        <td>{{ $product->price_roz }}p</td>
                        <td>{{ $product->price_opt }}p</td>
                        <td>{{ $product->count }}</td>
                        <td data-th="Subtotal" class="text-center">{{ ($product->price_opt * $product->count) }}p</td>
                    </tr>
                    <?php $total_price += ($product->price_opt * $product->count); ?>
                    @endforeach
                    </tbody>
                </table>
                <table style="width:100%">
                    <tr>
                        <td class="text-right">Итого: {{ $total_price }}p</td>
                    </tr>
                </table>
                <div class="ui horizontal divider">
                    Комментарии к заказу
                </div>
                @if(Session::has('c_added'))
                    <div class="ui positive message transition">
                        <i class="close icon"></i>
                        <div class="header">
                            Успех!
                        </div>
                        <p>Ваш комментарий был успешно добавлен</p>
                    </div>
                @endif
                <div class="ui piled segments">
                    <div class="ui segment">
                        <form method="POST" action="{{ route('add_comment') }}">
                            <div class="ui form">
                                <div class="field">
                                    <textarea name="text" required placeholder="Ваш комментарий"></textarea>
                                    <input type="hidden" name="oid" value="{{ $products['oid'] }}">
                                    {{ csrf_field() }}
                                </div>
                                <div class="field text-center">
                                    <input type="submit" class="ui button teal">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="ui segment">
                        <div class="ui form">
                            <div class="field">
                                <div class="ui comments">
                                    @foreach($products['comments'] as $comment)
                                        <div class="comment">
                                            <div class="content">
                                                <a class="author">{{ $comment->user->name }}</a>
                                                <div class="metadata">
                                                    <div class="date">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at)->format('d.m.y H:i:s') }}</div>
                                                </div>
                                                <div class="text">
                                                    {{ $comment->text }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop