@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Ожидают модерацию</h4>
                    <p class="category">Пользователи которые ожидают доступ к оптовой платформе</p>
                </div>
                <hr/>
                @if(!$users->isEmpty())
                    <div class="content table-responsive table-full-width">
                        <table class="table table-responsive">
                            <thead>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>E-mail</th>
                            <th>Юридическое название</th>
                            <th>ИНН</th>
                            <th>Город</th>
                            <th>Телефон</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->legal_name }}</td>
                                    <td>{{ $user->inn }}</td>
                                    <td>{{ $user->city }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <form action="{{ url('/control/moder/give_access') }}" method="POST" class="moder-btn-from">
                                                <button class="btn btn-sm btn-success btn-icon" title="Дать доступ"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="uid" value="{{ $user->id }}">
                                                <input type="hidden" name="action" value="update">
                                                @csrf
                                            </form>
                                            <form action="{{ url('/control/moder/give_access') }}" method="POST" class="moder-btn-from">
                                                <button class="btn btn-sm btn-danger btn-icon" title="Удалить"><i class="fa fa-close"></i></button>
                                                <input type="hidden" name="uid" value="{{ $user->id }}">
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
                        <div class="text-warning">Нет пользователей ожидающих модерацию</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@section('page_script')
    @if(Session::has('updated'))
        <script type="text/javascript">
            var msg = "<b>{{ Session::get('updated') }}</b> получил доступ к оптовой платформе!";
            var icon = 'ti-unlock';
            var type = 'success';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @elseif(Session::has('deleted'))
        <script type="text/javascript">
            var msg = 'Пользователь был удален!';
            var icon = 'ti-trash';
            var type = 'info';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @endif
@stop
@stop