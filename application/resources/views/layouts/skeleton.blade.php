<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @section("head")
        @show
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        @routes
        <form method="POST" action="{{ route('logout') }}" class="logout">
            @csrf
            <button class="btn logout-btn" type="submit">Logout</button>
        </form>
        <div id="app">
        @yield('content')
        </div>
        <script src="{{ mix("js/app.js")}}"></script>
    </body>

</html>
