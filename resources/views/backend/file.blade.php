@extends('backend.Layouts.avalon', ['current' => 'photo'])

@section('content')

    <header class="layout-tool-bar wlog-tool__header">
        <div class="tool-bar__inner">
            <div class="tool-bar__action search">
                <i class="fas fa-search"></i><input type="text" placeholder="Search...">
            </div>
            <div class="tool-bar__action buttons-area">
                <button class="wlog-button wlog-button-small wlog-refresh-button" id="refresh"><i class="fas fa-sync"></i></button>
                <button class="wlog-button wlog-button-small wlog-info-button"><i class="fas fa-info"></i></button>
                <button class="wlog-button wlog-button-small wlog-upload-button"><i class="fas fa-cloud-upload-alt"></i></button>
                <button class="wlog-button wlog-button-small wlog-delete-option-button"><i class="fas fa-trash"></i></button>
                <button class="wlog-button wlog-button-small hidden wlog-delete-confirm"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div class="wlog-floating-area layout-floating-area">
            <div class="layout-floating-form__hidden wlog-upload-form wlog-floating">
                <div class="wlog-button-rect">
                    <i class="fas fa-upload"></i> Upload File
                    <input type="file" id="file" class="wlog-input-file-btn" multiple>
                </div>
                <div id="progress" class="layout-progress wlog-progress">
                    <div class="layout-progress__inner">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="layout-floating-info__hidden wlog-info-table wlog-floating">
                <div id="info-table" class="progress-table"></div>
            </div>
        </div>
    </header>

    <h4>Photo </h4>

    <div class="wlog-card">

        <section class="wlog-files">
            <section class="layout-files__container">
                <div class="layout-img__list wlog-img__list">
                @foreach ($files as $file)
                <div class="layout-img__item wlog-img__item">
                    <div class="item__inner">
                        <div class="img-container">
                        <img src="//drive.google.com/uc?id={{ $file['id'] }}" alt="{{ $file['name'] }}" id_value="{{ $file['id'] }}" >
                        </div>
                        <div class="info">
                            <p class="info-name">{{ $file['name'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
            </section>
        </section>
    </div>


    <div class="layout-cover wlog-cover"></div>

@endsection

@section('bottom')
<script src="{{ asset('backend/scripts/md5.js') }}"></script>
<script src="{{ asset('backend/scripts/wlog-file.js') }}"></script>
@endsection