<!DOCTYPE html>
<html lang="en">
<head>
    <title>@if ($current != 'home') {!! $current !!} - @endif{{ bloginfo('seo_title') }}</title>
    <meta content="{{ bloginfo('seo_description') }}" name="Description">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/apple-touch-icon.png') }}">    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('app/styles/app.css') }}">
    {{ getGA() }}
</head>

<body>

    <header class="wlog-app-header">
        <div class="home" id="home-slide">
            <span class="logo"><img src="{{ asset('storage/logo.png') }}" alt="{{ env('APP_NAME') }}"></span>
            <span class="slide">{{ bloginfo('web_name') }}</span>
        </div>
    </header>

    <nav class="wlog-app-nav">
        <div class="app-nav__inner">

            <div class="nav-item__top app-nav__item">
                <div class="item__content">
                    <a class="avatar" href="{{ url('/') }}">
                        <img src="{{ asset('storage/logo.png') }}" alt="{{ env('APP_NAME') }}">
                    </a>
                    <div class="web-name">
                        <p class="name">{{ bloginfo('web_name') }}</p> 
                        <a href="{{ url('/feed/') }}" class="link rss-feed"><i class="fas fa-rss"></i></a>
                    </div>
                    <p>{!! bloginfo('content') !!}</p>
                </div>
            </div>

            <div class="nav-item__middle app-nav__item">
                <div class="item__content">
                    <ul>
                        <li class="@if ($current == 'home') active @endif"><a href="{{ url('/') }}" class="link">Post</a></li>
                        <!-- <li class="@if ($current == 'gallery') active @endif"><a href="" class="link">Gallery</a></li> -->
                    </ul>
                </div>
            </div>

            <div class="nav-item__bottom app-nav__item">
                <ul>
                    {{ bloglinks() }}
                </ul>
            </div>

        </div>
    </nav>

    <div class="body wlog-app-body">

        @yield('wlog-content')

        <div class="footer">
            <div class="footer__top">
                @if ($current != 'archive')
                <a href="{{ url('/archive/') }}">ARCHIVE</a>
                @endif
                <a href="{{ url('/') }}">HOME</a>
            </div>
            <div class="footer__bottom">
                <p>Created with <i class="fas fa-heart"></i> Powered By <a href="https://laravel.com/">Laravel</a></p>
                <p class="str">WLog, Your Log.</p>
            </div>
        </div>

    </div>
    <div class="cover-shelt"></div>
</body>

<script type="text/javascript" src="{{ asset('app/scripts/app.js') }}"></script>
@yield('footer')
</html>