<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Meta -->
    <meta name="keywords" content="опт, шины опт белгород, диски опт белгород, зимняя резина опт">
    <meta name="description" content="Оптовая площадка шин, дисков - Шинный центр ТоргШина в Белгороде. Продажа шин, дисков по оптовым ценам.">

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('/img/favicon.ico') }}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/semantic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @yield('css')

</head>
<body>
    <div id="app">
        <div id="oveflow-bg"><div id="main-bg-wrapper"></div></div>
        @if(!Auth::guest())
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('/img/logo.png')  }}" alt="Торгшина - оптовая площадка"/>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    @guest
                    @else
                        <ul class="nav navbar-nav navbar-left">
                            <li><a href="{{ route('home') }}"><button class="ui inverted red basic button">Заказы</button></a></li>
                            <li><a href="{{ route('tires') }}"><button class="ui inverted red basic button">Шины</button></a></li>
                            <li><a href="{{ route('wheels') }}"><button class="ui inverted red basic button">Диски</button></a></li>
                            <li><a href="{{ route('profile') }}"><button class="ui inverted red basic button">Личный кабинет</button></a></li>
                        </ul>
                    @endguest
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                        @else
                            <li class="dropdown cart">
                                <a href="{{ route('cart') }}">
                                <div class="ui labeled button cart-info" tabindex="0">
                                    <span class="cart-products-count">
                                         @if(Session::has('cart_products'))
                                            {{ Session::get('cart_products') }} шт.
                                        @else
                                            0 шт.
                                        @endif
                                    </span>
                                    <div class="ui red button">
                                        <i class="cart icon"></i> Корзина
                                    </div>
                                    <span class="ui basic red left pointing label cart-total-price"><span id="cart_total_price">
                                            @if(Session::has('total_price'))
                                                {{ Session::get('total_price') }}
                                            @else
                                                0
                                            @endif
                                        </span>p</span>
                                </div>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    <button class="ui blue button">{{ Auth::user()->name }} </button><span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/excel-download">Выгрузки</a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Выйти
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @endif
        @yield('content')
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="closes">&times;</span>
        <img src="" class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/semantic.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>
</html>
