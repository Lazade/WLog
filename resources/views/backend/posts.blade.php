@extends('backend.Layouts.avalon', ['current' => 'post'])

@section('content')

    @include('backend.Widgets.alert')

    <h4>Post</h4>

    <div class="layout-card-form wlog-card-form">
        <form action="{{ url('avalon/posts/create') }}" method="GET">
            <div class="layout-card-input wlog-card-input">
                <input type="text" name="title" placeholder="type the title">
            </div>
            <button type="submit" class="wlog-button-rect">Create a Post</button>
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
                        <th>Title</th>
                        <th>Tag</th>
                        <th>State</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $listData as $article )
                        <tr id="delte-id-{{ $article->id }}">
                            <td><input type="checkbox" name="id[]" value="{{ $article->id }}"></td>
                            <td>{{ $article->id }}</td>
                            <td><a href="{{ url('/post/'.$article->flag) }}" target="_blank">{{ $article->title }}</a></td>
                            <td><a href="{{ url('avalon/tags?search='.$article->tag->tag_name) }}"> {{ $article->tag->tag_name }} </a></td>
                            <td>
                                <a href="{{ url('avalon/posts/changeState/'.$article->id) }}" class="switch-track wlog-change-state">
                                    @if ($article->state) 
                                        <i class="fas fa-eye"></i>
                                    @else
                                        <i class="fas fa-eye-slash"></i>
                                    @endif
                                </a>
                            </td>
                            <td>{{ $article->created_at->format('Y-m-d H:i') }}</td>
                            <td class="buttons-area">
                                <a href="{{ url('avalon/posts/'.$article->id.'/edit') }}" class="wlog-button wlog-button-small"><i class="fas fa-edit"></i></a>
                                <form action="{{ url('avalon/posts/'.$article->id) }}" method="POST" style="display: inline-flex;">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button type="button" class="wlog-button wlog-button-small wlog-delete-action"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <button class="wlog-button wlog-button-small wlog-get-action" data-url="{{ url('/avalon/posts/publish/'.$article->id) }}"><i class="fas fa-paper-plane"></i></button>
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

@section('bottom')

<script>    

</script>

@endsection