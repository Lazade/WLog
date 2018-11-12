<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $post->seo_title }}</title>
    <meta content="{{ $post->seo_description }}" name="Description">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('app/styles/app.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $post->seo_title }}">
    <meta name="twitter:description" content="{{ $post->seo_description }}">
    <meta property="og:site_name" content="Lazade">
    <meta property="og:type" content="post">
    <meta property="og:title" content="{{ $post->seo_title }}">
    <meta property="og:description" content="{{ $post->seo_description }}">
    <meta property="og:url" content="https://www.lazade.me/post/{{ $post->flag }}/">
    @if ($post->thumb != '') 
    <meta property="og:image" content="{{ $post->thumb }}">
    @endif
    {{ getGA() }}
</head>
<body>

    <nav class="wlog-post__nav">
        <div class="home">
            <span class="logo"><img src="{{ asset('storage/logo.png') }}" alt=""></span>
            <a href="{{ url('/') }}"></a>
        </div>
    </nav>

    <div class="wlog-post__thumbnail">
        <img src="{{ $post->thumb }}" class="thumbnail">
    </div>

    <section class="wlog-post__info">
        <div class="post-info__content">
            <span class="date"><time class="date" datetime="{{$post->created_at->format('c')}}" itemprop="datePublished" pubdate=""> {{$post->created_at->format('M d, Y')}}</time></span>
            <span class="tag">#<a href="{{ url('/tag/'.$post->tag->tag_flag) }}">{{ $post->tag->tag_name }}</a></span>
        </div>
        <div class="post-title">
            <h1>{{ $post->title }}</h1>
        </div>
    </section>

    <div class="wlog-post__body">
        <article class="post-body__content wlog-md-body">   
            {!! $post->content !!}   
        </article>
    </div>

    <section class="wlog-post__adv"></section>

    @if (!empty($post->previous))
    <section class="wlog-recommend-post">
        <div class="recommend-post__content">
            <a href="{{ url('/post/'.$post->previous->flag) }}">
                <span class="tag">PREVIOUS</span>
                <span class="title">{{ $post->previous->title }}</span>
                <span class="des">{{ $post->previous->seo_description }}</span>
            </a>
        </div>
    </section>
    @endif

    <section class="wlog-post__comments">
        <div class="disqus" id="disqus_thread"></div>
    </section>

    <div class="footer wlog-post__footer">
        <div class="footer__top">
            <a class="foot-link" href="{{ url('/') }}">HOME</a>
            <a class="foot-link" href="{{ url('/achieve') }}">ARCHIVE</a>
        </div>
        <div class="footer__bottom">
            <p>Created with <i class="fas fa-heart"></i> Powered By <a href="https://laravel.com/">Laravel</a></p>
            <p class="str">WLog, Your Log.</p>
        </div>
    </div>

    <script>
    /**
    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
    /*
    var disqus_config = function () {
    this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
    };
    */
    (function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://lazade.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

</body>
</html>