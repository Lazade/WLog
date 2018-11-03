@extends('app.Layouts.header', ['current' => $current])

@section('wlog-content')
<div class="wlog-body__inner">
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
    <div class="wlog-body__footer">
        {{ $posts->links('app.Widgets.paginate') }}
    </div>
</div>
@endsection

@section('footer')

@endsection