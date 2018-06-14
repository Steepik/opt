@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="header">
                    <h4 class="title">Резерв</h4>
                    <p class="category"></p>
                </div>
                <hr/>
                <form action="{{ route('best-deals') }}" method="GET">
                    <div class="content">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="q" placeholder="Наименование шины" value="{{ app('request')->input('q') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="qw" placeholder="Наименование диска" value="{{ app('request')->input('qw') }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success">Поиск</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <h2>Найдено: {{ $t_count }}</h2>
                            </div>
                        </div>
                    </div>
                </form>
                <hr/>
                @if($products)
                    <div class="content table-responsive table-full-width">
                        <table class="table table-responsive">
                            <thead>
                            <th>№</th>
                            <th>Название</th>
                            <th>Цена розница</th>
                            <th>Цена оптовая</th>
                            <th>Остаток</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price_roz }}</td>
                                    <td>{{ $product->price_opt }}</td>
                                    <td style="text-align: center;">{{ $product->quantity }}</td>
                                    <td>
                                        <form action="{{ route('bestdeals-add') }}" method="POST">
                                            <a href="{{ route('bestdeals-add') }}"><button type="submit" class="btn btn-default btn-fill">Добавить</button></a>
                                            <input type="hidden" name="tcae" value="{{ $product->tcae }}">
                                            <input type="hidden" name="ptype" value="{{ $products->type }}">
                                            @csrf
                                            @method('POST')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="content">
                        {!! $products->appends(request()->input())->links() !!}
                    </div>
                @else
                    <div class="content">Пусто</div>
                @endif
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="header">
                    <h4 class="title">Товары в резерве</h4>
                    <p class="category">Список товаров которые уже в резерве</p>
                </div>
                <hr/>
                <form action="{{ route('best-deals') }}" method="GET">
                    <div class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="r" value="{{ app('request')->input('r') }}" placeholder="Наименование шины" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="rw" value="{{ app('request')->input('rw') }}" placeholder="Наименование диска" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success">Поиск</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr/>
                @if(!empty($inDeals))
                    <div class="content table-responsive table-full-width">
                        <table class="table table-responsive">
                            <thead>
                            <th>№</th>
                            <th>Название</th>
                            <th>Количество</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            @foreach($inDeals as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td style="text-align: center;">{{ $item->quantity }}</td>
                                    <td>
                                        <form action="{{ route('bestdeals-delete') }}" method="POST">
                                            <button type="submit" class="btn btn-success btn-fill">Убрать</button>
                                            <input type="hidden" name="tcae" value="{{ $item->tcae }}">
                                            <input type="hidden" name="ptype" value="1">
                                            @csrf
                                            @method('POST')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="content">
                        <p>Пусто</p>
                    </div>
                @endif
                {{--<div style="text-align: center">{{ $p_reserve->appends($appends)->links() }}</div>--}}
            </div>
        </div>
    </div>
@section('page_script')
    @if(Session::has('success'))
        <script type="text/javascript">
            var msg = "{{ Session::get('success') }}";
            var icon = 'ti-archive';
            var type = 'success';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @endif
@stop
@stop