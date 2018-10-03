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
                <div class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="{{ route('brand-view-access') }}" class="btn btn-fill btn-info">Запрет на отображения брендов</a></li>
                                <li class="list-group-item"><a href="{{ route('brand-view-percent') }}" class="btn btn-fill btn-info">Установить процент для бренда</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop