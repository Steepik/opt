@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Подбор шин</div>

                    <div class="panel-body">
                        <div class="ui top attached tabular menu">
                            <a class="item @if($filter_type == 1) active @endif" data-tab="first">Легковые шины</a>
                            <a class="item @if($filter_type == 2) active @endif" data-tab="second">Грузовые шины</a>
                            {{--<a class="item @if($filter_type == 3) active @endif" data-tab="third">Спецтехника</a>--}}
                        </div>
                        <div class="ui bottom attached tab segment @if($filter_type == 1) active @endif" data-tab="first">
                            <div class="ui form">
                                <form action="{{ route('podbor') }}" method="get">
                                    <input type="hidden" name="type" value="1">
                                    <div class="fields">
                                        <div class="field">
                                            <label>Ширина</label>
                                            <input type="text" @if(Session::get('twidth')) value="{{ Session::get('twidth') }}"  @endif name="twidth">
                                        </div>
                                        <div class="field">
                                            <label>Профиль</label>
                                            <input type="text" @if(Session::get('tprofile')) value="{{ Session::get('tprofile') }}"  @endif name="tprofile">
                                        </div>
                                        <div class="field">
                                            <label>Диаметр</label>
                                            <input type="text" name="tdiameter" @if(Session::get('tdiameter')) value="{{ Session::get('tdiameter') }}"  @endif>
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field select">
                                            <label>Сезон</label>
                                            <select class="form-control" name="tseason">
                                                <option value="" @if(Session::get('tseason') == '') selected @endif>Любой</option>
                                                <option value="Летняя" @if(Session::get('tseason') == 'Летняя') selected @endif >Летние</option>
                                                <option value="Зимняя" @if(Session::get('tseason') == 'Зимняя') selected @endif>Зимние</option>
                                                <option value="nospike" @if(Session::get('tseason') == 'nospike') selected @endif>Зимние нешипованные</option>
                                                <option value="spike" @if(Session::get('tseason') == 'spike') selected @endif>Зимние шипованные</option>
                                                <option value="Всесезонная" @if(Session::get('tseason') == 'Всесезонная') selected @endif>Всесезонная</option>
                                            </select>
                                        </div>
                                        <div class="field select">
                                            <label>Производитель</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="" @if(Session::get('tbrand') == '') selected  @endif>Любой</option>
                                                @foreach($brands_list as $brand)
                                                    <option value="{{ $brand->id }}" @if(Session::get('tbrand') == $brand->id) selected  @endif>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>CAE</label>
                                            <input type="text" name="tcae" @if(Session::get('tcae')) value="{{ Session::get('tcae') }}"  @endif>
                                        </div>
                                    </div>
                                    <hr/>
                                    <button class="ui blue button">Выполнить подбор</button>

                                </form>
                            </div>
                        </div>
                        <div class="ui bottom attached tab segment @if($filter_type == 2) active @endif" data-tab="second">
                            <div class="ui form">
                                <form action="{{ route('podbor') }}" method="GET">
                                    <input type="hidden" name="type" value="2">
                                    <div class="fields">
                                        <div class="field">
                                            <label>Ширина</label>
                                            <input type="text" @if(Session::get('trwidth')) value="{{ Session::get('trwidth') }}"  @endif name="twidth">
                                        </div>
                                        <div class="field">
                                            <label>Профиль</label>
                                            <input type="text" @if(Session::get('trprofile')) value="{{ Session::get('trprofile') }}"  @endif name="tprofile">
                                        </div>
                                        <div class="field">
                                            <label>Диаметр</label>
                                            <input type="text" @if(Session::get('trdiameter')) value="{{ Session::get('trdiameter') }}"  @endif name="tdiameter">
                                        </div>
                                        <div class="field">
                                            <label>Сезон</label>
                                            <select class="form-control" name="tseason">
                                                <option value="" @if(Session::get('trseason') == '') selected @endif>Любой</option>
                                                <option value="Летняя" @if(Session::get('trseason') == 'Летняя') selected @endif >Летние</option>
                                                <option value="Зимняя" @if(Session::get('trseason') == 'Зимняя') selected @endif>Зимние</option>
                                                <option value="nospike" @if(Session::get('trseason') == 'nospike') selected @endif>Зимние нешипованные</option>
                                                <option value="spike" @if(Session::get('trseason') == 'spike') selected @endif>Зимние шипованные</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>Производитель</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="" @if(Session::get('trbrand') == '') selected  @endif>Любой</option>
                                                @foreach($brands_list as $brand)
                                                    <option value="{{ $brand->id }}" @if(Session::get('trbrand') == $brand->id) selected  @endif>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field">
                                            <div class="field">
                                                <label>Ось</label>
                                                <select name="taxis">
                                                    <option value="" @if(Session::get('traxis') == '') selected  @endif>Любой</option>
                                                    <option value="Drive" @if(Session::get('traxis') == 'Drive') selected  @endif>Drive</option>
                                                    <option value="Front" @if(Session::get('traxis') == 'Front') selected  @endif>Front</option>
                                                    <option value="Front/Trailer" @if(Session::get('traxis') == 'Front/Trailer') selected  @endif>Front/Trailer</option>
                                                    <option value="Trailer" @if(Session::get('traxis') == 'Trailer') selected  @endif>Trailer</option>
                                                    <option value="Universal" @if(Session::get('traxis') == 'Universal') selected  @endif>Universal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label>CAE</label>
                                            <input type="text" @if(Session::get('trcae')) value="{{ Session::get('trcae') }}"  @endif name="tcae">
                                        </div>
                                    </div>
                                    <hr/>
                                    <button class="ui blue button">Выполнить подбор</button>

                                </form>
                            </div>
                        </div>
                        <div class="ui bottom attached tab segment @if($filter_type == 3) active @endif" data-tab="third">
                            <div class="ui form">
                                <form action="{{ route('podbor') }}" method="get">
                                    <input type="hidden" name="type" value="3">
                                    <div class="fields">
                                        <div class="field">
                                            <label>Ширина</label>
                                            <input type="text" @if(Session::get('swidth')) value="{{ Session::get('swidth') }}"  @endif name="twidth">
                                        </div>
                                        <div class="field">
                                            <label>Профиль</label>
                                            <input type="text" @if(Session::get('sprofile')) value="{{ Session::get('sprofile') }}"  @endif name="tprofile">
                                        </div>
                                        <div class="field">
                                            <label>Диаметр</label>
                                            <input type="text" name="tdiameter" @if(Session::get('sdiameter')) value="{{ Session::get('sdiameter') }}"  @endif>
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field select">
                                            <label>Сезон</label>
                                            <select class="form-control" name="tseason">
                                                <option value="" @if(Session::get('sseason') == '') selected @endif>Любой</option>
                                                <option value="Летняя" @if(Session::get('sseason') == 'Летняя') selected @endif >Летние</option>
                                                <option value="Зимняя" @if(Session::get('sseason') == 'Зимняя') selected @endif>Зимние</option>
                                            </select>
                                        </div>
                                        <div class="field select">
                                            <label>Производитель</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="" @if(Session::get('sbrand') == '') selected  @endif>Любой</option>
                                                @foreach($brands_list as $brand)
                                                    <option value="{{ $brand->id }}" @if(Session::get('sbrand') == $brand->id) selected  @endif>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>CAE</label>
                                            <input type="text" name="tcae" @if(Session::get('scae')) value="{{ Session::get('scae') }}"  @endif>
                                        </div>
                                    </div>
                                    <hr/>
                                    <button class="ui blue button">Выполнить подбор</button>

                                </form>
                            </div>
                        </div>
                        <!-- result -->
                        @if(!$data->isEmpty())
                        <div class="table-podbor table-responsive">
                            <table class="table table-hover">
                                <thead class="center aligned">
                                <tr><th >Название</th>
                                    <th nowrap>
                                        @php
                                            $url = 'tires/podbor?'.http_build_query(request()->except(['sortOptPrice', 'sortRozPrice']));
                                        @endphp
                                        @if(isset($appends['sortRozPrice']) and $appends['sortRozPrice'] == 'asc')
                                            <a title="Сортировать по убыванию" href="{{ url($url.'&sortRozPrice=desc') }}">Цена (Розница) <i class="arrow up icon"></i></a>
                                        @elseif(isset($appends['sortRozPrice']) and $appends['sortRozPrice'] == 'desc')
                                            <a title="Сортировать по возрастанию" href="{{ url($url.'&sortRozPrice=asc') }}">Цена (Розница) <i class="arrow down icon"></i></a>
                                        @elseif(!isset($appends['sortRozPrice']))
                                            <a title="Сортировать по возрастанию" href="{{ url($url.'&sortRozPrice=asc') }}">Цена (Розница) <i class="arrow down icon"></i></a>
                                        @endif
                                    </th>
                                    @if(!Session::has('hideOpt'))
                                    <th nowrap>
                                        @if(isset($appends['sortOptPrice']) and $appends['sortOptPrice'] == 'asc')
                                            <a title="Сортировать по убыванию" href="{{ url($url.'&sortOptPrice=desc') }}">Цена (Оптом) <i class="arrow up icon"></i></a>
                                        @elseif(isset($appends['sortOptPrice']) and $appends['sortOptPrice'] == 'desc')
                                            <a title="Сортировать по возрастанию" href="{{ url($url.'&sortOptPrice=asc') }}">Цена (Оптом) <i class="arrow down icon"></i></a>
                                        @elseif(!isset($appends['sortOptPrice']))
                                            <a title="Сортировать по возрастанию" href="{{ url($url.'&sortOptPrice=asc') }}">Цена (Оптом) <i class="arrow down icon"></i></a>
                                        @endif
                                    </th>
                                    @endif
                                    <th>Остаток</th>
                                    <th>Действия</th>
                                </tr></thead>
                                <tbody>

                                @foreach($data as $tire)
                                <tr>
                                    <td>
                                        <h4 class="ui image header">
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
                                            <img src="{{ asset('images/' . $tire->image) }}.jpg" alt="{{ $tire->name }}" class="light ui large rounded image" />
                                            <div class="content">
                                                <div class="sub header brand">
                                                    @php
                                                        $image_id = \App\Tire::brandImage($tire->brand->name)->first();
                                                    @endphp
                                                    @if($image_id != null)
                                                        <img src="//torgshina.com/image/manufacturer/{{ $image_id->manufacturer_id }}.jpg" alt="{{ $tire->brand->name }}">
                                                    @endif
                                                </div><br/>
                                                <span class="podbor-prod-name">{{ $tire->name }}</span>
                                                <div class="podbor-cae">{{ $tire->tcae }}
                                                </div>
                                            </div>
                                        </h4></td>
                                    <td class="center aligned">{{ $tire->price_roz }}</td>
                                    @if(!Session::has('hideOpt'))
                                    <td class="center aligned" style="font-style: italic;"><b>{{ $tire->price_opt }}</b></td>
                                    @endif
                                    <td class="center aligned" style="font-style: italic;">
                                        @if($tire->quantity > 8)
                                            <b> > 8 </b>
                                        @else
                                            <b>{{ $tire->quantity }}</b>
                                        @endif
                                    </td>
                                    <td class="center aligned">
                                        <form onsubmit="return false;" method="post" action="{{ route('addtocart') }}" class="form-add-to-cart">
                                        <div class="ui action input">
                                            <input type="number" min="1" name="count" class="count-field" id="count" placeholder="Количество">
                                            <button class="ui teal right labeled icon button add-to-cart">
                                                <i class="add to cart icon"></i>
                                                Купить
                                            </button>
                                        </div>
                                            <input type="hidden" name="product_id" id="product_id" value="{{ $tire->tcae }}">
                                            <input type="hidden" name="type" id="type" value="{{ $type }}">
                                            {{ csrf_field() }}
                                        </form>
                                        <div class="ui negative hidden message atc-error">
                                            <i class="close icon"></i>
                                            <div class="header">
                                                Ошибка добавления в корзину
                                            </div>
                                            <p>Количество покупаемого товар не должно превышать остаток
                                            </p></div>
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="ui negative message"><div class="header">К сожалению по данному запросу ничего не найдено</div></div>
                        @endif
                    </div>
                </div>
                <div class="paginate"> {{ $data->appends($appends)->render() }}</div>
            </div>
        </div>
    </div>

@stop