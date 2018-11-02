@extends('backend.Layouts.avalon', ['current' => 'dashboard'])

@section('content')

    <h4 class="wlog-layout__h2">Dashboard </h4>

    <section >
        <div id="dashboard_meta" class="wlog-meta-card__list layout-meta-card__list">
            <div class="layout-meta-card wlog-meta-card">
                <p class="body-1">Overview</p>
                <ul>
                    <li class="pcount"><i class="fas fa-book"></i>(<a href="{{ url('/avalon/posts/') }}">{{ $pcount }}</a>)</li>
                    <li class="tpcount"><i class="fas fa-trash"></i>(<a href="{{ url('/avalon/trash/') }}">{{ $tpcount }}</a>)</li>
                </ul>
            </div>
            <div class="layout-meta-card wlog-meta-card" id="pList">
                <p class="body-1">Recent Posts</p>
                <ul>
                    @foreach ($recent_posts as $post)
                    <li><span>{{ $post->created_at->format('Y-m-d') }}</span><a href="{{ url('/post/'.$post->flag) }}">{{ $post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="layout-meta-card wlog-meta-card">
                <p class="body-1">Activity</p>
                
            </div>
        </div>
    </section>

    <div class="layout-card wlog-card">
        <div class="cont-body">
            <p class="body-1">Welcome to WLOG. </p>
            <p class="body-2">What's New Today? </p>
        </div>
    </div>
    <br/><br/>
    <div class="layout-form-buttons">
        <button type="button" class="wlog-button-rect wlog-get-action" data-url="{{ route('refreshSetting') }}"><i class="fas fa-cog"></i> Refresh Settings</button>
    </div>

    @include('backend.Widgets.message')

@endsection

@section('bottom')

@endsection
