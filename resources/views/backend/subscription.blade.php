@extends('Layouts.avalon', ['current' => 'subscription'])

@section('content')

<div class="wlog-main wlog-content">
    <div class="cont-list">
        <div class="card md-card">
            <div class="cont-nav">
                <h2>SUBSCRIPTION </h2>
            </div>

            <div class="cont-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        {!! implode('<br>', $errors->all()) !!}
                    </div>
                @endif
                <div class="cont-table md-data-table">
                    <table id="dataTable" class="table table-striped table-bordered datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>State</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $subscriptions as $subscription )
                                <tr id="delte-id-{$subscription->id}">
                                    <td>{{ $subscription->id }}</td>
                                    <th>{{ $subscription->emial }}</th>
                                    <td>
                                        @if ($subscription->state)
                                        <div class="switch-track active">
                                            <span class="switch"></span>
                                        </div>
                                        @else
                                        <div class="switch-track">
                                            <span class="switch"></span>
                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ $subscription->created_at }}</td>
                                    <td>{{ $subscription->updated_at }}</td>
                                    <td>
                                        <form action="{{ url('avalon/article/'.$subscription->id) }}" method="POST" style="display: inline;">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="md-btn btn-red"><i class="far fa-trash-alt" title="delete"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection