@extends('layouts.app')

    @section('content')
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header-cart">
                                <h4 class="title">Редактирование профиля</h4>
                            </div>
                            <h4 class="ui horizontal divider header"><i class="address card outline icon"></i> Ваши данные </h4>
                            <div class="content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="ui cards">
                                                <div class="ui card profile">
                                                    <div class="content">
                                                        <div class="header">
                                                            <img class="ui avatar image" src="{{ asset('/img/people.svg') }}">
                                                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) . mb_substr($user->name, 1) }}
                                                        </div>
                                                        <div class="meta">
                                                            <span class="right floated time">{{ $user->created_at->format('d.m.Y') }}</span>
                                                            <span class="category">Дата регистрации:</span>
                                                        </div>
                                                        <div class="description">
                                                            <p></p>
                                                        </div>
                                                    </div>
                                                    <div class="extra content">
                                                        <div class="right floated author">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ui card profile">
                                                    <div class="content">
                                                        <div class="header">
                                                            <img class="ui avatar image" src="{{ asset('/img/people.svg') }}">
                                                            Ваш менеджер
                                                        </div>
                                                        <div class="meta">
                                                            <span class="right floated time">Лапкин Роман Михайлович</span>
                                                            <span class="category">ФИО:</span>
                                                        </div>
                                                        <div class="meta">
                                                            <span class="right floated time">+7(4722) 414-494</span>
                                                            <span class="category">Тел.: </span>
                                                        </div>
                                                        <div class="description">
                                                            <p></p>
                                                        </div>
                                                    </div>
                                                    <div class="extra content">
                                                        <div class="right floated author">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="ui horizontal divider header"><i class="edit icon"></i>Редактирование</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6" style="display: table; margin:0 auto;float:none;">
                                            @if($errors->any())
                                                <div class="ui negative message transition">
                                                    <i class="close icon"></i>
                                                    <div class="header">Исправьте следующие ошибки</div>
                                                    @foreach ($errors->all() as $error)
                                                        <div class="ui list">
                                                            <div class="item">
                                                                <i class="pointing right icon"></i>
                                                                <div class="content">{{ $error }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <br/>
                                            @endif
                                            </div>
                                            <div class="ui two column middle aligned very relaxed stackable grid container" style="position: relative">
                                                <div class="column">
                                                    @if(Session::has('success'))
                                                        <div class="ui positive message transition">
                                                            <i class="close icon"></i>
                                                            <div class="header">Данные сохранены</div>
                                                            <p>Ваши данные были успешно обновлены.</p>
                                                        </div>
                                                    @endif
                                                    <form action="{{ route('profile_update') }}" method="POST">
                                                        <div class="ui form">
                                                            <div class="field">
                                                                <label>Контактное лицо</label>
                                                                <div class="ui left icon input">
                                                                    <input type="text" required name="name" placeholder="Контактное лицо" value="{{ $user->name or '' }}">
                                                                    <i class="user icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <label>Юридическое название</label>
                                                                <div class="ui left icon input">
                                                                    <input type="text" required name="legal_name" placeholder="Юридическое название" value="{{ $user->legal_name or '' }}">
                                                                    <i class="industry icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <label>ИНН</label>
                                                                <div class="ui left icon input">
                                                                    <input type="text" required name="inn" placeholder="ИНН" value="{{ $user->inn or '' }}">
                                                                    <i class="id address book outline icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <label>Город</label>
                                                                <div class="ui left icon input">
                                                                    <input type="text" required name="city" placeholder="Город" value="{{ $user->city or '' }}">
                                                                    <i class="building icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <label>Улица</label>
                                                                <div class="ui left icon input">
                                                                    <input type="text" required name="street" placeholder="Улица" value="{{ $user->street or '' }}">
                                                                    <i class="building icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <label>Дом, квартира, корпус</label>
                                                                <div class="ui left icon input">
                                                                    <input type="text" required name="house" placeholder="Дом, кв., к." value="{{ $user->house or '' }}">
                                                                    <i class="building icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="field">
                                                                <label>Телефон</label>
                                                                <div class="ui left icon input">
                                                                    <input type="text" required name="phone" placeholder="Телефон" value="{{ $user->phone or '' }}">
                                                                    <i class="phone icon"></i>
                                                                </div>
                                                            </div>
                                                            <div class="text-center">
                                                                <button type="submit" class="ui blue button btn-wd">Сохранить</button>
                                                            </div>
                                                        </div>
                                                        @method('PUT')
                                                        @csrf
                                                    </form>
                                                </div>
                                                <div class="ui vertical divider"><i class="settings icon"></i></div>
                                                <div class="center aligned column">
                                                    <div class="column">
                                                        @if(Session::has('success-pass'))
                                                            <div class="ui positive message transition">
                                                                <i class="close icon"></i>
                                                                <div class="header">Новый пароль сохранен</div>
                                                                <p>Новый пароль был успешно сохранен.</p>
                                                            </div>
                                                        @endif
                                                        <form action="{{ route('profile_up_pass') }}" method="POST">
                                                            <div class="ui form">
                                                                <div class="field">
                                                                    <label>Старый пароль</label>
                                                                    <div class="ui left icon input">
                                                                        <input type="password" required name="old_pass" placeholder="Старый пароль">
                                                                        <i class="shield icon"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Новый пароль</label>
                                                                    <div class="ui left icon input">
                                                                        <input type="password" required name="password" placeholder="Новый пароль">
                                                                        <i class="privacy icon"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Повторите пароль</label>
                                                                    <div class="ui left icon input">
                                                                        <input type="password" required name="password_confirmation" placeholder="Повторите пароль">
                                                                        <i class="privacy icon"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="text-center">
                                                                    <button type="submit" class="ui blue button btn-wd">Изменить пароль</button>
                                                                </div>
                                                            </div>
                                                            @method('PUT')
                                                            @csrf
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop
