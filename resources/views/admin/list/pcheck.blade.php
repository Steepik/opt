@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Ожидают проверку товара</h4>
                    <p class="category">Пользователи которые ожидают проверку наличия товара</p>
                </div>
                <hr/>
                @if(!$orders->isEmpty())
                    <div class="content table-responsive table-full-width">
                        <table class="table table-responsive">
                            <thead>
                            <th>ID</th>
                            <th>Номер заказа</th>
                            <th>Дата создания</th>
                            <th>Наименование</th>
                            <th>Кол-во</th>
                            <th>Итого</th>
                            <th>Имя пользователя</th>
                            <th>Тел. пользователя</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->cnum }}</td>
                                    <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td>{{ $order->pname }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>{{ $order->count * $order->price }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->user->phone }}</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <form action="{{ url('/control/moder/productAction') }}" method="POST" class="moder-btn-from">
                                                <button class="btn btn-sm btn-success btn-icon" title="Дать доступ"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="oid" value="{{ $order->id }}">
                                                <input type="hidden" name="action" value="update">
                                                @csrf
                                            </form>
                                            <form action="{{ url('/control/moder/productAction') }}" method="POST" class="moder-btn-from">
                                                <button class="btn btn-sm btn-danger btn-icon" title="Удалить"><i class="fa fa-close"></i></button>
                                                <input type="hidden" name="oid" value="{{ $order->id }}">
                                                <input type="hidden" name="action" value="delete">
                                                @csrf
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="content">
                        <div class="text-warning">Нет товаров ожидающих проверку</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @section('page_script')
        @if(Session::has('updated'))
            <script type="text/javascript">
                var msg = "Выбраный товар стал доступен для покупки";
                var icon = 'ti-shopping-cart';
                var type = 'success';
                chart.showNotification(msg, icon, type, 'top', 'right');
            </script>
        @elseif(Session::has('deleted'))
            <script type="text/javascript">
                var msg = 'Выбраный товар был отменён';
                var icon = 'ti-na';
                var type = 'info';
                chart.showNotification(msg, icon, type, 'top', 'right');
            </script>
        @endif
    @stop
@stop