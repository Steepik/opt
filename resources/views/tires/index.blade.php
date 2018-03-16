@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Подбор шин</div>
                    <div class="panel-body">
                        <div class="ui top attached tabular menu">
                            <a class="item active" data-tab="first">Легковые шины</a>
                            <a class="item" data-tab="second">Грузовые шины</a>
                            <a class="item" data-tab="third">Спецтехника</a>
                        </div>
                        <div class="ui bottom attached tab segment active" data-tab="first">
                            <div class="ui form">
                                <form action="{{ route('podbor') }}" method="GET">
                                    <input type="hidden" name="type" value="1">
                                    <div class="fields">
                                        <div class="field">
                                            <label>Ширина</label>
                                            <input type="text" name="twidth">
                                        </div>
                                        <div class="field">
                                            <label>Профиль</label>
                                            <input type="text" name="tprofile">
                                        </div>
                                        <div class="field">
                                            <label>Диаметр</label>
                                            <input type="text" name="tdiameter">
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field select">
                                            <label>Сезон</label>
                                            <select class="form-control" name="tseason">
                                                <option value="">Любой</option>
                                                <option value="Летняя">Летние</option>
                                                <option value="Зимняя">Зимние</option>
                                                <option value="nospike">Зимние нешипованные</option>
                                                <option value="spike">Зимние шипованные</option>
                                                <option value="Всесезонная">Всесезонные</option>
                                            </select>
                                        </div>
                                        <div class="field select">
                                            <label>Производитель</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="">Любой</option>
                                                @foreach($brands_list as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>CAE</label>
                                            <input type="text" name="tcae" placeholder="CAE">
                                        </div>
                                    </div>
                                    <hr/>
                                    <button class="ui blue button">Выполнить подбор</button>
                                   {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                        <div class="ui bottom attached tab segment" data-tab="second">
                            <div class="ui form">
                                <form action="{{ route('podbor') }}" method="GET">
                                    <input type="hidden" name="type" value="2">
                                    <div class="fields">
                                        <div class="field">
                                            <label>Ширина</label>
                                            <input type="text" name="twidth">
                                        </div>
                                        <div class="field">
                                            <label>Профиль</label>
                                            <input type="text" name="tprofile">
                                        </div>
                                        <div class="field">
                                            <label>Диаметр</label>
                                            <input type="text" name="tdiameter">
                                        </div>
                                        <div class="field select">
                                            <label>Сезон</label>
                                            <select class="form-control" name="tseason">
                                                <option value="">Любой</option>
                                                <option value="Летняя">Летние</option>
                                                <option value="Зимняя">Зимние</option>
                                                <option value="nospike">Зимние нешипованные</option>
                                                <option value="spike">Зимние шипованные</option>
                                            </select>
                                        </div>
                                        <div class="field select">
                                            <label>Производитель</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="">Любой</option>
                                                @foreach($brands_list as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field">
                                            <div class="field">
                                                <label>Ось</label>
                                                <select name="axis">
                                                    <option value="">Любой</option>
                                                    <option value="Drive">Drive</option>
                                                    <option value="Front">Front</option>
                                                    <option value="Front/Trailer">Front/Trailer</option>
                                                    <option value="Trailer">Trailer</option>
                                                    <option value="Universal">Universal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label>CAE</label>
                                            <input type="text" name="tcae">
                                        </div>
                                    </div>
                                    <hr/>
                                    <button class="ui blue button">Выполнить подбор</button>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                        <div class="ui bottom attached tab segment" data-tab="third">
                            <div class="ui form">
                                <form action="{{ route('podbor') }}" method="GET">
                                    <input type="hidden" name="type" value="3">
                                    <div class="fields">
                                        <div class="field">
                                            <label>Ширина</label>
                                            <input type="text" name="twidth">
                                        </div>
                                        <div class="field">
                                            <label>Профиль</label>
                                            <input type="text" name="tprofile">
                                        </div>
                                        <div class="field">
                                            <label>Диаметр</label>
                                            <input type="text" name="tdiameter">
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field select">
                                            <label>Сезон</label>
                                            <select class="form-control" name="tseason">
                                                <option value="">Любой</option>
                                                <option value="Летняя">Летние</option>
                                                <option value="Зимняя">Зимние</option>
                                            </select>
                                        </div>
                                        <div class="field select">
                                            <label>Производитель</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="">Любой</option>
                                                @foreach($brands_list as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>CAE</label>
                                            <input type="text" name="tcae">
                                        </div>
                                    </div>
                                    <hr/>
                                    <button class="ui blue button">Выполнить подбор</button>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                        <!-- result -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="closes">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>
@stop