<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} - Dashboard</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/styles/backend.css') }}">
</head>
<body class="home layout__home">
    <!--  -->
    <nav class="wlog-drawer layout__drawer">
        <div class="layout-draw__inner-container">
            <header class="wlog-header layout-drawer__header">
                <img src="{{ asset('storage/logo.png') }}" alt=""><span>WLOG</span>
            </header>
            <div class="layout-drawer__content wlog-drawer__content">
                <nav class="layout-drawer__list">
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'dashboard') active @endif" href="{{ url('avalon/') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>

                    <div class="layout-drawer__group wlog-drawer__group">
                        <i class="fas fa-th"></i> Resources
                    </div>
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'post') active @endif" href="{{ url('avalon/posts/') }}">
                        <i class="fas fa-book"></i> Post
                    </a>
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'tag') active @endif" href="{{ url('avalon/tags/') }}">
                        <i class="fas fa-tags"></i> Tag
                    </a>
                    <!-- <a class="layout-drawer-list__item wlog-list__item @if ($current == 'gallery') active @endif" href="{{ url('avalon/gallery/') }}">
                        <i class="material-icons">photo_library</i> GALLERY
                    </a> -->
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'trash') active @endif" href="{{ url('avalon/trash/') }}">
                        <i class="fas fa-trash"></i> Trash
                    </a>
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'photo') active @endif" href="{{ url('avalon/file/') }}">
                        <i class="fas fa-images"></i> Photo
                    </a>
                    <!-- <a class="layout-drawer-list__item wlog-list__item @if ($current == 'subscription') active @endif " href="{{ url('avalon/subscription/') }}">
                        <i class="fas fa-envelope"></i> Subscription
                    </a> -->
                    <div class="layout-drawer__group wlog-drawer__group">
                        <i class="fas fa-cogs"></i> Preferences
                    </div>
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'setting') active @endif" href="{{ url('avalon/settings/') }}">
                        <i class="fas fa-cog"></i> Setting
                    </a>
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'profile') active @endif" href="{{ url('avalon/profile/') }}">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                    
                    <div class="layout-drawer__group wlog-drawer__group">
                        <i class="fas fa-flask"></i> LAB
                    </div>
                    <a class="layout-drawer-list__item wlog-list__item @if ($current == 'lab') active @endif" href="{{ url('avalon/lab') }}">
                        <i class="fas fa-wrench"></i> Lab
                    </a>
                </nav>
            </div>
        </div>
    </nav>
    <div class="wlog-body layout-body">
        <div class="layout-body__inner-container">
            <header class="layout-body-header wlog-body-header">
                <div class="layout-body-header__left wlog-body-header__left">
                    <a href="{{ url('/') }}"><i class="fas fa-home"></i> Home Page</a>
                </div>
                <div class="layout-body-header__right wlog-body-header__right">
                    <button class="wlog-button wlog-button-regular" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i></button>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            </header>
            <section class="layout-body-main wlog-body-main">
                @yield('content')
            </section>
        </div>
    </div>
</body>
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('backend/scripts/backend-common.js') }}"></script>
@yield('bottom')
</html>