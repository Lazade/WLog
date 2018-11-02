@extends('backend.Layouts.avalon', ['current' => 'tag'])

@section('content')

    @include('backend.Widgets.alert')

    <h4>TAG </h4>

    <div class="layout-card-form wlog-card-form">
        <form action="{{ url('avalon/tags') }}" method="POST">
            {{ csrf_field() }}
            <div class="layout-card-input wlog-card-input">
                <input type="text" name="tag_name" placeholder="tag name">
            </div>
            <div class="layout-card-input wlog-card-input">
                <input type="text" name="tag_flag" placeholder="tag flag">
            </div>
            <button type="submit" class="wlog-button-rect">Create a Tag</button>
        </form>
    </div>
    
    <div class="wlog-card">
        <div class="wlog-table layout-table">
            <div class="wlog-table__action layout-table__action">
                <div class="wlog-table-action-input">
                    <i class="fas fa-search"></i><input type="text" id="inner-q" placeholder="">
                </div>
            </div>
            <table id="dataTable" class="layout-table__datatable">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Count</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $listData as $tag )
                        <tr id="delte-id-{{ $tag->id }}">
                            <td><input type="checkbox" name="id[]" id="{{ $tag->id }}"></td>
                            <td>{{ $tag->id }}</td>
                            <td><a href="{{ url('/tag/'.$tag->tag_flag) }}">{{ $tag->tag_name }}</a></td>
                            <td><a href="{{ url('avalon/posts?search='.$tag->tag_name) }}" >{{ $tag->count }}</a></td>
                            <td class="buttons-area">
                                <a href="{{ url('avalon/tags/'.$tag->id.'/edit') }}" class="wlog-button wlog-button-small"><i class="fas fa-edit"></i></a>
                                <form action="{{ url('avalon/tags/'.$tag->id) }}" method="POST" style="display: inline-flex;">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button type="button" class="wlog-button wlog-button-small wlog-delete-action"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="layout-table__footer wlog-table__footer">
                {{ $listData->links('backend.Widgets.paginate') }}
            </div>
        </div>
    </div>

    @include('backend.Widgets.message')

@endsection
