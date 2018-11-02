<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} - Type your Log...</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/styles/backend.css') }}">
</head>

<body class="layout__home home">
    <div class="wlog-body layout-body layout-body_full">
        <div class="layout-body__inner-container">
            @yield('content')
        </div>
    </div>
</body>
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('backend/scripts/backend-common.js') }}"></script>

@yield('bottom')

</html>