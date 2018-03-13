@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Резерв</h4>
                    <p class="category"></p>
                </div>
                <hr/>
                    <form action="{{ route('reserve') }}" method="GET">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" name="q" value="{{ app('request')->input('q') }}" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success">Поиск</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h2>Найдено: {{ $t_count }}</h2>
                                </div>
                            </div>
                        </div>
                    </form>
                <hr/>
                @if($tires)
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
                        @foreach($tires as $tire)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>@if(isset($tire->reserved))<span class="label label-danger">В резерве</span>@endif {{ $tire->name }}</td>
                                <td>{{ $tire->price_roz }}</td>
                                <td>{{ $tire->price_opt }}</td>
                                <td>{{ $tire->quantity }}</td>
                                <td>
                                    @if(!isset($tire->reserved))
                                        <form action="{{ route('reserve-add') }}" method="POST">
                                            <a href="{{ route('reserve-add') }}"><button type="submit" class="btn btn-default btn-fill">В резерв</button></a>
                                            <input type="hidden" name="pid" value="{{ $tire->id }}">
                                            <input type="hidden" name="ptype" value="1">
                                            @csrf
                                            @method('POST')
                                        </form>
                                    @else
                                        <form action="{{ route('reserve-delete') }}" method="POST">
                                            <button type="submit" class="btn btn-success btn-fill">Убрать с резерва</button>
                                            <input type="hidden" name="pid" value="{{ $tire->id }}">
                                            <input type="hidden" name="ptype" value="1">
                                            @csrf
                                            @method('POST')
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="content">
                    {!! $tires->appends(request()->input())->links() !!}
                </div>
                @else
                    <div class="content">Пусто</div>
                @endif
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