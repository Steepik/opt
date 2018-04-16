@extends('admin.layouts.index')


@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h4 class="title text-center">Заказ №{{ $order->cnum }}</h4>
                    <p class="category text-center"><a href="{{ route('invoice', $order->id) }}" target = "_blank">Накладная</a></p>
                </div>
                <hr/>
                <div class="alert alert-info text-center">
                    {!! $order->status->text !!}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h4 class="title text-center">Дата</h4>
                    <p class="category"></p>
                </div>
                <hr/>
                <div class="content">
                    <span>Дата создания: {{ $order->created_at->format('d.m.Y H:i') }}</span><br/>
                    <span>Дата отправления: {{ $order->updated_at->format('d.m.Y H:i') }}</span><br/>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h4 class="title text-center">Информация</h4>
                    <p class="category"></p>
                </div>
                <hr/>
                <div class="content">
                    <span>Общее кол-во: <span class="order-total-count">{{ $order->count }}</span></span><br/>
                    <span>Сумма: <span class="order-total-sum">{{ number_format($product->price_opt * $order->count, 0, ',' , ' ') }}</span>p</span><br/>
                    <span>Вид отгрузки: Самовывоз</span><br/>
                    <span>Контактное лицо: {{ $order->user->name }}</span><br/>
                    <span>Телефон: {{ $order->user->phone }}</span><br/>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
                <div class="content card table-responsive table-full-width">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th class="text-center">Товар</th>
                            <th class="text-center" >Бренд</th>
                            <th class="text-center" >Цена(Розница)</th>
                            <th class="text-center" >Цена(Оптом)</th>
                            <th class="text-center" nowrap>Кол-во</th>
                            <th class="text-center">Итого</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-cart">
                            <tr align="center">
                                <td>
                                    <div class="col-sm-6 col-md-2 col-xs-3">
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
                                    <h4 class="order-product-name-inside">{{ $product->name }}</h4>
                                </td>
                                <td>{{ $product->brand->name }}</td>
                                <td>{{ number_format($product->price_roz, 0, ',', ' ') }}p</td>
                                <td class="order-price-opt">{{  number_format($product->price_opt, 0, ',', ' ') }}p</td>
                                <td class="order-item-count" style="cursor: pointer">{{ $order->count }}</td>
                                <td data-th="Subtotal" class="text-center"><span class="order-total-sum">{{ number_format($product->price_opt * $order->count, 0, ',', ' ') }}</span>p</td>
                                <input type="hidden" class="order-cnum" value="{{ $order->cnum }}">
                                <input type="hidden" class="product-tcae" value="{{ $order->tcae }}">
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h4 class="title text-center">Комментарии к заказу</h4>
                    <p class="category"></p>
                </div>
                <hr/>
                <div class="content">
                    <div class="card">
                        <div class="content">
                            <ul class="list-unstyled team-members">
                                @forelse($order->comments as $comment)
                                    <li>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ $comment->user->name }}
                                                <br>
                                                <span class="text-muted"><small>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at)->format('d.m.y H:i:s') }}</small></span>
                                            </div>
                                            <span>{{ $comment->text }}</span>
                                        </div>
                                    </li>
                                @empty
                                    Нет коммментариев
                                @endforelse
                            </ul>
                            <br/>
                            <form action="{{ route('admin_add_comment', $order->id) }}" method="POST">
                                <textarea class="form-control" required name="text"></textarea><br/>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-fill btn-success">Отправить</button>
                                </div>
                                @method('POST')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page_script')
    @if(Session::has('success'))
        <script type="text/javascript">
            var msg = "Комментарий был успешно добавлен";
            var icon = 'ti-comment';
            var type = 'success';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @endif
@stop