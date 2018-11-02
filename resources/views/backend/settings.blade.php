@extends('backend.Layouts.avalon', ['current' => 'setting'])

@section('content')
    <style>
        .button-container {
            margin: 24px 0;
        }
        .layout-form-panel .wlog-control.option-2 {
            display: none;
        }
    </style>
    <h4>SETTING </h4>

    @include('backend.Widgets.alert')

    <div class="wlog-card layout-card">

        <div class="wlog-tabs layout-tabs">
            <nav id="wlog-tab-bar" class="layout-tabs__container">
                <span class="layout-tab wlog-tab wlog-tab--active">Preference</span>
                <span class="layout-tab wlog-tab">Links</span>
                <span class="layout-tab wlog-tab">Logos</span>
                <span class="layout-tab wlog-tab">Extends</span>
                <span class="wlog__indicator layout-tab-bar__indicator"></span>
            </nav>
        </div>

        <section id="wlog-tab-panels" class="wlog-tab-panels layout-tab-panels">
            <div class="layout-tab-panels__container">

                <div class="wlog-tab-panel layout-tab-panel">
                    <div class="wlog-form-panel layout-form-panel">
                        <form action="{{ url('avalon/options/update') }}" method="POST">
                            {{ csrf_field() }}
                            <fieldset>
                                <div class="other-subjects">
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Web Name</label>
                                        <input type="text" name="web_name" value="{{ $optionsData['web_name'] }}">
                                    </div>
                                    <div class="wlog-textarea__control layout-input__control wlog-control">
                                        <label>Content</label>
                                        <textarea name="content">{{ $optionsData['content'] }}</textarea>
                                    </div>
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Seo Title</label>
                                        <input type="text" name="seo_title" value="{{ $optionsData['seo_title'] }}">
                                    </div>
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Seo Keywords</label>    
                                        <input type="text" name="seo_keywords" value="{{ $optionsData['seo_keywords'] }}">
                                    </div>
                                    <div class="wlog-textarea__control layout-input__control wlog-control">
                                        <label>SEO Description </label>
                                        <textarea name="seo_description">{{ $optionsData['seo_description'] }}</textarea>
                                    </div>
                                </div>
                                <div class="button-container full-width">
                                    <button type="submit" class="wlog-button-rect">Submit</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

                <div class="wlog-tab-panel layout-tab-panel">
                    <div class="wlog-form-list layout-form-list">
                        <div class="subjects-list">
                            <div class="layout-flex-table wlog-flex-table">
                                <div class="flex-table__item flex-table__header">
                                    <span class="logo"> Logo </span>
                                    <span class="name"> Name </span>
                                    <span class="url"> Url </span>
                                    <span class="sort"> Sort </span>
                                    <span class="operate"> Operate </span>
                                </div>
                                @foreach ($linksData as $link)
                                <form method="POST" style="order:{{ $link['sort'] }}">
                                    <div class="flex-table__item flex-table__body" >
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{ $link['id'] }}">
                                        <span class="logo">
                                            <div class="inline-item wlog-cus-select layout-cus-select">
                                                <label><i class="{{ $link['logo'] }} default"></i> <i class="fas fa-sort-down right"></i></label>
                                                <input type="hidden" name="logo" value="{{ $link['logo'] }}">
                                                <ul class="select">
                                                    <li class="select-item" data="fab fa-github">
                                                        <i class="fab fa-github"></i>
                                                    </li>
                                                    <li class="select-item" data="fab fa-google-plus">
                                                        <i class="fab fa-google-plus"></i>
                                                    </li>
                                                    <li class="select-item" data="fab fa-instagram">
                                                        <i class="fab fa-instagram"></i>
                                                    </li>
                                                    <li class="select-item" data="fab fa-twitter">
                                                        <i class="fab fa-twitter"></i>
                                                    </li>
                                                    <li class="select-item" data="fab fa-facebook">
                                                        <i class="fab fa-facebook"></i>
                                                    </li>
                                                    <li class="select-item" data="fab fa-tumblr">
                                                        <i class="fab fa-tumblr"></i>
                                                    </li>
                                                    <li class="select-item" data="fas fa-envelope">
                                                        <i class="fas fa-envelope"></i>
                                                    </li>
                                                    <li class="select-item" data="fab fa-weibo">
                                                        <i class="fab fa-weibo"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </span>
                                        <span class="name">
                                            <input class="flex-table__input" type="text" name="name" value="{{ $link['name'] }}">
                                        </span>
                                        <span class="url">
                                            <input class="flex-table__input" type="text" name="url" value="{{ $link['url'] }}">
                                        </span>
                                        <span class="sort">
                                            <input class="flex-table__input" type="text" name="sort" value="{{ $link['sort'] }}">
                                        </span>
                                        <span class="operate">
                                            <button type="button" class="wlog-button-rect submit-btn">Submit</button>
                                            <button type="button" class="wlog-button-rect destroy">Delete</button>
                                        </span>
                                    </div>
                                </form>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="wlog-form-panel layout-form-panel">
                            <form id="links-create-form" action="{{ url('avalon/links/store') }}" method="POST">
                                {{ csrf_field() }}
                                <fieldset>
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Name</label>
                                        <input type="text" name="name" value="">
                                    </div>
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>URL</label>
                                        <input type="text" name="url" value="">
                                    </div>
                                    <div class="layout-cus-select wlog-cus-select">
                                        <label><i class="fab fa-github default"></i> <i class="fas fa-sort-down"></i></label>
                                        <input type="hidden" name="logo" value="">
                                        <ul class="select">
                                            <li class="select-item" data="fab fa-github"><i class="fab fa-github"></i></li>
                                            <li class="select-item" data="fab fa-google-plus"><i class="fab fa-google-plus"></i></li>
                                            <li class="select-item" data="fab fa-instagram"><i class="fab fa-instagram"></i></li>
                                            <li class="select-item" data="fab fa-twitter"><i class="fab fa-twitter"></i></li>
                                            <li class="select-item" data="fab fa-facebook"><i class="fab fa-facebook"></i></li>
                                            <li class="select-item" data="fab fa-tumblr"><i class="fab fa-tumblr"></i></li>
                                            <li class="select-item" data="fas fa-envelope"><i class="fas fa-envelope"></i></li>
                                            <li class="select-item" data="fab fa-weibo"><i class="fab fa-weibo"></i></li>
                                        </ul>
                                    </div>
                                    <div class="button-container"><button type="submit" class="wlog-button-rect">Create</button></div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="wlog-tab-panel layout-tab-panel">
                    <div class="layout-form-panel wlog-form-panel">
                        <div class="wlog-logos-upload layout-logos-upload">
                            <div class="wlog-file-upload layout-upload-item">
                                <div class="desc-flex">
                                    <p class="body-1">favicon.ico</p>
                                    <p class="body-2">A ICO file for browser tabs</p>
                                </div>
                                <div class="flex-form">
                                    <img src="{{ asset('storage/favicon.ico') }}" alt="">
                                    <div class="wlog-input-file">
                                        <form action="{{ url('avalon/file/uploadLogo') }}" method="POST">
                                            <input type="hidden" name="filename" value="favicon.ico">
                                            <input name="file" type="file" class="manual-file-chooser wlog-input-file-btn">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="wlog-file-upload layout-upload-item">
                                <div class="desc-flex">
                                    <p class="body-1">apple-touch-icon.png</p>
                                    <p class="body-2">A PNG file for mobile browsers (Recommend 180×180)</p>
                                </div>
                                <div class="flex-form">
                                    <img src="{{ asset('storage/apple-touch-icon.png') }}" alt="">
                                    <div class="wlog-input-file">
                                        <form action="{{ url('avalon/imagesUpload') }}" method="POST">
                                            <input type="hidden" name="filename" value="apple-touch-icon.png">
                                            <input name="image" type="file" class="manual-file-chooser wlog-input-file-btn">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="wlog-file-upload layout-upload-item">
                                <div class="desc-flex">
                                    <p class="body-1">logo.png</p>
                                    <p class="body-2">The logo image on your blog homepage (Recommend 256×256)</p>
                                </div>
                                <div class="flex-form">
                                    <img src="{{ asset('storage/logo.png') }}" alt="">
                                    <div class="wlog-input-file">
                                        <form action="{{ url('avalon/imagesUpload') }}" method="POST">
                                            <input type="hidden" name="filename" value="logo.png">
                                            <input name="image" type="file" class="manual-file-chooser wlog-input-file-btn">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wlog-tab-panel layout-tab-panel">
                    @foreach ($optionsExtData as $data)
                    <div class="wlog-form-panel layout-form-panel">
                        <form action="{{ url('avalon/options/updateExt') }}" method="POST">
                            <fieldset>
                                <div class="other-subjects">
                                    @if ($data->data_type == 'text')
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>{{ $data->option_key }}</label>
                                        <input type="text" name="option_value" value="{{ $data->option_value }}">
                                    </div>
                                    @endif
                                    @if ($data->data_type == 'textarea')
                                    <div class="wlog-textarea__control layout-input__control wlog-control">
                                        <label>{{ $data->option_key }}</label>
                                        <textarea name="option_value">{{ $data->option_value }}</textarea>
                                    </div>
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    @endif
                                </div>
                                <div class="button-container full-width">
                                    <button type="button" class="wlog-button-rect wlog-form-submit">Submit</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    @endforeach
                    <!-- <hr> -->
                    <br>
                    <div class="wlog-form-panel layout-form-panel">
                        <form action="{{ url('avalon/options/store') }}" method="POST">
                            {{ csrf_field() }}
                            <fieldset>
                                <div class="other-subjects">
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>option_key</label>
                                        <input type="text" name="option_key" value="" require>
                                    </div>
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Data Type</label>
                                        <select name="data_type" class="select-options" require>
                                            <option value="text">TEXT</option>
                                            <option value="textarea">TEXTAREA</option>
                                        </select>
                                    </div>
                                    <div class="layout-input__control wlog-input__control wlog-control option-1">
                                        <label>option_value</label>
                                        <input type="text" name="option_value" value="" require>
                                    </div>
                                    <div class="wlog-textarea__control layout-input__control wlog-control option-2">
                                        <label>option_value</label>
                                        <textarea name="option_value" require></textarea>
                                    </div>
                                    <input type="hidden" name="type" value="extends">
                                </div>
                                <div class="button-container full-width">
                                    <button type="submit" class="wlog-button-rect">Create</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection

@section('bottom') 
<script src="{{ asset('backend/scripts/settings.js') }}"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.manual-file-chooser').on('change', function() {
        self = $(this);
        let file = $(this)[0].files;
        let filename = $(this).parent().find('input[name="filename"]').val();
        var formData = new FormData();
        formData.append('file', file[0]);
        formData.append('filename', filename);
        $.ajax({
            url: '/avalon/file/uploadLogo',
            type: 'POST',
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                self.parent().parent().parent().find('img').css('opacity', '0.5');
            },
            success: function(result) {
                console.log(result.state)
                if (result.state == 200) {
                    loadImage(self, '/storage'+result.path);
                } else {
                    alert(result.error);
                }
            },
            complete: function() {
                self.parent().parent().parent().find('img').css('opacity', '1');
            }
        })
    });

    $('.wlog-form-submit').click(function() {
        let self = $(this);
        let data = $(this).parent().parent().parent().serialize();
        // console.log(data);
        $.ajax({
            url: '/avalon/options/updateExt',
            data: data,
            type: 'POST',
            cache: false,
            processData: false,
            success: function(result) {
                if (result.state == 200) {
                    alert('success');
                } else {
                    alert('error');
                }
            },
            
        })
    })

    function showRequest() {
        return true;
    }

    function loadImage (self, url) {
        self.parent().parent().parent().find('img').remove();
        var img = new Image();
        img.src = url;
        self.parent().parent().parent().append(img);
        img.onload = function () {
            console.log(img);
            self = null;
            return true;
        };
    };
</script>

<script>
    $(".select-options").on('change', function() {
        if ($(this).val() == 'text') {
            $('.layout-form-panel .wlog-control.option-1').show();
            $('.layout-form-panel .wlog-control.option-2').hide();
        } else {
            $('.layout-form-panel .wlog-control.option-1').hide();
            $('.layout-form-panel .wlog-control.option-2').show();
        }
    });
</script>

@endsection