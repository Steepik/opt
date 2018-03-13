@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ui  segments">
                    <div class="ui segment">
                        <p>Нет доступа  <i class="warning sign icon"></i></p>
                    </div>
                    <div class="ui secondary segment">
                        <p>В настоящее время у Вас нет доступа к оптовой платформе.</p>
                        <p>После проверки модерации, Вам придет письмо на <b>{{ Auth::user()->email }}</b> с положительным или отрицательным ответом.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop