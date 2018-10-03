@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Настройки</h4>
                    <p class="category"></p>
                </div>
                <hr/>
                <form action="{{ route('brand-view-access') }}" method="GET">
                    <div class="content">
                        <h3>Запрет на отображения брендов</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="q" value="{{ app('request')->input('q') }}" placeholder="Email оптовика" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success">Поиск</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr/>
                @if($users)
                    <div class="content table-responsive table-full-width">
                        <table class="table table-responsive">
                            <thead>
                            <th>№</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Юр. название</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="legal_name" style="text-align: center;">{{ $user->legal_name }}</td>
                                    <td>
                                        <button type="submit" class="btn btn-default btn-fill accesss_brand" data-userId="{{ $user->id }}" data-toggle="modal" data-target="#accessBrand">Просмотр</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="content text-center">
                        {!! $users->appends(request()->input())->links() !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="accessBrand" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Выберите бренд для <span id="legal_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('access-brand-store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <select name="brand_id" class="form-control">
                            @foreach(\App\Brand::orderBy('name', 'asc')->get() as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="user_id" value="" id="userId">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Запретить отображение</button>
                    </div>
                </form>
                <hr/>
                <h5 class="text-center">Список брендов запрещенных для отображения</h5>
                <div class="row">
                    <div class="col-md-12">
                        <ul id="brandList" class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('page_script')
    @if(Session::has('success'))
        <script type="text/javascript">
            var msg = "{{ Session::get('success') }}";
            var icon = 'ti-eye';
            var type = 'success';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @elseif(Session::has('failed'))
        <script type="text/javascript">
            var msg = "{{ Session::get('failed') }}";
            var icon = 'ti-eye';
            var type = 'danger';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @endif
@stop
@stop