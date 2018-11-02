@extends('backend.Layouts.avalon', ['current' => 'trash'])

@section('content')

    <h4>TRASH</h4>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

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
                        <th>Title</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Deleted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $listData as $item )
                        <tr id="delte-id-{{ $item->id }}">
                            <td><input type="checkbox" name="id[]" value="{{ $item->id }}"></td>
                            <td><a href="{{ url('/post/'.$item->flag) }}" target="_blank">{{ $item->title }}</a></td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td>
                            <td>{{ $item->deleted_at }}</td>
                            <td class="buttons-area">
                                <form action="{{ url('avalon/trash/'.$item->id) }}" method="POST" style="display: inline-flex;">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button type="button" class="wlog-button wlog-button-small wlog-delete-action"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <form action="{{ url('avalon/trash/restore/'.$item->id) }}" method="POST" style="display: inline-flex">
                                    {{ csrf_field() }}
                                    <button type="button" class="wlog-button wlog-button-small wlog-delete-action"><i class="fas fa-redo-alt"></i></button>
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