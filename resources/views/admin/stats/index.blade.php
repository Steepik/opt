@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-success text-center">
                                <i class="ti-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>На сайте</p>
                                {{ count($u_online) }}
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            Пользователей онлайн
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-success text-center">
                                <i class="ti-money"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Итого за месяц</p>
                                {{ $sumByMonth }}
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            Сумма за текущий месяц
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-success text-center">
                                <i class="ti-money"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Итого за год</p>
                                {{ $sumByYear }}
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            Сумма покупок за {{ date('Y') }} год
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-7 col-sm-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Информация</h4>
                </div>
                <div class="content">
                    <table class="table table-responsive">
                        <thead>
                        <th>№</th>
                        <th>Имя</th>
                        <th>Юридическое название</th>
                        <th>Товаров куплено</th>
                        <th>Сумма</th>
                        </thead>
                        <tbody class="tbody-cart">
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="position:relative;">
                                @if($user->is_online)
                                    <i class="ti-shine" style="position:absolute; left:-15px; top:6px; color: green; font-size:22px;"></i>
                                @endif
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->legal_name }}</td>
                            <td>
                                @php
                                    $ptotal = 0;
                                    $psum = 0;
                                @endphp
                                @foreach($user->orders()->where('sid', 6)->where('ptype', '!=', null)->get() as $order)
                                    @php
                                        $ptotal += $order->count;
                                        if($order->ptype != null) {
                                            $instance = \App\Cart::getInstanceProductType($order->ptype);
                                            $product = $instance->where('tcae', $order->tcae)->first();
                                            if(! is_null($product)) {
                                                $psum += $product->price_opt * $order->count;
                                            } else {
                                                $product = \App\HistoryOrders::where('oid', $order->id)->first();
                                                $psum += $product->price_opt * $order->count;
                                            }
                                        }
                                    @endphp
                                @endforeach

                                {{ $ptotal }} шт.
                            </td>
                            <td>{{ number_format($psum, 0, '', ' ') }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

@stop