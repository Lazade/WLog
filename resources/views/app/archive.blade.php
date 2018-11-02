@extends('app.Layouts.header', ['current' => $current])

@section('wlog-content')

<div class="wlog-body__inner">

    <h2 class="wlog-page-name">ARCHIVE-{{ $data['thisYear'] }}</h2>
    <div class="wlog-body__content">
        @foreach ($data['posts'] as $post)
        <article class="wlog-body-article">
            @if ($post['thumb']) 
            <img class="article-thumb" src="{{ $post['thumb'] }}">
            @endif
            <a href="{{ url('/post/'.$post['flag']) }}">
                <div class="article-content">
                    <div class="date">{{ date('M d, Y',strtotime($post['updated_at'])) }}</div>
                    <div class="title">{{ $post['title'] }}</div>
                </div>
            </a>
        </article>
        @endforeach
    </div>
    <div class="wlog-body__footer">
        <div class="wlog-archive__paginate">
            @if ($data['lastYear'] != '') 
            <a href="{{ url('/archive/'.$data['lastYear']) }}" class="page-button"><i class="fas fa-angle-left"></i>{{ $data['lastYear'] }}</a>
            @endif
            
            @if ($data['nextYear'] != '')
            <a href="{{ url('/archive/'.$data['nextYear']) }}" class="page-button">{{ $data['lastYear'] }}<i class="fas fa-angle-right"></i></a>
            @endif
        </div>
    </div>
</div>

@endsection