<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'routes' => $routes,
        ]) !!};
        window.App.baseUrl = window.location.origin;
        </script>
        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div id="app">
            <app></app>
        </div>
    </body>
</html>
