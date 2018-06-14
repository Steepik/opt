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

    @yield('css')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114770315-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-114770315-1');
    </script>

</head>
<body class="{{ request()->route()->getName() == 'auth' ? 'auth-background' : 'home-background'}}">
    <div id="app">
        <div id="oveflow-bg"><div id="main-bg-wrapper"></div></div>
        @auth
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="{{ route('home') }}"><button class="pull-left collapse-btn navbar-toggle"><i class="home icon colored"></i></button></a>
                    <a href="{{ route('order-list') }}" class="pull-left collapse-btn"><button class="navbar-toggle">Заказы</button></a>
                    <a href="{{ route('tires') }}" class="pull-left collapse-btn"><button class="navbar-toggle">Шины</button></a>
                    <a href="{{ route('wheels') }}" class="pull-left collapse-btn"><button class="navbar-toggle">Диски</button></a>
                    <a href="{{ route('cart') }}">
                        <button class="navbar-toggle">
                            <i class="shopping cart icon colored"></i>
                            <span class="cart-products-count">
                                @if(Session::has('cart_products'))
                                    {{ Session::get('cart_products') }} шт.
                                @else
                                    0 шт.
                                @endif
                            </span>
                        </button></a>
                    <a href="{{ route('profile') }}"><button class="navbar-toggle"><i class="user icon colored"></i></button></a>

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
                        <ul class="nav navbar-nav navbar-left">
                            <li><a href="{{ route('order-list') }}"><button class="ui inverted red basic button">Заказы</button></a></li>
                            <li><a href="{{ route('tires') }}"><button class="ui inverted red basic button">Шины</button></a></li>
                            <li><a href="{{ route('wheels') }}"><button class="ui inverted red basic button">Диски</button></a></li>
                            <li><a href="{{ route('profile') }}"><button class="ui inverted red basic button">Личный кабинет</button></a></li>
                        </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
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
                                    <span class="ui basic red left pointing label cart-total-price">
                                        <span id="cart_total_price">
                                        @if(Session::has('total_price'))
                                            {{ Session::get('total_price') }}
                                        @else
                                            0
                                        @endif
                                        </span>
                                        p
                                    </span>
                                </div>
                                </a>
                            </li>
                           {{-- <li class="dropdown profile-btn">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    <button class="ui blue button">{{ Auth::user()->name }} </button><span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        @admin
                                        <a href="/control">Панель управления</a>
                                        @endadmin
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
                            </li>--}}
                        <div class="ui floating icon dropdown button profile-btn" style="margin-top:1em; margin-left: 1em;">
                            <i class="user icon"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <div class="menu">
                                @admin
                                <a href="{{ route('control') }}" class="item"> <i class="cogs icon"></i> Панель управления</a>
                                @endadmin
                                <a href="/excel-download" class="item"> <i class="file excel outline icon"></i> Выгрузки</a>
                                <a href="{{ route('logout') }}" class="item"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"> <i class="sign out alternate icon"></i> Выход</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
        @endauth
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
</body>
</html>
