@extends('layouts.app')

@section('content')
    <div class="container">
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
                    <form class="ui form" method="get" action="{{ route('order-list') }}">
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
                        <form action="{{ route('prod_action') }}" method="POST" class="table-responsive form-action-prod">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="text-align: center;"><div class="ui checkbox checkAllBox"><input type="checkbox"><label></label></div></th>
                                    <th style="text-align: center;">Номер</th>
                                    <th style="text-align: center;">Создано</th>
                                    <th style="text-align: center;" nowrap>Кол-во</th>
                                    <th class="text-center">Итого</th>
                                    <th style="text-align: center;">Статус</th>
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
                        <br/>
                        <div class="col-md-12 hidden-xs">
                            <button class="ui button green basic btn_ready_prod">К отгрузке</button>
                            <button class="ui red basic button btn_cancel_prod">Отменить заказ</button>
                            <button class="ui button blue basic btn_merge_prod">Объединить</button>
                            <button class="ui button brown basic btn_archive_prod">В архив</button>
                            <button class="ui button black basic btn_del_prod">Удалить</button>
                        </div>
                        <div class="ui floating labeled bottom icon dropdown pointing button visible-xs">
                            <i class="check circle icon"></i>
                            <span class="text">Действия с отмеченными</span>
                            <div class="menu">
                                <div class="header">Список действий</div>
                                <div class="divider"></div>
                                <div class="item btn_ready_prod">
                                    <span class="description"></span>
                                    <span class="text"><i class="truck icon"></i> К отгрузке</span>
                                </div>
                                <div class="item btn_cancel_prod">
                                    <span class="description"></span>
                                    <span class="text"><i class="ban icon"></i> Отменить заказ</span>
                                </div>
                                <div class="item btn_merge_prod">
                                    <span class="description"></span>
                                    <span class="text"><i class="sitemap icon"></i> Объединить</span>
                                </div>
                                <div class="item btn_archive_prod">
                                    <span class="description"></span>
                                    <span class="text"><i class="archive icon"></i> В архив</span>
                                </div>
                                <div class="item btn_del_prod">
                                    <span class="description"></span>
                                    <span class="text"><i class="trash alternate icon"></i> Удалить</span>
                                </div>
                            </div>
                        </div>
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
@endsection