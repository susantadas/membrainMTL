@extends('layouts.app')
@section('title','Multiple Api')
@section('content')
    <div class="xs">
        <h3>List of Multiples Api</h3>
        <div class="bs-example4" data-example-id="contextual-table">
            <div id="success-errors">
                @if(Session::has('success'))
                    <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('success') !!}</em></div>
                @endif
            </div>
            <table class="table table-striped table-bordered" id="multipleapi">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>type</th>
                        <th>Country</th>
                        <th>Api Url</th>
                        <th>Active</th>
                        <th>Created Time</th>
                        @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($multipleapis as $key => $multipleapi)
                        <tr class="active" id="multipleapi-id-{{$multipleapi->id}}">
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$multipleapi->type}}</td>
                            <td>{{$multipleapi->name}}</td>
                            <td>{{$multipleapi->apiurl}}</td>
                            <td>@if($multipleapi->status)<i class="fa fa-check" aria-hidden="true"></i>@endif</td>
                            <td>{{ date('jS M Y h:i:s a', strtotime($multipleapi->created_at)) }}</td>
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
                                <td><a href="{{ url('/multipleapi/'.$multipleapi->id.'/edit') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="javascript:void(0);" onclick="destroy({{$multipleapi->id}})"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            @endif
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('#multipleapi').DataTable();
                });
                function destroy(id) {
                    if (confirm('Are you sure you want to delete this?')) {
                        jQuery.ajax({
                            type: 'POST',
                            url: "{{ url('/multipleapi') }}/"+id,
                            data: { _token:'{{ csrf_token() }}', _method:'DELETE'} , 
                            success: function(result){
                                if(result==1){
                                    jQuery('#success-errors').html('<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em>Multiple Api User has been deleted</em></div>');
                                    jQuery('#multipleapi-id-'+id).remove();
                                }
                            }
                        });
                    }
                }
            </script>                        
        </div>
    </div>
@endsection