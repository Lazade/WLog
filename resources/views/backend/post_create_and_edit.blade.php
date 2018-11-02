@extends('backend.Layouts.avalon_no_left')

@section('content')

<div class="layout-wrting-area wlog-writing-area">

    @include('backend.Widgets.alert')

    <header class="layout-writing__header wlog-writing__header">
        <a href="{{ url('avalon/posts/') }}" class="wlog-button wlog-button-regular"><i class="fas fa-arrow-left"></i></a>
        <button type="button" id="sidebarToggle" class="wlog-button wlog-button-regular"><i class="fas fa-images"></i></button>
    </header>

    <div class="layout-writing__body wlog-writing__body">
        <div class="wlog-form-panel layout-form-panel">
            @if ($type == 'create')
            <form action="{{ url('avalon/posts/') }}" method="post" id="post-form">
            @else 
            <form action="{{ url('avalon/posts/'.$post->id) }}" method="post" id="post-form">
            {{ method_field('PATCH') }}
            @endif
            {{ csrf_field() }}
            <fieldset>
                <div class="layout-cover-container cover-container">
                    <div class="default-item">
                        <i class="fas fa-image"></i>
                        <p class="icon">Thumb</p>
                    </div>
                    @if ($type == 'create')
                    <div class="drag-place image-place" ondrop="drop(event)" ondragover="allowDrop(event)">
                        <input type="hidden" name="thumb" value="">
                    </div>
                    @else
                    <div class="drag-place image-place" ondrop="drop(event)" ondragover="allowDrop(event)" style="background-image:url({{ $post->thumb }})">
                        <input type="hidden" name="thumb" value="{{ $post->thumb }}">
                    </div>
                    @endif
                </div/>

                <div class="writing-place" id="editor" ondrop="mdrop(event)" ondragover="allowDrop(event)">
                    <textarea name="markdown" id="editorMD">@if ($type == 'edit') {{ $post->markdown }} @endif</textarea>
                </div>

                <div class="other-subjects">
                    <div class="wlog-input__control layout-input__control wlog-control">
                        <label>Title</label>
                        <input type="text" name="title" @if ($type == 'create') value="{{ $title }}" @else value="{{ $post->title }}" @endif>
                    </div>
                    <div class="wlog-input__control layout-input__control wlog-control">
                        <label>SEO Title</label>
                        <input type="text" name="seo_title" @if($type == 'edit') value="{{ $post->seo_title }}" @endif>
                    </div>
                    <div class="wlog-input__control layout-input__control wlog-control">
                        <label>SEO Keywords</label>
                        <input type="text" name="seo_keywords" @if($type == 'edit') value="{{ $post->seo_keywords }}" @endif>
                    </div>
                    <div class="wlog-textarea__control layout-input__control wlog-control">
                        <label>SEO Description</label>
                        <textarea name="seo_description">@if ($type == 'edit') {{ $post->seo_description }}  @endif</textarea>
                    </div>
                    <div class="wlog-input__control layout-input__control wlog-control">
                        <label>Flag</label>
                        <input type="text" name="flag" @if ($type == 'edit') value="{{ $post->flag }}" @endif required>
                    </div>
                    <div class="wlog-input__control layout-input__control wlog-control">
                        <label>Tag</label>
                        <select name="tag_id" required>
                        <option value="">Choose a tag</option>
                        @foreach ( $tagsList as $tag )
                        @if ($type == 'edit') 
                        <option value="{{ $tag->id }}" @if($tag['id'] == $post['tag_id']) selected @endif>{{ $tag->tag_name }}</option>
                        @else 
                        <option value="{{ $tag->id }}">{{ $tag->tag_name }}</option>
                        @endif
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="layout-form-buttons">
                    <button type="submit" class="wlog-button-rect">Submit</button>
                </div>

            </fieldset>
            </form>
        </div>

    </div>

    <div class="wlog-sidebar-menu layout-sidebar-menu" id="wlog-sidebar">
        <div class="layout-sidebar__inner">
            <div id="images-area" class="images-folder">
                <button class="btn btn-info wlog-button-rect" id="getMoreBtn">MORE</button>
                <div class="folder-container"></div>
            </div>

            <div class="operation-board">
                <form id="wlog-ajax-form" class="fab-form">
                    <div id="progress_bar"></div>
                    <div class="buttons-area">
                        <div class="wlog-button-rect">
                            new
                            <input type="file" id="file" class="manual-file-chooser wlog-input-file-btn" multiple>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@section('bottom')
<link href="{{ asset('backend/styles/simplemde.min.css') }}" rel="stylesheet">
<script src="{{ asset('backend/scripts/simplemde.min.js') }}" charset="utf-8"></script>
<script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>
<link href="https://cdn.jsdelivr.net/highlight.js/latest/styles/github.min.css" rel="stylesheet">
<script src="{{ asset('backend/scripts/md5.js') }}"></script>

<style>
    .CodeMirror, .CodeMirror-scroll {
        min-height: 500px;
    }
</style>
<script>
    var editor = new SimpleMDE({ 
        autoFocus : true,
        element: document.getElementById("editorMD"),
        status: false,
        toolbar: false,
        forceSync: true,
        placeholder: "Type here...",
    });
</script>
<script>

    $('#images-area .folder-container').on('click', 'button', function(e) {
        let currentTarget = e.target;
        let imgSrc = $(currentTarget).parent().parent().find('img').attr('src');
        if ($(currentTarget).hasClass('wlog-set-thumb')) {
            $('.image-place').find('input').val(imgSrc);
            $('.image-place')[0].style.backgroundImage = 'url('+imgSrc+')';
        } else if ($(currentTarget).hasClass('wlog-insert')) {
            let mdString = '![]('+imgSrc+')';
            insertString = editor.value() + '\n' + mdString;
            editor.value(insertString);
        } else {
            return;
        }
    });

    function fileDetect(files) {
        var namesList = new Array();
        for (var i in files) {
            if (typeof(files[i]) != 'object') {
                continue;
            }
            if ( /image\/\w+/.test(files[i].type) ) {
                namesList.push(files[i].name);
            } else {
                return false;
            }
        }
        return namesList;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#getMoreBtn').click(function(){
        var _this = $(this);
        $.ajax({
            url: '/avalon/file/getMore',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#images-area .folder-container').html('');
                _this.addClass('loading');
            },
            success: function(result) {
                for (var i in result.data) {
                    var div = '<div class="image-list-card"><div class="image-file"><img src="'+result.data[i]['path']+'" alt="" /></div><div class="hidden-oper"><button class="wlog-set-thumb">As thumb</button><button class="wlog-insert">As insert</button></div></div>';
                    $('#images-area .folder-container').append(div);
                }
            },
            complete: function() {
                _this.removeClass('loading');
            },
        });
    });

    $('#file').on('change', function() {
        if ($('#file').val() == '') return;
        var path = $('#file')[0].files;
        var ret = fileDetect(path);
        if (!ret) {
            return;
        } 
        var formData = new FormData();
        var names = '';
        for ( var i = 0, name; i < path.length; i++ ) {
            name = $.md5(path[i].name);
            formData.append(name, path[i]);
            names += name + ',';
        }
        formData.append('info', names);
        $.ajax({
            url: '/avalon/file',
            type: 'POST',
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#progress_bar').show();
                $('#progress_bar').css('width', 0);
            },
            success: function(result) {
                if (result.status == 200) {
                    for (var i in result.info) {
                        if (result.info[i].head_state == 200) {
                            var div = '<div class="image-list-card"><div class="image-file"><img src="'+result.info[i].url+'" alt="" /></div><div class="hidden-oper"><button class="wlog-set-thumb">As thumb</button><button class="wlog-insert">As insert</button></div></div>';
                            $('#images-area .folder-container').append(div);
                        }
                    }
                }
            },
            xhr: function() {
                var xhr = $.ajaxSettings.xhr();
                if (onprogress && xhr.upload) {
                    xhr.upload.addEventListener("progress", onprogress, false);
                    return xhr;
                }
            }
        });
    });
</script>
@endsection