@extends('admin.layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-warning text-center">
                                <i class="ti-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Оптовиков</p>
                                <a href="{{ route('buyers') }}">{{ count($users) }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            Общее количество оптовиков
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
                                <i class="ti-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Успешных сделок</p>
                                {{ count($orders) }}
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            <i class="ti-calendar"></i>  За этот месяц
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
                            <div class="icon-big icon-danger text-center">
                                <i class="ti-lock"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                               <p>Ожидают модерацию</p>
                                <a href="{{ route('moder') }}">{{ count($access) }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            Пользователи ожидающие модерацию
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
                            <div class="icon-big icon-info text-center">
                                <i class="ti-time"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers">
                                <p>Ожидают проверку</p>
                                <a href="{{ route('pcheck') }}">{{ count($s_wait) }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <hr />
                        <div class="stats">
                            Пользователи ожидающие проверку товара
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Диаграмма успешных сделок</h4>
                </div>
                <div class="content">
                    <div id="chartHours" class="ct-chart"></div>
                    <div class="footer">
                        <div class="chart-legend">
                        </div>
                        <hr>
                        <div class="stats">
                            <i class="ti-calendar"></i> За этот год
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @section('page_script')
        <script type="text/javascript">
            $(document).ready(function(){
                var data = <?php echo $chart?>;
                chart.initChartist(data);

            });
        </script>
    @stop
@stop