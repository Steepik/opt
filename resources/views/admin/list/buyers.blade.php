@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Cписок оптовиков</h4>
                    <p class="category">Список всех оптовиков</p>
                </div>
                <hr/>
                @if(!$buyers->isEmpty())
                    <div class="content table-responsive table-full-width">
                        <table class="table table-responsive">
                            <thead>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>E-mail</th>
                            <th>Юридическое название</th>
                            <th>Инн</th>
                            <th>Город</th>
                            <th>Улица</th>
                            <th>Дом, кв., к.</th>
                            <th>Телефон</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            @foreach($buyers as $buyer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $buyer->name }}</td>
                                    <td>{{ $buyer->email }}</td>
                                    <td>{{ $buyer->legal_name }}</td>
                                    <td>{{ $buyer->inn }}</td>
                                    <td>{{ $buyer->city }}</td>
                                    <td>{{ $buyer->street }}</td>
                                    <td>{{ $buyer->house }}</td>
                                    <td>{{ $buyer->phone }}</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <form action="{{ url('/control/moder/give_access') }}" method="POST" class="moder-btn-from">
                                                <button class="btn btn-sm btn-default @if($buyer->payment_type == 0) btn-fill @endif btn-icon" title="Безналичный расчет">Б/Р</button>
                                                <input type="hidden" name="uid" value="{{ $buyer->id }}">
                                                <input type="hidden" name="action" value="pay_beznal">
                                                @csrf
                                            </form>
                                            <form action="{{ url('/control/moder/give_access') }}" method="POST" class="moder-btn-from">
                                                <button class="btn btn-sm btn-success @if($buyer->payment_type == 1) btn-fill @endif btn-icon" title="Наличный расчет">Н/Р</button>
                                                <input type="hidden" name="uid" value="{{ $buyer->id }}">
                                                <input type="hidden" name="action" value="pay_nal">
                                                @csrf
                                            </form>
                                            <form action="{{ url('/control/moder/give_access') }}" method="POST" class="moder-btn-from">
                                                <button class="btn btn-sm btn-danger btn-icon" title="Заблокировать"><i class="fa fa-lock"></i></button>
                                                <input type="hidden" name="uid" value="{{ $buyer->id }}">
                                                <input type="hidden" name="action" value="blocked">
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
                        <div class="text-warning">Нет оптовиков</div>
                    </div>
                @endif
            </div>
            {{ $buyers->render() }}
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
    @elseif(Session::has('blocked'))
        <script type="text/javascript">
            var msg = 'Пользователь был заблокирован';
            var icon = 'ti-na';
            var type = 'info';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @elseif(Session::has('payment'))
        <script type="text/javascript">
            var msg = 'Пользователю был установлен вид оплаты';
            var icon = 'ti-money';
            var type = 'info';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @endif
@stop
@stop