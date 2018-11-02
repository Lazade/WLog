@extends('app.Layouts.header', ['current' => $current])

@section('wlog-content')

<div class="wlog-body__inner">
    <h2 class="wlog-page-name">#{{ $tag->tag_name }}</h2>
    <div class="wlog-body__content">
        @foreach ($posts as $post)
        <article class="wlog-body-article">
            @if ($post->thumb) 
            <img class="article-thumb" src="{{ $post->thumb }}">
            @endif
            <a href="{{ url('/post/'.$post->flag) }}">
                <div class="article-content">
                    <div class="date">{{ date('M d, Y',strtotime($post->updated_at)) }}</div>
                    <div class="title">{{ $post->title }}</div>
                </div>
            </a>
        </article>
        @endforeach
    </div>
</div>

@endsection

@section('footer')

@endsection