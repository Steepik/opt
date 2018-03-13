@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Импорт</h4>
                    <p class="category">Импортирует данные из excel в базу данных</p>
                </div>
                <hr/>
                <div class="content">
                    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        <input type="file" name="uploadfile" class="inputfile"/>
                        <button type="submit" class="btn btn-info btn-fill btn-wd sbmt">Импорт</button>
                        @method('POST')
                        @csrf
                    </form><br/>
                    @if($errors->any())
                        <div class="col-md-3 col-xs-7">
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
@section('page_script')
    @if(Session::has('updated'))
        <script type="text/javascript">
            var msg = "База данных была обновлена";
            var icon = 'ti-check';
            var type = 'success';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @endif
@stop