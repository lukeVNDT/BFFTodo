<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>BFF Todo</title>

        <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css')}}">
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    </head>
    <body class="antialiased">
        <div id="app">
            <app-component></app-component>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </body>
</html>
