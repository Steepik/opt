@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-8 col-xs-9 bhoechie-tab-container">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 bhoechie-tab-menu">
                <div class="list-group">
                    <a href="#" class="list-group-item active text-center">
                        <h4 class="glyphicon glyphicon-own-icon tire"></h4><br/>Поиск шин по типоразмеру
                    </a>
                    <a href="#" class="list-group-item text-center">
                        <h4 class="glyphicon glyphicon-own-icon wheels"></h4><br/>Поиск дисков по типоразмеру
                    </a>
                    <a href="#" class="list-group-item text-center">
                        <h4 class="glyphicon glyphicon-own-icon car"></h4><br/>Поиск шин и дисков по автомобилю
                    </a>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-9 col-xs-9 bhoechie-tab">
                <!-- TIRE SECTION -->
                <div class="bhoechie-tab-content active">
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
                                            <option value="Всесезонная">Всесезонная</option>
                                        </select>
                                    </div>
                                    <div class="field select">
                                        <label>Производитель</label>
                                        <select class="form-control" name="brand_id">
                                            <option value="">Любой</option>
                                            @foreach($tire_brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>CAE</label>
                                        <input type="text" name="tcae">
                                    </div>
                                </div>
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
                                            @foreach($truck_brands as $brand)
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
                                            @foreach($special_brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>CAE</label>
                                        <input type="text" name="tcae">
                                    </div>
                                </div>
                                <button class="ui blue button">Выполнить подбор</button>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
                <!-- WHEELS SECTION -->
                <div class="bhoechie-tab-content wheels-tab">
                    <div class="ui form">
                        <form action="{{ route('podbor_wheels') }}" method="GET">
                            <input type="hidden" name="type" value="4">
                            <div class="fields">
                                <div class="field">
                                    <label>Ширина</label>
                                    <input type="text" name="twidth">
                                </div>
                                <div class="field">
                                    <label>Диаметр</label>
                                    <input type="text" name="tdiameter">
                                </div>
                                <div class="field">
                                    <label>Кол-во отверстий</label>
                                    <input type="text" name="hole_count">
                                </div>
                            </div>
                            <div class="fields">
                                <div class="field">
                                    <label>PCD</label>
                                    <input type="text" name="pcd" placeholder="67.1">
                                </div>
                                <div class="field">
                                    <label>Вылет</label>
                                    <input type="text" name="et" placeholder="40">
                                </div>
                                <div class="field">
                                    <label>DIA</label>
                                    <input type="text" name="dia" placeholder="108">
                                </div>
                            </div>
                            <div class="fields">
                                <div class="field select">
                                    <label>Производитель</label>
                                    <select class="form-control" name="brand_id">
                                        <option value="">Любой</option>
                                        @foreach($wheels_brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>CAE</label>
                                    <input type="text" name="tcae" placeholder="CAE">
                                </div>
                            </div>
                            <button class="ui blue button">Выполнить подбор</button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>

                <!-- BY CAR SECTION -->
                <div class="bhoechie-tab-content wheels-tab">
                    <div class="ui form">
                        <form action="{{ route('podbor_wheels') }}" method="GET">
                            <input type="hidden" name="type" value="4">
                            <div class="fields">
                                <div class="field">
                                    <label>Марка автомобиля</label>
                                    <select name="vendor" id="fvendor">
                                        <option>Выберите</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->fvendor }}">{{ $vendor->fvendor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Модель автомобиля</label>
                                    <select name="car" id="fcar"></select>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="field">
                                    <label>Год выпуска</label>
                                    <select name="year" id="fyear"></select>
                                </div>
                                <div class="field">
                                    <label>Модификация</label>
                                    <select name="mod" id="fmod"></select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Ваши заказы</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        @if(Session::has('status-error') and !empty(Session::get('status-error')))
                            <div class="ui negative message">
                                <i class="close icon"></i>
                                <div class="header">
                                    Ошибка
                                </div>
                                <p>
                                <ul>
                                    <li>{{ Session::get('status-error') }}</li>
                                </ul>
                                </p>
                            </div>
                        @endif
                        @if(Session::has('info-msg') and !empty(Session::get('info-msg')))
                            <div class="ui blue message">
                                <i class="close icon"></i>
                                <div class="header">
                                    Информация
                                </div>
                                <p>
                                <ul>
                                    <li>{{ Session::get('info-msg') }}</li>
                                </ul>
                                </p>
                            </div>
                        @endif
                        <form class="ui form" method="get" action="{{ route('home') }}">
                            <h4 class="ui dividing header">Поиск заказов</h4>
                            <div class="field">
                                <div class="fields">
                                    <div class="field">
                                        <label>Дата с:</label>
                                        <input type="date" class="form-control" name="start">
                                    </div>
                                    <div class="field">
                                        <label>Дата по:</label>
                                        <input type="date" class="form-control" name="end" value="<?php echo date('Y-m-d');?>">
                                    </div>
                                    <div class="field">
                                        <label>Статус</label>
                                        <select class="form-control" name="sid">
                                            <option value="">Любой</option>
                                            @foreach($get_status as $status)
                                                <option value="{{ $status->id }}">{!! $status->text !!}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="fields">
                                    <div class="field">
                                        <label>Номер заказа</label>
                                        <input type="text" class="form-control" name="cnum" placeholder="Номер заказа">
                                    </div>
                                    <div class="field">
                                        <label>Код товара</label>
                                        <input type="text" class="form-control" name="cae" placeholder="Код товара">
                                    </div>
                                    <div class="field">
                                        <label>Искать в архивах</label>
                                        <select class="form-control" name="arch">
                                            <option value="0">Нет</option>
                                            <option value="1">Да</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="ui button blue" value="Поиск">
                        </form>
                        <hr/>
                    @if($list)
                            <form action="{{ route('prod_action') }}" method="POST" class="form-action-prod">
                            <table id="cart" class="ui celled table">
                                <thead>
                                <tr>
                                    <th style="text-align: center;"><div class="ui checkbox checkAllBox"><input type="checkbox"><label></label></div></th>
                                    <th>Номер</th>
                                    <th>Создано</th>
                                    <th nowrap>Кол-во</th>
                                    <th class="text-center">Итого</th>
                                    <th>Статус</th>
                                </tr>
                                </thead>
                                <tbody class="tbody-cart">
                                <?php $total_price = 0;?>
                                @foreach($list as $arr_product)
                                    @foreach($arr_product as $product)
                                        @if(!array_key_exists('merged', $product))
                                            <tr align="center">
                                                    <td>
                                                        <div class="ui checkbox"><input class="checkbox-prod" value="{{ $product['oid'] }}" name="oid[]" type="checkbox"><label></label></div>
                                                    </td>
                                                <td class="order-cnum">
                                                    @if($product->commented)
                                                        <span class="ui label red new-comment">Новый комментарий</span><br/>
                                                    @endif
                                                        <a href="{{ route('order', $product->oid) }}">{{ $product->cnum }}</a>
                                                </td>
                                                <td nowrap>{{ $product->time }}</td>
                                                <td>{{ $product->count }}</td>
                                                <td data-th="Subtotal" class="text-center">{{ ($product->price_opt * $product['count']) }}p</td>
                                                <td nowrap>
                                                    @if($product->sid == 1)
                                                        <div class="ui message blue">
                                                            {!! $product->status  !!}
                                                        </div>
                                                    @elseif($product->sid == 2)
                                                        <div class="ui message positive">
                                                            {!! $product->status  !!}
                                                        </div>
                                                    @elseif($product->sid == 3)
                                                        <div class="ui message negative">
                                                            {!! $product->status  !!}
                                                        </div>
                                                    @elseif($product->sid == 4)
                                                        <div class="ui message teal">
                                                            {!! $product->status  !!}
                                                        </div>
                                                    @elseif($product->sid == 5)
                                                        <div class="ui message positive">
                                                            {!! $product->status  !!}
                                                        </div>
                                                    @elseif($product->sid == 6)
                                                        <div class="ui message black">
                                                            {!! $product->status  !!}
                                                        </div>
                                                    @elseif($product->sid == 7)
                                                        <div class="ui message red">
                                                            {!! $product->status  !!}
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <?php $total_price += ($product->price_opt * $product->count); ?>
                                        @else
                                            <tr align="center">
                                                <td><div class="ui checkbox"><input class="checkbox-prod" value="{{ $product['oid'] }}" name="oid[]" type="checkbox"><label></label></div></td>
                                                <td>
                                                    @if($product['merged'] == 0)
                                                        <a href="{{ route('order', $product['oid']) }}">{{ $product['cnum'] }}</a>
                                                    @else
                                                        <a href="{{ route('order-m', $product['oid']) }}">{{ $product['cnum'] }}</a>
                                                    @endif
                                                </td>
                                                <td nowrap>{{ $product['time'] }}</td>
                                                <td>{{ $product['count'] }}</td>
                                                <td data-th="Subtotal" class="text-center">{{ $product['price'] }}p</td>
                                                <td nowrap>
                                                    @if($product['sid'] == 1)
                                                        <div class="ui message blue">
                                                            {!! $product['status']  !!}
                                                        </div>
                                                    @elseif($product['sid'] == 2)
                                                        <div class="ui message positive">
                                                            {!! $product['status']  !!}
                                                        </div>
                                                    @elseif($product['sid'] == 3)
                                                        <div class="ui message negative">
                                                            {!! $product['status']  !!}
                                                        </div>
                                                    @elseif($product['sid'] == 4)
                                                        <div class="ui message teal">
                                                            {!! $product['status']  !!}
                                                        </div>
                                                    @elseif($product['sid'] == 5)
                                                        <div class="ui message positive">
                                                            {!! $product['status']  !!}
                                                        </div>
                                                    @elseif($product['sid'] == 6)
                                                        <div class="ui message black">
                                                            {!! $product['status']  !!}
                                                        </div>
                                                    @elseif($product['sid'] == 7)
                                                        <div class="ui message red">
                                                            {!! $product['status']  !!}
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <?php $total_price += $product['price']; ?>
                                        @endif
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                                {{ csrf_field() }}
                                <input type="hidden" name="action" value="" id="action_btn">
                            </form>
                        <table style="width:100%">
                            <tr>
                                <td class="text-right">Итого: {{ $total_price }}p</td>
                            </tr>
                        </table>
                        <button class="ui button green basic" id="btn_ready_prod">К отгрузке</button>
                        <button class="ui red basic button" id="btn_cancel_prod">Отменить заказ</button>
                        <button class="ui button blue basic" id="btn_merge_prod">Объединить</button>
                        <button class="ui button brown basic" id="btn_archive_prod">В архив</button>
                        <button class="ui button black basic" id="btn_del_prod">Удалить</button>
                    @else
                            <div class="ui large message atc-error">
                                <p><i class="icon unordered list"></i> Список заказов пуст
                                </p>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
