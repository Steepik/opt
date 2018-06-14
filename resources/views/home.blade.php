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
                <div class="panel-body">
                    <h2>Лучшие предложения сейчас</h2>
                    @if(!$tires->isEmpty())
                    <div class="ui horizontal divider">
                        Шины
                    </div>
                    <div class="best-deals-block" style="text-align: center;">
                        @foreach($tires as $tire)
                        <div class="best-product-block">
                            <div class="best-image">
                                <div class="image-season">
                                    @if($tire->tseason == 'Зимняя')
                                        <img src="https://torgshina.com/image/icons/winter.png" />
                                    @endif
                                    @if($tire->tseason == 'Летняя')
                                        <img src="https://torgshina.com/image/icons/sun.png" />
                                    @endif
                                    @if($tire->tseason == 'Всесезонная')
                                        <img src="https://torgshina.com/image/icons/winsun.png" alt="всесезонные шины"/><br/>
                                    @endif
                                    @if($tire->spike)
                                        <img src="https://torgshina.com/image/icons/ship.png" />
                                    @endif
                                </div>
                                <img src="{{ asset('images/' . $tire->image) }}.jpg" alt="{{ $tire->name }}" class="ui tiny rounded image" />
                            </div>
                            <div class="best-name">
                                <ul>
                                    <li>{{ \Illuminate\Support\Str::limit($tire->name, 30) }}</li>
                                    <li class="best-price">{{ number_format($tire->price_opt, 0, '.', ' ') }} ₽</li>
                                    <li class="text-muted">Остаток: {{ $tire->quantity }}</li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @if(!$wheels->isEmpty())
                    <div class="ui horizontal divider">
                        Диски
                    </div>
                    <div class="best-deals-block" style="text-align: center;">
                        @foreach($wheels as $wheel)
                            <div class="best-product-block">
                                <div class="best-image">
                                    <img src="{{ asset('images/' . $wheel->image) }}.jpg" alt="{{ $wheel->name }}" class="ui tiny rounded image" />
                                </div>
                                <div class="best-name">
                                    <ul>
                                        <li>{{ \Illuminate\Support\Str::limit($wheel->name, 30) }}</li>
                                        <li class="best-price">{{ number_format($wheel->price_opt, 0, '.', ' ') }} ₽</li>
                                        <li class="text-muted">Остаток: {{ $wheel->quantity }}</li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                    @if($tires->isEmpty() and $wheels->isEmpty())
                    <div class="text-center">На данный момент предложений нет</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
