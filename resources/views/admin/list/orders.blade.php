@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Cписок Заказов</h4>
                    <p class="category">Список всех заказов</p>
                </div>
                <hr/>
                <div class="content order-filter">
                    <form action="{{ route('admin_order') }}" method="GET" class="form-inline" role="form">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label>Начальная дата</label><br/>
                                    <input type="date" class="form-control" name="start" value="{{ app('request')->input('start') }}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Конечная дата</label><br/>
                                    <input type="date" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input type="text" name="num" class="form-control" placeholder="Номер" value="{{ app('request')->input('num') }}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" name="legal_name" class="form-control" placeholder="Юр. название" value="{{ app('request')->input('legal_name') }}">
                                </div>
                            </div>
                            <div class="col-sm-10 text-right">
                            <div class="form-group">
                                <button class="btn btn-sm btn-success btn-icon">Отсортировать</button>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(!$orders->isEmpty())
                    <form action="{{ route('orders_action') }}" method="POST">
                        @csrf
                        @method('POST')
                        <hr/>
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-2">
                                    <select name="action" class="form-control">
                                        <option>С выбранными</option>
                                        <option value="merge">Объединить</option>
                                        <option value="del">Удалить</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-sm btn-success btn-icon">Выполнить</button>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-responsive">
                                <thead>
                                <th></th>
                                <th>№ заказа</th>
                                <th>Имя</th>
                                <th>Юр. название</th>
                                <th>Количество</th>
                                <th>Итого</th>
                                <th>Создано</th>
                                <th>Статус</th>
                                </thead>
                                 <tbody>
                                @foreach($orders as $order)
                                    @php
                                        if($order->ptype != null) {
                                            $product = \App\Cart::getInstanceProductType($order->ptype)->where('tcae', $order->tcae)->first();
                                            if(is_null($product)) {
                                                $product = \App\HistoryOrders::where('oid', $order->id)->first();
                                            }
                                        }
                                    @endphp
                                    @if($order->merged == false and $order->ptype != null)
                                    <tr>
                                        <td>
                                            <div class="ui checkbox orders"><input class="checkbox-prod" value="{{ $order['id'] }}" name="oid[]" type="checkbox"><label></label></div>
                                            <input type="hidden" name="uid[]" value="{{ $order['uid'] }}">
                                        </td>
                                        <td class="cnum-order">
                                            <a href="{{ route('order_show', $order->id) }}">{{ $order->cnum }}
                                                <div class="container short-info">
                                                    <div class="row">
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading">Перечень товаров</div>
                                                            <div class="panel-body">
                                                                <div class="content table-responsive table-full-width">
                                                                    <table class="table table-responsive">
                                                                        <thead>
                                                                        <th>Изображение</th>
                                                                        <th>Наименование</th>
                                                                        <th>Бренд</th>
                                                                        <th>Цена опт</th>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td><img src="{{ asset('images/' . $product->image) }}.jpg" class="short-info-img"></td>
                                                                            <td>{{ $product->name }}</td>
                                                                            <td>{{ $product->brand->name }}</td>
                                                                            <td>{{ $product->price_opt }}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a></td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->user->legal_name }}</td>
                                        <td>{{ $order->count }}</td>
                                        <td>{{ number_format($product->price_opt * $order->count, 0, ',', ' ') }}</td>
                                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                        <td>
                                            @if($order->sid == 4)
                                                <div class="alert status alert-warning text-center">
                                                    <span>{!! $order->status->text  !!}</span>
                                                </div>
                                            @elseif($order->sid == 6)
                                                <div class="alert status alert-success text-center">
                                                    <span>{!! $order->status->text  !!}</span>
                                                </div>
                                            @elseif($order->sid == 7)
                                                <div class="alert status alert-danger text-center">
                                                    <span>{!! $order->status->text  !!}</span>
                                                </div>
                                            @else
                                                <div class="alert status alert-info text-center">
                                                    <span>{!! $order->status->text  !!}</span>
                                                </div>
                                            @endif
                                            <input type="hidden" id="oid" value="{{ $order->id }}">
                                        </td>
                                    </tr>
                                    @elseif($order->merged == false and $order->ptype == null)
                                        @php
                                            $merged = new \App\OrderMerges();
                                            $result = $merged->where('cnum', $order->cnum)->get();
                                            $total_price = 0;
                                            $p_list = collect();
                                            foreach($result as $item){
                                                foreach($item->orders as $m_order) {
                                                    $product = \App\Cart::getInstanceProductType($m_order->ptype)->where('tcae', $m_order->tcae)->first();
                                                    if(! is_null($product)) {
                                                        $total_price += $product->price_opt * $m_order->count;
                                                        $p_list->push($product);
                                                    } else {
                                                        $history = \App\HistoryOrders::where('oid', $m_order->id)->first();
                                                        $total_price += $history->price_opt * $m_order->count;
                                                        $p_list->push($history);
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="ui checkbox orders"><input class="checkbox-prod" value="{{ $order['id'] }}" name="oid[]" type="checkbox"><label></label></div>
                                                <input type="hidden" name="uid[]" value="{{ $order['uid'] }}">
                                            </td>
                                            <td class="cnum-order">
                                                <a href="{{ route('order_show_merge', $order->id) }}">{{ $order->cnum }}
                                                    <div class="container short-info">
                                                        <div class="row">
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading">Перечень товаров</div>
                                                                <div class="panel-body">
                                                                    <div class="content table-responsive table-full-width">
                                                                        <table class="table table-responsive">
                                                                            <thead>
                                                                            <th>Изображение</th>
                                                                            <th>Наименование</th>
                                                                            <th>Бренд</th>
                                                                            <th>Цена опт</th>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($p_list as $product)
                                                                                <tr>
                                                                                    <td><img src="{{ asset('images/' . $product->image) }}.jpg" class="short-info-img"></td>
                                                                                    <td>{{ $product->name }}</td>
                                                                                    <td>{{ $product->brand->name }}</td>
                                                                                    <td>{{ $product->price_opt }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->user->legal_name }}</td>
                                            <td>{{ $order->count }}</td>
                                            <td>{{ number_format($total_price, 0, ',', ' ') }}</td>
                                            <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                            <td>
                                                @if($order->sid == 4)
                                                    <div class="alert status alert-warning text-center">
                                                        <span>{!! $order->status->text  !!}</span>
                                                    </div>
                                                @elseif($order->sid == 6)
                                                    <div class="alert status alert-success text-center">
                                                        <span>{!! $order->status->text  !!}</span>
                                                    </div>
                                                @elseif($order->sid == 7)
                                                    <div class="alert status alert-danger text-center">
                                                        <span>{!! $order->status->text  !!}</span>
                                                    </div>
                                                @else
                                                    <div class="alert status alert-info text-center">
                                                        <span>{!! $order->status->text  !!}</span>
                                                    </div>
                                                @endif
                                                <input type="hidden" id="oid" value="{{ $order->id }}">
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                @else
                    <div class="content">
                        <div class="text-warning">Нет заказов</div>
                    </div>
                @endif
            </div>
            {{ $orders->render() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Изменение статуса заказа</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Текущий статус заказа: <b><span class="current_status"></span></b></h5>
                    <select class="form-control status-list">
                        <option>Выбрать статус</option>
                        @foreach($status_list as $status)
                            <option value="{{ $status->id }}">{{ strip_tags($status->text) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="save-status btn btn-primary">Сохранить статус</button>
                </div>
            </div>
        </div>
    </div>
@section('page_script')
    @if(Session::has('merge-error'))
        @php $msg = Session::get('merge-error'); @endphp
        <script type="text/javascript">
            var msg = '<?php echo $msg?>';
            var icon = 'ti-layout-accordion-merged';
            var type = 'danger';
            chart.showNotification(msg, icon, type, 'top', 'right');
        </script>
    @elseif(Session::has('merge-success'))
            @php $msg = Session::get('merge-success'); @endphp
            <script type="text/javascript">
                var msg = '<?php echo $msg?>';
                var icon = 'ti-layout-accordion-merged';
                var type = 'success';
                chart.showNotification(msg, icon, type, 'top', 'right');
            </script>
    @endif
@stop
@stop