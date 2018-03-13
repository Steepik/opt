@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        ПОДБОР ШИН И ДИСКОВ ПО АВТО
                    </div>
                <div class="bhoechie-tab-content wheels-tab">
                    <div class="ui form">
                        <form action="{{ route('podbor_wheels') }}" method="GET">
                            <div class="fields">
                                <div class="field">
                                    <label>Марка автомобиля</label>
                                    <select name="vendor" id="fvendor">
                                        <option>Выберите</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->fvendor }}" >{{ $vendor->fvendor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Модель автомобиля</label>
                                    <select name="car" id="fcar"></select>
                                </div>
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
        </div>
        <br/>
        <div class="panel panel-default">
            <div class="panel-body">
            <h3 class="text-center bycar-title">{{ Request::segment(2) }}, {{ Request::segment(3) }}, {{ Request::segment(4) }}, {{ Request::segment(5) }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Шины</div>
                    <div class="panel-body">
                        <h4>Заводские размеры</h4>
                        @foreach($result as $item)
                            @if($item->fpriznak == 'Заводская' and $item->ftips == 'Шина')
                                @php
                                    $sizes = explode('/', $item->fsizes);
                                    $url = '/tires/podbor?type=1&twidth=' . $sizes[0] . '&tprofile=' . $sizes[1] .
                                           '&tdiameter=' . $sizes[2];
                                @endphp
                                <a href="{{ $url }}">{{ $item->fsizes }}</a><br/>
                            @endif
                        @endforeach
                        <h4>Размеры на замену</h4>
                        @foreach($result as $item)
                            @if($item->fpriznak == 'Замена' and $item->ftips == 'Шина')
                                @php
                                    $sizes = explode('/', $item->fsizes);
                                    $url = '/tires/podbor?type=1&twidth=' . $sizes[0] . '&tprofile=' . $sizes[1] .
                                           '&tdiameter=' . $sizes[2];
                                @endphp
                                <a href="{{ $url }}">{{ $item->fsizes }}</a><br/>
                            @endif
                        @endforeach
                        <h4>Размеры для тюнинга</h4>
                        @foreach($result as $item)
                            @if($item->fpriznak == 'Тюнинг' and $item->ftips == 'Шина')
                                @php
                                    $sizes = explode('/', $item->fsizes);
                                    $url = '/tires/podbor?type=1&twidth=' . $sizes[0] . '&tprofile=' . $sizes[1] .
                                           '&tdiameter=' . $sizes[2];
                                @endphp
                                <a href="{{ $url }}">{{ $item->fsizes }}</a><br/>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Диски</div>
                    <div class="panel-body">
                        <h4>Заводские размеры</h4>
                        @foreach($result as $item)
                            @if($item->fpriznak == 'Заводская' and $item->ftips == 'Диск')
                                {{ $item->fsizes }}<br/>
                            @endif
                        @endforeach
                        <h4>Размеры на замену</h4>
                        @foreach($result as $item)
                            @if($item->fpriznak == 'Замена' and $item->ftips == 'Диск')
                                {{ $item->fsizes }}<br/>
                            @endif
                        @endforeach
                        <h4>Размеры для тюнинга</h4>
                        @foreach($result as $item)
                            @if($item->fpriznak == 'Тюнинг' and $item->ftips == 'Диск')
                                {{ $item->fsizes }}<br/>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Крепежь</div>
                    <div class="panel-body">
                        <h4>Крепеж</h4>
                        @foreach($result as $item)
                            @if($item->ftips == 'Крепеж')
                                {{ $item->fsizes }}<br/>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop