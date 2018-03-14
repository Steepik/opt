@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="header-cart">
                            <h4 class="title">Выгрузки</h4>
                        </div>
                        <div class="content">
                            <h4 class="ui horizontal divider header"><i class="address card outline icon"></i> Списов доступных выгрузок </h4>
                            <br/>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="ui cards">
                                        <div class="ui card profile">
                                            <div class="content">
                                                <div class="header">
                                                    <i class="glyphicon glyphicon-own-icon tire"></i>
                                                    <a href="{{ route('excel-download', 'tire') }}">Выгрузить шины</a>
                                                </div>
                                                <div class="description">
                                                    <p>Выгружает все данные всех шин в формате xls</p>
                                                </div>
                                            </div>
                                            <div class="extra content">
                                                <div class="right floated author">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                </div>
                                <div class="col-md-6">
                                    <div class="ui cards">
                                        <div class="ui card profile">
                                            <div class="content">
                                                <div class="header">
                                                    <i class="glyphicon glyphicon-own-icon wheels"></i>
                                                    <a href="{{ route('excel-download', 'wheel') }}">Выгрузить диски</a>
                                                </div>
                                                <div class="description">
                                                    <p>Выгружает все данные всех дисков в формате xls</p>
                                                </div>
                                            </div>
                                            <div class="extra content">
                                                <div class="right floated author">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
