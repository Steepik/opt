@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
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
                                            <input type="text" name="pcd">
                                        </div>
                                        <div class="field">
                                            <label>Вылет</label>
                                            <input type="text" name="et">
                                        </div>
                                        <div class="field">
                                            <label>DIA</label>
                                            <input type="text" name="dia">
                                        </div>
                                    </div>
                                    <div class="fields">
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