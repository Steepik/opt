@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Подбор дисков</div>

                    <div class="panel-body">
                        <div class="ui bottom attached tab segment active" data-tab="first">
                            <div class="ui form">
                                <form action="{{ route('podbor_wheels') }}" method="GET">
                                    <input type="hidden" name="type" value="4">
                                    <div class="fields">
                                        <div class="field">
                                            <label>Ширина</label>
                                            <input type="text" @if(Session::get('width')) value="{{ Session::get('width') }}"  @endif name="twidth">
                                        </div>
                                        <div class="field">
                                            <label>Диаметр</label>
                                            <input type="text" @if(Session::get('diameter')) value="{{ Session::get('diameter') }}"  @endif name="tdiameter">
                                        </div>
                                        <div class="field">
                                            <label>Кол-во отверстий</label>
                                            <input type="text" @if(Session::get('hole_count')) value="{{ Session::get('hole_count') }}"  @endif name="hole_count">
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field">
                                            <label>PCD</label>
                                            <input type="text" @if(Session::get('pcd')) value="{{ Session::get('pcd') }}"  @endif name="pcd">
                                        </div>
                                        <div class="field">
                                            <label>Вылет</label>
                                            <input type="text" @if(Session::get('et')) value="{{ Session::get('et') }}"  @endif name="et">
                                        </div>
                                        <div class="field">
                                            <label>DIA</label>
                                            <input type="text" @if(Session::get('dia')) value="{{ Session::get('dia') }}"  @endif name="dia">
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field select">
                                            <label>Производитель</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="" @if(Session::get('brand') == '') selected  @endif>Любой</option>
                                                @foreach($brands_list as $brand)
                                                    <option value="{{ $brand->id }}" @if(Session::get('brand') == $brand->id) selected  @endif>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>Тип диска</label>
                                            <select class="form-control" name="type">
                                                <option value="">Любой</option>
                                                <option value="Литой" {{ Session::get('type') == 'Литой' ? 'selected' : '' }}>Литой</option>
                                                <option value="Штампованный" {{ Session::get('type') == 'Штампованный' ? 'selected' : '' }}>Штампованный</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>CAE</label>
                                            <input type="text" name="tcae" @if(Session::get('cae')) value="{{ Session::get('cae') }}"  @endif>
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
                                    <thead>
                                    <tr><th>Название</th>
                                        <th nowrap>
                                            @php
                                                $url = 'wheels/podbor?'.http_build_query(request()->except(['sortOptPrice', 'sortRozPrice']));
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

                                    @foreach($data as $wheel)
                                        <tr>
                                            <td>
                                                <h4 class="ui image header">
                                                    <img src="{{ asset('images/' . $wheel->image) }}.jpg" class="light ui large rounded image" />
                                                    <div class="content">
                                                        <div class="sub header brand">
                                                            @php
                                                                $image_id = \App\Wheel::brandImage($wheel->brand->name)->first();
                                                            @endphp
                                                            <img src="//torgshina.com/image/manufacturer/{{ $image_id->manufacturer_id }}.jpg" alt="{{ $wheel->brand->name }}">
                                                        </div><br/>
                                                        <span class="podbor-prod-name">{{ $wheel->name }}</span>
                                                        <div class="sub header">{{ $wheel->tcae }}
                                                        </div>
                                                    </div>
                                                </h4></td>
                                            <td>{{ $wheel->price_roz }}</td>
                                            @if(!Session::has('hideOpt'))
                                            <td style="font-style: italic;"><b>{{ $wheel->price_opt }}</b></td>
                                            @endif
                                            <td style="font-style: italic;">
                                                @if($wheel->quantity > 8)
                                                    <b> > 8 </b>
                                                @else
                                                    <b>{{ $wheel->quantity }}</b>
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
                                                    <input type="hidden" name="product_id" id="product_id" value="{{ $wheel->tcae }}">
                                                    <input type="hidden" name="type" id="type" value="{{ $type }}">
                                                    {{ csrf_field() }}
                                                </form>
                                                <div class="ui negative hidden message atc-error">
                                                    <i class="close icon"></i>
                                                    <div class="header">
                                                        Ошибка добавления в корзину
                                                    </div>
                                                    <p>Количество товара не должно превышать остаток
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="ui negative message"><div class="header text-center">К сожалению по данному запросу ничего не найдено</div></div>
                        @endif
                    </div>
                </div>
                <div class="paginate"> {{ $data->appends($appends)->render() }}</div>
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