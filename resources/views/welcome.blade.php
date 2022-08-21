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
        document.documentElement.style.setProperty('--color-primary', '#94d41c');
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Scripts -->
        
        <script src="{{ mix('js/app.js') }}" defer></script>
        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="coverage" id="body">
        <div id="app">
            <app></app>
        </div>
    </body>
</html>
