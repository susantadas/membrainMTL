@extends('layouts.app')
@section('title','Campaign')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>List of Campaigns</h3>
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('delete_error'))
                <div class="alert alert-warning">{{ session('delete_error') }}</div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('success') !!}</em></div>
            @endif
            <div class="bs-example4" data-example-id="contextual-table">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="campaign">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Campaign Name</th>
                            <th>Public ID</th>
                            <th>Client Name</th>
                            <th>Campaign Type</th>
                            <th>Active</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $key => $campaign)
                            <tr class="active" id="campaign-id-{{$campaign->id}}">
                                <td scope="row">{{$key+1}}</td>
                                <td> {{ $campaign->name }} </td>
                                <td> {{ $campaign->public_id }} </td>
                                <td> {{ $campaign->client_name }} </td>
                                <td> {{ $campaign->method }} </td>
                                <td>@if($campaign->active == 1)<i class="fa fa-check" aria-hidden="true"></i>@endif</td>
                                <td><a href="{{ url('/campaign/'.$campaign->id.'/edit') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="javascript:void(0)" data-val="{{$campaign->id}}" class="cDelete"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#campaign').DataTable();
                    });
                    $(document).on('click','.cDelete',function(){
                        var id = $(this).attr('data-val');
                        if (confirm('Are you sure you want to delete this?')) {
                            jQuery.ajax({
                                type: 'POST',
                                url: "{{ url('/campaign') }}/"+id,
                                data: { _token:'{{ csrf_token() }}', _method:'DELETE'} , 
                                success: function(result){
                                    if(result==1){
                                        jQuery('#success-errors').html('<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em>Campaign has been deleted</em></div>');
                                        jQuery('#campaign-id-'+id).remove();
                                        $('#campaign').DataTable();
                                    }
                                }
                            });
                        }
                    });
                </script>                        
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection