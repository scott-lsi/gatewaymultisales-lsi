<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Personaliser') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    @if(\Auth::check())
                    <a class="navbar-brand" href="{{ action('ProductController@index') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    @else
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav" >
                        @if(\Auth::check())
                        <li><a href="{{ action('ProductController@index') }}">All Products</a></li>
                        <!-- <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Powerbanks<span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><a href="{{ action('ProductController@getProductsByType', 'Powerbanks') }}">All Powerbanks</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'pb101Powerbanks') }}">PB101 - Cuboid Metal 2000mAh</a></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'pb116Powerbanks') }}">PB116 - Credit Card 2500mAh</a></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'pb119Powerbanks') }}">PB119 - Flat 4000mAh</a></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'pb119cPowerbanks') }}">PB119c - Flat 8000mAh</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'shirtPowerbanks') }}">Football Shirt</a></li>
                          </ul>
                        </li> -->

                        <!-- Swapped the dropdown menu for simple navigation since everything is now grouped. -->
                        <li>
                            <a href="{{ action('ProductController@getProductsByType', 'Powerbanks') }}" >Powerbanks</a>
                        </li>

                        <!-- <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Notebooks<span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><a href="{{ action('ProductController@getProductsByType', 'Notebooks') }}">All Notebooks</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'A5Notebooks') }}">A5 Books</a></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'A6Notebooks') }}">A6 Books</a></li>
                          </ul>
                        </li> -->
                        
                        <!-- Swapped the dropdown menu for simple navigation since everything is now grouped. -->
                        <li>
                            <a href="{{ action('ProductController@getProductsByType', 'Notebooks') }}" >Notebooks</a>
                        </li>

                        <!-- <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pens<span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><a href="{{ action('ProductController@getProductsByType', 'Pens') }}">All Pens</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'curvyBallpen') }}">Curvy Ballpen</a></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'contourBallpen') }}">Contour Ballpen</a></li>
                          </ul>
                        </li> -->

                        <!-- Swapped the dropdown menu for simple navigation since everything is now grouped. -->
                        <li>
                            <a href="{{ action('ProductController@getProductsByType', 'Pens') }}" >Pens</a>
                        </li>
                        
                        <!-- <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Boxes<span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><a href="{{ action('ProductController@getProductsByType', 'Boxes') }}">All Boxes</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'midiBoxes') }}">Midi Box</a></li>
                            <li><a href="{{ action('ProductController@getProductsByType', 'midiBoxWithItems') }}">Midi Box With Items</a></li>
                          </ul>
                        </li> -->

                        <!-- Swapped the dropdown menu for simple navigation since everything is now grouped. -->
                        <li>
                            <a href="{{ action('ProductController@getProductsByType', 'Boxes') }}" >Boxes</a>
                        </li>

                        <li>
                            <a href="{{ action('ProductController@getProductsByType', 'Mugs') }}" >Mugs</a>
                        </li>
                        @endif

                        @if(Auth::check() && Auth::user()->isAdmin())
                        <li>
                            <a href="{{ action('ProductController@getTrashed') }}">Soft Deletes</a>
                        </li>                       
                        @endif
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                            @if(!\Auth::check())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                            @else
                            <li><a href="{{ action('OrderController@getOrders', ['id' => auth()->user()->id]) }}">My Orders</a></li>
                            <li><a href="{{ action('CartController@index') }}">Basket</a></li>
                            <li><a href="{{ action('HomeController@logout') }}">Log Out</a></li>
                            @endif
                        </ul>
                </div>
            </div>
        </nav>
        
        @if(session()->has('message'))
            <div class="container">
                <div class="alert alert-{{ session('message.type')}}">
                    {!! session('message.content') !!}
                </div>
            </div>
        @endif

        @yield('content')
        
        <footer id="footer">
            <div class="container">
                <hr>
                &copy; LSi 2018 @if(date('Y') !== '2018') &ndash; {{ date('Y') }} @endif
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
