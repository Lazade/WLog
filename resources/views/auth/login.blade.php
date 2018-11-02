<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} -- Login</title>
    <!-- Styles -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('backend/styles/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/styles/backend.css') }}">
</head>
<body class="wlog-login home">
    <div class="wlog-login-panel">
        <header>
            <div class="logo"><img src="{{ asset('storage/logo.png') }}" alt="WLog"></div>
            <p class="name">WLOG - Login</p>
        </header>
        <div class="wlog-form-panel layout-form-panel">
            <form method="POST" action="{{ route('admin.login') }}" aria-label="{{ __('Login') }}">
                @csrf
                <fieldset>
                    <div class="other-subjects">
                        <div class="wlog-input__control layout-input__control wlog-control">
                            <label>Email/Name</label>    
                            <input type="text" id="username-input" name="emailorname" required autofocus>
                        </div>
                        <div class="wlog-input__control layout-input__control wlog-control">
                            <label>Password</label>
                            <input type="password" id="password-input" name="password" required>
                        </div>
                        <div class="wlog-input__control layout-input__control wlog-control" style="align-items:center;">
                            <input type="checkbox" id="remember" name="remember" />
                            <label for="remember">Remember?</label>
                        </div>
                    </div>
                    <div class="layout-form-buttons">
                        <button type="submit" class="wlog-button-rect">Login</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>
</html>