<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token()}}">
        <script> window.Laravel = { csrfToken : '{{csrf_token()}}'}</script>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
    .popup {
  float: right;
  position: relative;
  display: inline-block;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* The actual popup */
.popup .popuptext {
  visibility: hidden;
  width: 160px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 1;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s;
}
.show-popup{
    display: block;
}
.hide-popup{
    display: none;
}
.btn-action-list{
    color: cornsilk;
    padding-left: 25px;
}
.filter-input-select{
    width: 95px; 
    margin-top: 4px;
    height: 38px;
}
.btn-action-list-element{
    cursor: pointer;
    color: initial;
    font-size: 17px;
    font-weight: 500;
    text-decoration: none;
}
.span-action-list{
    cursor: pointer;
    text-decoration: none;
    margin: 9px;
    font-size: 25px;
    font-weight: 700;
}
.btn-action-list-element:hover{
    font-size: 18px;
    font-weight: 700;
}
.action-list-popup {
  border-radius: 12px;
  background-color: darkgray;
  width: 80px;
  position: fixed;
  border: 1px solid cyan;
  z-index: 9;
  margin-top: -20px;
  margin-left: 36px;
  padding: 6px;
}
.row-selected{
    background-color: grey;
}
.row-grid{
    height: 50px;
}
/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}
</style>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Reserve Your Doctor</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                       <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item active">
                       <a class="nav-link" href="{{ url('/control_panel') }}">Control Panel 
                        </a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Language <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left" style="min-width:7rem;" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item">
                            English
                        </a>
                        <a class="dropdown-item">
                            Arabic
                        </a>
                    </div>
                </li>
                <!-- Authentication Links -->
                @guest
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->full_name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" style="min-width:7rem;"  aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
            </div>
           
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}" ></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}" ></script>
</body>
</html>
