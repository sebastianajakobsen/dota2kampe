<!doctype html>
<html class="no-js" lang="da">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>DOTA2kampe</title>

    <meta name="description"
          content="@yield('meta-description', "dota 2 kampe")">
    <meta name="keywords"
          content="">
    <meta name="locale" content="da">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1f2e40">


    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/flag-icon.min.css')}}">
    <script src="{{asset('js/vendor/modernizr-2.8.3.min.js')}}"></script>
    @yield('styles')
            <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div class="wrap">
    @if (Auth::check())

        @if(Auth::user()->isAdmin())
            <a href="{{route('admin.dashboard')}}">Admin Panel</a></li>
        @endif

        <p><a href="{{route('frontpage')}}">Forside </a></p>

        Hi {{ Auth::user()->username }}
        <br/>
        <p><a href="{{route('logout')}}">Log af </a></p>
    @else
        <p><a {{ (Request::is('register') ? 'class=' : '') }} href="{{route('register')}}">
                Opret Bruger</a></p>
        <p><a {{ (Request::is('signin') ? 'class=' : '') }} href="{{route('login')}}">
                Log på</a></p>
    @endif

    @yield('content')

</div>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="{{asset('js/main.js')}}"></script>

@yield('scripts')

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function (b, o, i, l, e, r) {
        b.GoogleAnalyticsObject = l;
        b[l] || (b[l] =
                function () {
                    (b[l].q = b[l].q || []).push(arguments)
                });
        b[l].l = +new Date;
        e = o.createElement(i);
        r = o.getElementsByTagName(i)[0];
        e.src = 'https://www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e, r)
    }(window, document, 'script', 'ga'));
    ga('create', 'UA-XXXXX-X', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>
