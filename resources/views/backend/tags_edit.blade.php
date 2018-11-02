@extends('backend.Layouts.avalon_no_left')

@section('content')
<div class="layout-wrting-area wlog-writing-area">

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <header class="layout-writing__header wlog-writing__header">
        <a href="{{ url('avalon/tags/') }}" class="wlog-button wlog-button-regular"><i class="fas fa-arrow-left"></i></a>
    </header>

    <div class="layout-writing__body wlog-writing__body">
        <div class="wlog-form-panel layout-form-panel">
            <form action="{{ url('avalon/tags/'.$data->id) }}" method="POST">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                <fieldset>
                    <div class="other-subjects">
                        <div class="wlog-input__control layout-input__control wlog-control">
                            <label>Name</label>
                            <input type="text" name="tag_name" value="{{ $data->tag_name }}">
                        </div>
                        <div class="wlog-input__control layout-input__control wlog-control">
                            <label>Tag Flag</label>
                            <input type="text" name="tag_flag" value="{{ $data->tag_flag }}">
                        </div>
                    </div>
                    <div class="layout-form-buttons">
                        <button type="submit" class="wlog-button-rect">Submit</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@endsection