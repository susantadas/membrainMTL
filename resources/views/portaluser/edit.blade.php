{{--
    Input Parameters:
        @Contact Username (Unique email)
        @Contact Email (Unique)
        @Contact Full Name
        @Contact Password
        @Contact Confirm Password
        @Role(Select Dropdown)
        @Supplier(Select Dropdown) When role select supplier
        @Client(Select Dropdown) When role select Client
        @Clients(Select Dropdown) When role select Super Client
        @Active (Chheckbox)
        Author: Latitude Global Partners
--}}

@extends('layouts.app')
@section('title','Edit/Portal User')
@section('content')
    @if(Auth::user()->role_id==1)
        <div class="xs">
            <h3>Edit Portal User</h3>
            <div class="well1 white">                
                @if(Auth::user()->role_id==1)                    
                    <div id="massage"></div>
                    <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" name="userUpdate" method="post" action="{{ action('PortaluserController@update',$portaluser->id) }}">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}
                        <fieldset>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="control-label">Username</label>
                                    <input type="email" class="form-control1 ng-invalid ng-valid-email ng-invalid-required ng-touched" id="username" name="username" value="{{$portaluser->username}}" />
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Name</label>
                                    <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" value="{{$portaluser->name}}" />
                                </div>
                            </div>
                            <div class="form-group filled">
                                <div class="col-sm-6">
                                    <label class="control-label">Role </label>
                                    <select class="form-control1 ng-invalid ng-invalid-required" disabled="disabled">
                                        <option value="" @if($portaluser->id=='') selected @endif>Please Select Role</option>
                                        @foreach($portalroles as $portalrole)
                                            <option value="{{$portalrole->id}}" @if($portalrole->id==$portaluser->role_id) selected @endif>{{$portalrole->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden"  name="role_id" id="role_id" value="{{$portaluser->role_id}}" />
                                </div>
                                <div class="col-sm-6 supplierid" @if($portaluser->role_id!='3') style=display:none @endif>
                                    <label class="control-label">Supplier</label>
                                    <select class="form-control1 ng-invalid ng-invalid-required"  disabled="disabled">
                                        <option value="">Please Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}" @if($supplier->id==$portaluser->supplier_id) selected @endif>{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                   <input type="hidden"  name="supplier_id" id="supplier_id" value="{{$portaluser->supplier_id}}" />
                                </div>
                                <div class="col-sm-6 clientid" @if($portaluser->role_id!='4') style=display:none @endif>
                                    <label class="control-label">Client</label>
                                    <select class="form-control1 ng-invalid ng-invalid-required"  disabled="disabled">
                                        <option value="">Please Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}" @if($client->id==$portaluser->client_id) selected @endif>{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden"  name="client_id" id="client_id" value="{{$portaluser->client_id}}" />
                                </div>
                                <div class="col-sm-6 clientsid" @if($portaluser->role_id!='5') style=display:none @endif>
                                    <label class="control-label">Client</label>
                                    <select class="form-control1 ng-invalid ng-invalid-required" name="clients_id[]" id="clients_id" multiple="multiple">
                                        <option value="">Please Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}" @if(in_array($client->id,$supclients_arr)) selected @endif>{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>   
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="control-label">Created</label>
                                    <input type="text" class="form-control1 " id="created" name="created" value="{{$portaluser->created_at}}" readonly="readonly" />
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Last Login</label>
                                    <input type="text" class="form-control1 " id="last_login" name="last_login" value="{{$portaluser->last_login}}"  readonly="readonly" />
                                </div>
                                <div class="clearfix"></div>
                            </div>                 
                            <div class="form-group">
                                <div class="clearfix"></div>
                                <div class="col-sm-6">
                                     <div class="checkboxb checkbox-primary">
                                            <input id="active" name="active" value="1" type="checkbox" <?php if($portaluser->active==1){?>checked="checked" <?php } ?>>
                                            <label for="active">Active</label>
                                        </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="reset" class="btn btn-default">Reset</button>
                                </div>
                                <div class="col-sm-6">
                                    <div class="delete-left-1">
                                        <button type="delete" id="delete" disabled="disabled" class="btn btn-danger" onclick="destroy({{$portaluser->id}})">Delete</button>
                                    </div>
                                    <div class="delete-left-2">
                                        <div class="checkboxb checkbox-primary">
                                            <input id="confirm" type="checkbox">
                                            <label for="confirm">Delete Confirmation</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </fieldset>
                    </form>   
                @else
                    <div  class="alert alert-danger" >You are not allowed to access this page</div>
                @endif
                <script type="text/javascript">
                    /* Start validation form and store data using ajax */
                    jQuery(function() {
                        $.validator.addMethod("regex",function(value, element, regexp) {
                            if (regexp.constructor != RegExp)
                                regexp = new RegExp(regexp);
                            else if (regexp.global)
                                regexp.lastIndex = 0;
                            return this.optional(element) || regexp.test(value);
                        },"Please check your input.");
                        jQuery("form[name='userUpdate']").validate({
                            rules: {
                                username: {
                                    required: true,
                                    email: true,
                                    regex: /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/, 
                                },
                                name: {
                                    required: true,
                                    regex:/^[a-zA-Z ]*$/,
                                },
                                password: {
                                    required: true,
                                    minlength:  6,
                                },
                                cpassword: {
                                    required: true,
                                    minlength:  6,
                                    equalTo: "#password"
                                },
                                role_id: "required",
                                client_id: {
                                    required: function (el) {
                                        return $(el).closest('form').find('#role_id').val()=='4';
                                    }
                                },
                                supplier_id:  {
                                    required: function (el) {
                                        return $(el).closest('form').find('#role_id').val()=='3';
                                    }
                                },
                                clients_id:  {
                                    required: function (el) {
                                        return $(el).closest('form').find('#role_id').val()=='5';
                                    }
                                }
                            },
                            messages: {
                                username: {
                                    required:"Please enter a valid email address",
                                    email:"Please enter a valid email address",
                                    regex: 'Please enter a valid email without spacial chars, ie, Example@gmail.com'
                                },
                                name: {
                                    required: "Please enter a name.",
                                    regex: "Special character and Number not allowed"
                                },
                                password: {
                                    required: "Please enter a password.",
                                    minlength: "Please enter a password of 6 char",
                                },
                                cpassword: {
                                    required: "Please enter a password.",
                                    minlength: "Please enter a password of 6 char",
                                    equalTo:'Please enter match password',
                                },
                                role_id: "Please select a role",
                                client_id: {
                                    required: "Please select user Client"
                                },
                                supplier_id: {
                                    required: "Please select user Supplier"
                                },
                                clients_id: {
                                    required: "Please select user Clients"
                                }
                            },
                            onfocusout: function(element) {
                                this.element(element);
                            },
                            submitHandler: function(form) {
                                jQuery.ajax({
                                    url: form.action,
                                    type: form.method,
                                    data: $(form).serialize(),
                                    success: function(response) {
                                        //alert(response);
                                        if(response==1){
                                            jQuery('#deleteupdate').html('<p>Portal User has been updated</p>');                       
                                            $('html').animate({ scrollTop: 0 }, 300);
                                            window.location.href = "{{ action('PortaluserController@index') }}";
                                        } else if (response==2) {
                                            jQuery('#massage').text('User email already exist');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                        } else {
                                            jQuery('#massage').text('Something Wrong! User not Updated');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                        }
                                    }
                                });
                            }
                        });
                    });
                    /* End validation form and store data using ajax */
                    /* Start active/inactive */
                    jQuery(document).on('click','#active',function(){
                        if(jQuery(this).is(':checked')==true){
                            jQuery(this).attr('checked','checked');
                            jQuery(this).val('1');
                        } else {
                            jQuery(this).removeAttr('checked');
                            jQuery(this).val('0');
                        }
                    });
                    /* End active/inactive */
                    jQuery(document).on('change','#role_id',function(){
                        if(jQuery(this).val()=='3'){
                            jQuery('.supplierid').show();
                            jQuery('.clientid').hide();
                            jQuery('.clientsid').hide();
                            jQuery('#client_id option:selected').removeAttr('selected');
                            jQuery('#client_id option:eq(0)').attr('selected','selected');
                            jQuery('#clients_id option:selected').removeAttr('selected');
                            jQuery('#clients_id option:eq(0)').attr('selected','selected');
                        } else if(jQuery(this).val()=='4'){
                            jQuery('.supplierid').hide();
                            jQuery('.clientid').show();
                            jQuery('.clientsid').hide();
                            jQuery('#supplier_id option:selected').removeAttr('selected');
                            jQuery('#supplier_id option:eq(0)').attr('selected','selected');
                            jQuery('#clients_id option:selected').removeAttr('selected');
                            jQuery('#clients_id option:eq(0)').attr('selected','selected');
                        } else if(jQuery(this).val()=='5'){
                            jQuery('.supplierid').hide();
                            jQuery('.clientid').hide();
                            jQuery('.clientsid').show();
                            jQuery('#supplier_id option:selected').removeAttr('selected');
                            jQuery('#supplier_id option:eq(0)').attr('selected','selected');
                            jQuery('#client_id option:selected').removeAttr('selected');
                            jQuery('#client_id option:eq(0)').attr('selected','selected');
                        } else {
                            jQuery('.supplierid').hide();
                            jQuery('.clientid').hide();
                            jQuery('.clientsid').hide();
                            jQuery('#supplier_id option:selected').removeAttr('selected');
                            jQuery('#supplier_id option:eq(0)').attr('selected','selected');
                            jQuery('#client_id option:selected').removeAttr('selected');
                            jQuery('#client_id option:eq(0)').attr('selected','selected');
                            jQuery('#clients_id option:selected').removeAttr('selected');
                            jQuery('#clients_id option:eq(0)').attr('selected','selected');
                        }
                    });
                    /* Start active/inactive delete button */
                    jQuery(document).on('click','#confirm',function(){
                        if(jQuery(this).is(':checked')==true){
                            jQuery('#delete').removeAttr('disabled');
                        } else {
                            jQuery('#delete').attr('disabled','disabled');
                        }
                    });
                    /* End active/inactive delete button */
                    /* End delete id */
                    function destroy(id) {
                        if (confirm('Are you sure you want to delete this?')) {
                            jQuery.ajax({
                                type: 'POST',
                                url: "{{ url('/portaluser') }}/"+id,
                                data: { _token:'{{ csrf_token() }}', _method:'DELETE'} , 
                                success: function(result){
                                    if(result==1){
                                        jQuery('#deleteupdate').html('<p>Portal User has been deleted</p>');
                                        jQuery('html').animate({ scrollTop: 0 }, 300);
                                        window.location.href = "{{ action('PortaluserController@index') }}";
                                    }
                                }
                            });
                        }
                    }
                    /* End delete id */
                </script>
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection