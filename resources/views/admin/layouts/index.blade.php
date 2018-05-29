<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('admin/assets/img/favicon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Админ-панель</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{{ asset('admin/assets/css/animate.min.css') }}" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="{{ asset('admin/assets/css/paper-dashboard.css') }}" rel="stylesheet"/>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('/img/favicon.ico') }}">

    <!--  Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/themify-icons.css') }}" rel="stylesheet">

    @yield('css')

</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="{{ route('home') }}" class="simple-text">
                    {{ config('app.name') }}
                </a>
            </div>

            <ul class="nav">
                <li class="{{ request()->route()->getName() == 'control' ? 'active' : '' }}">
                    <a href="{{ route('control') }}">
                        <i class="ti-bar-chart-alt"></i>
                        <p>Сводка</p>
                    </a>
                </li>
                <li class="{{ request()->route()->getName() == 'buyers' ? 'active' : '' }}">
                    <a href="{{ route('buyers') }}">
                        <i class="ti-user"></i>
                        <p>Оптовики</p>
                    </a>
                </li>
                <li class="{{ request()->route()->getName() == 'admin_order' ? 'active' : '' }}">
                    <a href="{{ route('admin_order') }}">
                        <i class="ti-shopping-cart"></i>
                        <p>Заказы</p>
                    </a>
                </li>
                <li class="{{ request()->route()->getName() == 'reserve' ? 'active' : '' }}">
                    <a href="{{ route('reserve') }}">
                        <i class="ti-archive"></i>
                        <p>Резерв</p>
                    </a>
                </li>
                <li class="{{ request()->route()->getName() == 'stats' ? 'active' : '' }}">
                    <a href="{{ route('stats') }}">
                        <i class="ti-bar-chart-alt"></i>
                        <p>Статистика</p>
                    </a>
                </li>
                <li class="{{ request()->route()->getName() == 'import' ? 'active' : '' }}">
                    <a href="{{ route('import') }}">
                        <i class="ti-import"></i>
                        <p>Импорт</p>
                    </a>
                </li>
                <li>
            </ul>
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="{{ route('control') }}">Панель управления</a>
                </div>
                <div class="collapse navbar-collapse">
                    <!--<ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-panel"></i>
                                <p>Stats</p>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-bell"></i>
                                <p class="notification">5</p>
                                <p>Notifications</p>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Notification 1</a></li>
                                <li><a href="#">Notification 2</a></li>
                                <li><a href="#">Notification 3</a></li>
                                <li><a href="#">Notification 4</a></li>
                                <li><a href="#">Another notification</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <i class="ti-settings"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                    </ul>-->

                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script>, {{ config('app.name') }} <i class="fa fa-bomb"></i> by <a href="//vk.com/fisher__ok">Fisher John</a>
                </div>
            </div>
        </footer>

    </div>
</div>

<!-- notify sound -->
<audio id="buzzer" src="{{ asset('notify/notify.mp3') }}" type="audio/ogg"></audio>

</body>

<script src="{{ asset('js/app.js') }}"></script>

<!--   Core JS Files   -->
<script src="{{ asset('admin/assets/js/jquery-1.10.2.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/assets/js/bootstrap.min.js') }}" type="text/javascript"></script>

<!--  Checkbox, Radio & Switch Plugins -->
<script src="{{ asset('admin/assets/js/bootstrap-checkbox-radio.js') }}"></script>

<!--  Charts Plugin -->
<script src="{{ asset('admin/assets/js/chartist.min.js') }}"></script>

<!--  Notifications Plugin    -->
<script src="{{ asset('admin/assets/js/bootstrap-notify.js') }}"></script>

<script src="{{ asset('admin/assets/js/paper-dashboard.js') }}"></script>

<script src="{{ asset('admin/assets/js/chart.js') }}"></script>

<script src="{{ asset('admin/assets/js/main.js') }}"></script>

@yield('page_script')

<!-- Real-time notify -->
<script>
    window.Echo.channel('admin-notify')
        .listen('.notify', (e) => {

        swal({
                 title: "Новый заказ",
                 text: e.message,
                 icon: "warning",
                 button: "Закрыть окно",
             });

        //play notify sound
        var buzzer = $('#buzzer')[0];
        buzzer.play();
        return false;
    });
</script>

</html>
