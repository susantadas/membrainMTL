{{--
    Input Parameters:
        @Type
        @Country Id
        @Url
        @Credentials Detaila
        @Status
        Author: Latitude Global Partners
--}}

@extends('layouts.app')
@section('title','Create/Multiple Api')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>Create Multiple API</h3>
            <div class="well1 white">
                <div id="massage"></div>
                <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" name="multipleapiCreate" method="post" action="{{ action('MultipleapiController@store') }}">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Type</label>
                                <select class="form-control1 ng-invalid ng-invalid-required" name="type" id="type">
                                    <option value="">Please Select Type</option>
                                    <option value="emailApi">E-mail API</option>
                                    <option value="phoneApi">Phone API</option>
                                    <!-- <option value="rapportAPICall">Rapport API call</option> -->
                                    <option value="genderApi">Gender API</option>
                                    <option value="birthdateApi">Birth date API</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Country</label>
                                <select class="form-control1 ng-invalid ng-invalid-required" name="country_code" id="country_code">
                                    <option value="">Please Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->code}}">{{$country->name}}</option>
                                    @endforeach;
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">API Url</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="apiurl" name="apiurl" />
                                <label>example: http://abc.com/ or https://abc.com/ Or https://www.abc.com/</label>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Credentials Details</label>
                                <textarea name="credentials_detaila" id="credentials_detaila" class="form-control1 ng-invalid ng-invalid-required ng-touched"></textarea>
                                <label>example: {"apiToken":"s$56hfkdjkd","apiPassword":"@34567sjdhs$"}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="checkboxb checkbox-primary">
                                    <input id="status" name="status" type="checkbox" value="1" checked="checked">
                                    <label for="status">
                                        Active
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Save New API</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
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
                        jQuery("form[name='multipleapiCreate']").validate({
                            rules: {
                                type: {
                                    required: true,
                                },
                                country_code: {
                                    required: true,
                                },
                                apiurl: {
                                    required: true,
                                    url: true,
                                },
                                credentials_detaila:  {
                                    required: true,
                                },
                            },
                            messages: {
                                type: {
                                    required: "Please enter type"
                                },
                                country_code: {
                                    required:"Please select country code."
                                },
                                apiurl: {
                                    required: "Please enter api url.",
                                    url: "Invalid api Url"
                                },
                                credentials_detaila:  {
                                    required: "Please enter credentials detaila"
                                },
                                error_allowance: " Please select a number."
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
                                        if(response==1){
                                            window.location.href = "{{ action('MultipleapiController@index') }}";
                                        } else {
                                            jQuery('#massage').text('Something Wrong! Api not created');
                                            jQuery('html').animate({ scrollTop: 0 }, 300);
                                        }
                                    }
                                });
                            }
                        });
                    });
                    /* Start validation form and store data using ajax */
                    /* Start active/inactive */
                    jQuery(document).on('click','#status',function(){
                        if(jQuery(this).is(':checked')==true){
                            jQuery(this).attr('checked','checked');
                            jQuery(this).val('1');
                        } else {
                            jQuery(this).removeAttr('checked');
                            jQuery(this).val('0');
                        }
                    });
                    /* End active/inactive */
                    /* Start url generated */
                    /*jQuery(document).on('change','#country_code',function(){
                        var pastUrl = "{{ url('/api/v1/{country}/multisupplierapi') }}";
                        var newUrl = pastUrl.replace("{country}",$(this).val());
                        jQuery('#apiurl').val(newUrl);
                    });*/
                    /* End url generated */
                    /* Start reset */
                    jQuery("button[type=reset]").click(function(){
                        jQuery("#status").attr("checked",false);
                        jQuery('#status').val('0');
                    });
                    /* End reset */
                </script>
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection