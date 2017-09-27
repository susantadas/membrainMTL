@extends('layouts.app')
@section('title','Edit/Campaign')
@section('content')  
  <?php $age_array = array();
  $age_array = explode('-', $campaign[0]->criteria_age);
  if(sizeof($age_array)==1){
    $age_array[0]=0;
    $age_array[1]=0;
  }

  $sp_array = array();
  $sp_array = json_decode( $campaign[0]->server_parameters ); 

  $pm_array = array();
  $pm_array = json_decode( $campaign[0]->parameter_mapping );
  $pr_array = array();
  $pr_array = json_decode( $campaign[0]->parameter_required ); ?>
  @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
    <div class="xs">
      <h3>Edit Campaign</h3>
      <div class="well1 white">
        <div class="control-group error" style="color:red">
          <ul id="summary"></ul>
        </div>
        <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" method="post" action="{{ action('CampaignController@update', $campaign[0]->id ) }}" name="campaignUpdate">
          {{ csrf_field() }}
          {{method_field('PUT')}}
          <input type="hidden" name="method_type" id="method_type" value="{{ $campaign[0]->method }}"/>
          <div class="form-group">
            <div class="col-sm-12">
              <label class="control-label">Name</label>
              <input type="text" class="form-control1 changeallfieldforce" id="name" name="name" value="{{ $campaign[0]->name}}" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <label class="control-label">Public ID</label>
              <input type="text" class="form-control1" id="public_id" name="public_id" value="{{ $campaign[0]->public_id}}" readonly="readonly" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <label class="control-label">Client Name</label>
              <input type="text" class="form-control1" id="client_name" value="{{ $campaign[0]->client_name}}" readonly="readonly" />
              <input type="hidden" name="client_name" value="{{ $campaign[0]->client_id}}">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <label class="control-label">Method</label>
              <input type="text" class="form-control1" id="cpmethod" name="cpmethod" value="{{ $campaign[0]->method}}" readonly="readonly" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <div class="checkboxb checkbox-primary">
                <input id="age_criteria" name="age_criteria" class="age_criteria changeallfieldforce"  type="checkbox" @if($campaign[0]->criteria_age!='') checked=checked @endif>
                <label for="age_criteria">
                  Age Criteria
                </label>
              </div>
            </div>
          </div>
          <div id="age_range" @if(isset($campaign[0]->criteria_age) && $campaign[0]->criteria_age=='') style=display:none @endif >
            <h4>Age Range</h4>
            <div class="form-group">
              <div class="col-sm-6">
                <select class="form-control1 unique-areRange changeallfieldforce" name="start_age" id="start_age">
                  <option value="">---Please Select Min Age---</option>
                  @for($i=1;$i<100;$i++)
                    <option value="{{$i}}" @if(isset($age_array[0]) && $age_array[0] == $i) selected=selected @endif> {{$i}} </option>;
                  @endfor
                </select>
              </div>
              <div class="col-sm-6">
                <select class="form-control1 unique-areRange changeallfieldforce" name="end_age" id="end_age">
                  <option value="">---Please Select Max Age---</option>
                  @for($i=2;$i<101;$i++)
                    <option value="{{$i}}" @if(isset($age_array[1]) && $age_array[1] == $i) selected=selected @endif> {{$i}} </option>;
                  @endfor
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <div class="checkboxb checkbox-primary">
                <input id="state_criteria" name="state_criteria" class="state_criteria changeallfieldforce"  type="checkbox" @if($campaign[0]->criteria_state!='') checked=checked @endif>
                <label for="state_criteria">
                  State Criteria
                </label>
              </div>
            </div>
          </div>
          <div id="state_list_hidden" @if(isset($campaign[0]->criteria_state) && $campaign[0]->criteria_state=='') style=display:none @endif>
            <div class="form-group">
              <div class="col-sm-12">
                <input type="text" name="state_list" class="state_list form-control1 changeallfieldforce" id="state_list" value="{{$campaign[0]->criteria_state}}" >
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <div class="checkboxb checkbox-primary">
                <input id="postcode_criteria" name="postcode_criteria" class="postcode_criteria changeallfieldforce"  type="checkbox" @if($campaign[0]->criteria_postcode!='') checked=checked @endif>
                <label for="postcode_criteria">
                  Postcode Criteria
                </label>
              </div>
            </div>
          </div>
          <div id="postcode_list_hidden" @if(isset($campaign[0]->criteria_postcode) && $campaign[0]->criteria_postcode=='') style=display:none @endif>
            <div class="form-group">
              <div class="col-sm-12">
                <label for="postcode_list">Postcode List:</label>
                <textarea name="postcode_list" class="postcode_list form-control1 changeallfieldforce" rows="5" id="postcode_list">{{$campaign[0]->criteria_postcode}}</textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <label>DNCR Required</label><br/>
              <div class="radio radio-info radio-inline">
                <input type="radio" id="dncr_required1" value="0" class="dncr_required changeallfieldforce" name="dncr_required" {{ $campaign[0]->dncr_required == 0 ? "checked":"" }}>
                <label for="dncr_required1">Yes </label>
              </div>
              <div class="radio radio-inline radio-info">
                <input type="radio" id="dncr_required2" value="1" class="dncr_required changeallfieldforce" name="dncr_required" {{ $campaign[0]->dncr_required == 1 ? "checked":"" }}>
                <label for="dncr_required2"> No </label>
              </div>
            </div>
            <div class="col-sm-6">
              <label class="control-label">&nbsp;</label>
              <div class="checkboxb checkbox-primary">
                <input id="active" name="active" class="changeallfieldforce" type="checkbox" value="{{$campaign[0]->active}}" {{$campaign[0]->active==1 ?' checked=checked':''}}>
                <label for="active">
                  Active
                </label>
              </div>
            </div>
          </div>
          <div class="API" @if($campaign[0]->method != 'API') style=display:none @endif >
            <div class="form-group">
              <div class="col-sm-6">
                <label for="type">Type:</label>
                <select class="form-control1 changeallfieldforce" id="type" name="type">
                  <option value="">--Please select type--</option>
                  <option value="GET" {{ $sp_array->type == "GET"? "selected":"" }} >GET</option>
                  <option value="POST" {{ $sp_array->type == "POST"? "selected":"" }} >POST</option>
                  <option value="JSON POST" {{ $sp_array->type == "JSON POST"? "selected":"" }} >JSON POST</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="control-label">Endpoint</label>
                <input type="text" class="form-control1 changeallfieldforce" id="endpoint" name="endpoint" class="endpoint" value="{{ isset($sp_array->endpoint) ? $sp_array->endpoint:'' }}" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <label class="control-label">User</label>
                <input type="text" class="form-control1 changeallfieldforce" id="user" name="user" class="user" value="{{ isset($sp_array->user) ? $sp_array->user:'' }}" />
              </div>    
              <div class="col-sm-6">
                <label class="control-label">Password</label>
                <input type="text" class="form-control1 changeallfieldforce" id="password" name="password" class="password" value="{{ isset($sp_array->password)? $sp_array->password:'' }}" />
              </div>
            </div>    
            <div class="form-group">
              <div class="col-sm-6">
                <label class="control-label">Port</label>
                <input type="text" class="form-control1 changeallfieldforce" id="port" name="port" class="port" value="{{ isset($sp_array->port) ? $sp_array->port:'' }}" />
              </div>
            </div>     
            <h4>Standard Parameter Mapping Fields</h4>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="email">email:</label>
                  <input type="text" class="form-control1 changeallfieldforce api-valid-group unique-api" id="email" name="email" value="{{ isset($pm_array->email) ? $pm_array->email:'' }}">
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apiemail" class="apiRequired changeallfieldforce" name="api[email]" type="checkbox" value="{{ isset($pr_array->api->email) ? $pr_array->api->email:'0' }}" @if(isset($pr_array->api->email) && $pr_array->api->email=='1') checked=checked @endif>
                    <label for="apiemail">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="phone">phone:</label>
                  <input type="text" class="form-control1 changeallfieldforce phone api-valid-group unique-api" id="phone" name="phone" value="{{ isset($pm_array->phone) ? $pm_array->phone:'' }}">
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apiphone" class="apiRequired changeallfieldforce" name="api[phone]" type="checkbox" value="{{ isset($pr_array->api->phone) ? $pr_array->api->phone:'0' }}" @if(isset($pr_array->api->phone) && $pr_array->api->phone=='1') checked=checked @endif>
                    <label for="apiphone">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="title">title:</label>
                  <input type="text" class="form-control1 changeallfieldforce title unique-api" id="title" name="title" value="{{ isset($pm_array->title) ? $pm_array->title:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apititle" class="apiRequired changeallfieldforce" name="api[title]" type="checkbox" value="{{ isset($pr_array->api->title) ? $pr_array->api->title:'0' }}" @if(isset($pr_array->api->title) && $pr_array->api->title=='1') checked=checked @endif>
                    <label for="apititle">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="firstName">firstName:</label>
                  <input type="text" class="form-control1 changeallfieldforce firstName unique-api" id="firstName" name="firstName" value="{{ isset($pm_array->firstName) ? $pm_array->firstName:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apifirstName" class="apiRequired changeallfieldforce" name="api[firstName]" type="checkbox" value="{{ isset($pr_array->api->firstName) ? $pr_array->api->firstName:'0' }}" @if(isset($pr_array->api->firstName) && $pr_array->api->firstName=='1') checked=checked @endif>
                    <label for="apifirstName">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="lastName">lastName:</label>
                  <input type="text" class="form-control1 changeallfieldforce lastName unique-api" id="lastName" name="lastName" value="{{ isset($pm_array->lastName) ? $pm_array->lastName:'' }}">
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apilastName" class="apiRequired changeallfieldforce" name="api[lastName]" type="checkbox" value="{{ isset($pr_array->api->lastName) ? $pr_array->api->lastName:'0' }}" @if(isset($pr_array->api->lastName) && $pr_array->api->lastName=='1') checked=checked @endif>
                    <label for="apilastName">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="birthdate">birthdate:</label>
                  <input type="text" class="form-control1 changeallfieldforce birthdate unique-api" id="birthdate" name="birthdate" value="{{ isset($pm_array->birthdate) ? $pm_array->birthdate:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apibirthdate" class="apiRequired changeallfieldforce" name="api[birthdate]" type="checkbox" value="{{ isset($pr_array->api->birthdate) ? $pr_array->api->birthdate:'0' }}" @if(isset($pr_array->api->birthdate) && $pr_array->api->birthdate=='1') checked=checked @endif>
                    <label for="apibirthdate">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="age">age:</label>
                  <input type="text" class="form-control1 changeallfieldforce age unique-api" id="age" name="age" value="{{ isset($pm_array->age) ? $pm_array->age:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apiage" class="apiRequired changeallfieldforce" name="api[age]" type="checkbox" value="{{ isset($pr_array->api->age) ? $pr_array->api->age:'0' }}" @if(isset($pr_array->api->age) && $pr_array->api->age=='1') checked=checked @endif>
                    <label for="apiage">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="ageRange">ageRange:</label>
                  <input type="text" class="form-control1 changeallfieldforce ageRange unique-api" id="ageRange" name="ageRange" value="{{ isset($pm_array->ageRange) ? $pm_array->ageRange:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apiageRange" class="apiRequired changeallfieldforce" name="api[ageRange]" type="checkbox" value="{{ isset($pr_array->api->ageRange) ? $pr_array->api->ageRange:'0' }}" @if(isset($pr_array->api->ageRange) && $pr_array->api->ageRange=='1') checked=checked @endif>
                    <label for="apiageRange">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="gender">gender:</label>
                  <input type="text" class="form-control1 changeallfieldforce gender unique-api" id="gender" name="gender" value="{{ isset($pm_array->gender) ? $pm_array->gender:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apigender" class="apiRequired changeallfieldforce" name="api[gender]" type="checkbox" value="{{ isset($pr_array->api->gender) ? $pr_array->api->gender:'0' }}" @if(isset($pr_array->api->gender) && $pr_array->api->gender=='1') checked=checked @endif>
                    <label for="apigender">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="address1">address1:</label>
                  <input type="text" class="form-control1 changeallfieldforce address1 unique-api" id="address1" name="address1" value="{{ isset($pm_array->address1) ? $pm_array->address1:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apiaddress1" class="apiRequired changeallfieldforce" name="api[address1]" type="checkbox" value="{{ isset($pr_array->api->address1) ? $pr_array->api->address1:'0' }}" @if(isset($pr_array->api->address1) && $pr_array->api->address1=='1') checked=checked @endif>
                    <label for="apiaddress1">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="address2">address2:</label>
                  <input type="text" class="form-control1 changeallfieldforce address2 unique-api" id="address2" name="address2" value="{{ isset($pm_array->address2) ? $pm_array->address2:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apiaddress2" class="apiRequired changeallfieldforce" name="api[address2]" type="checkbox" value="{{ isset($pr_array->api->address2) ? $pr_array->api->address2:'0' }}" @if(isset($pr_array->api->address2) && $pr_array->api->address2=='1') checked=checked @endif>
                    <label for="apiaddress2">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="city">city:</label>
                  <input type="text" class="form-control1 changeallfieldforce city unique-api" id="city" name="city" value="{{ isset($pm_array->city) ? $pm_array->city:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apicity" class="apiRequired changeallfieldforce" name="api[city]" type="checkbox" value="{{ isset($pr_array->api->city) ? $pr_array->api->city:'0' }}" @if(isset($pr_array->api->city) && $pr_array->api->city=='1') checked=checked @endif>
                    <label for="apicity">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="state">state:</label>
                  <input type="text" class="form-control1 changeallfieldforce state unique-api" id="state" name="state" value="{{ isset($pm_array->state) ? $pm_array->state:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apistate" class="apiRequired changeallfieldforce" name="api[state]" type="checkbox" value="{{ isset($pr_array->api->state) ? $pr_array->api->state:'0' }}" @if(isset($pr_array->api->state) && $pr_array->api->state=='1') checked=checked @endif>
                    <label for="apistate">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="postcode">postcode:</label>
                  <input type="text" class="form-control1 changeallfieldforce postcode unique-api" id="postcode" name="postcode" value="{{ isset($pm_array->postcode) ? $pm_array->postcode:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apipostcode" class="apiRequired changeallfieldforce" name="api[postcode]" type="checkbox" value="{{ isset($pr_array->api->postcode) ? $pr_array->api->postcode:'0' }}" @if(isset($pr_array->api->postcode) && $pr_array->api->postcode=='1') checked=checked @endif>
                    <label for="apipostcode">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="countryCode">countryCode:</label>
                  <input type="text" class="form-control1 changeallfieldforce countryCode unique-api" id="countryCode" name="countryCode" value="{{ isset($pm_array->countryCode) ? $pm_array->countryCode:'' }}" >
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-api checkboxb checkbox-primary">
                    <input id="apicountryCode" class="apiRequired changeallfieldforce" name="api[countryCode]" type="checkbox" value="{{ isset($pr_array->api->countryCode) ? $pr_array->api->countryCode:'0' }}" @if(isset($pr_array->api->countryCode) && $pr_array->api->countryCode=='1') checked=checked @endif>
                    <label for="apicountryCode">Required</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="CSV" @if($campaign[0]->method != 'CSV') style=display:none @endif >
            <div class="form-group">
              <div class="col-sm-6">
                <label for="server_type">Server Type:</label>
                <select class="form-control1 changeallfieldforce" id="server_type" name="server_type">
                  <option value="">--Please select server type--</option>
                  <option value="ftp" {{ $sp_array->type == "ftp"? "selected":"" }} >FTP</option>
                  <option value="sftp" {{ $sp_array->type == "sftp"? "selected":"" }}>SFTP</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="control-label">Server</label>
                <input type="text" class="form-control1 changeallfieldforce" id="csv_server" name="csv_server" class="csv_server" value="{{ isset($sp_array->server) ? $sp_array->server : '' }}" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <label class="control-label">Directory</label>
                <input type="text" class="form-control1 changeallfieldforce" id="directory" name="directory" class="directory" value="{{ isset($sp_array->directory) ? $sp_array->directory : '' }}" />
              </div>   
              <div class="col-sm-6">
                <label class="control-label">User</label>
                <input type="text" class="form-control1 changeallfieldforce" id="csv_user" name="csv_user" class="csv_user" value="{{ isset($sp_array->user) ? $sp_array->user : '' }}" />
              </div>
            </div> 
            <div class="form-group">
              <div class="col-sm-6">
                <label class="control-label">Password</label>
                <input type="text" class="form-control1 changeallfieldforce" id="csv_password" name="csv_password" class="csv_password" value="{{ isset($sp_array->password) ? $sp_array->password : '' }}" />
              </div>
            </div>
            <h4>Column-Mapping Fields</h4>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_email">email:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_email csv_unique_select" name="csv_email" id="csv_email">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->email) && $pm_array->email == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_email_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvemail" class="csvRequired changeallfieldforce" name="csv[email]" type="checkbox" value="{{ isset($pr_array->csv->email) ? $pr_array->csv->email:'0' }}" @if(isset($pr_array->csv->email) && $pr_array->csv->email=='1') checked=checked @endif>
                    <label for="csvemail">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_phone">phone:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_phone csv_unique_select" name="csv_phone" id="csv_phone">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->phone) && $pm_array->phone == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_phone_error" style="display:none;"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvphone" class="csvRequired changeallfieldforce" name="csv[phone]" type="checkbox" value="{{ isset($pr_array->csv->phone) ? $pr_array->csv->phone:'0' }}" @if(isset($pr_array->csv->phone) && $pr_array->csv->phone=='1') checked=checked @endif>
                    <label for="csvphone">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_title">title:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_title csv_unique_select" name="csv_title" id="csv_title">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->title) && $pm_array->title == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_title_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvtitle" class="csvRequired changeallfieldforce" name="csv[title]" type="checkbox" value="{{ isset($pr_array->csv->title) ? $pr_array->csv->title:'0' }}" @if(isset($pr_array->csv->title) && $pr_array->csv->title=='1') checked=checked @endif>
                    <label for="csvtitle">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_firstName">firstName:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_firstName csv_unique_select" name="csv_firstName" id="csv_firstName">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->firstName) && $pm_array->firstName == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_firstName_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvfirstName" class="csvRequired changeallfieldforce" name="csv[firstName]" type="checkbox" value="{{ isset($pr_array->csv->firstName) ? $pr_array->csv->firstName:'0' }}" @if(isset($pr_array->csv->firstName) && $pr_array->csv->firstName=='1') checked=checked @endif>
                    <label for="csvfirstName">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_lastName">lastName:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_lastName csv_unique_select" name="csv_lastName" id="csv_lastName">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->lastName) && $pm_array->lastName == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_lastName_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvlastName" class="csvRequired changeallfieldforce" name="csv[lastName]" type="checkbox" value="{{ isset($pr_array->csv->lastName) ? $pr_array->csv->lastName:'0' }}" @if(isset($pr_array->csv->lastName) && $pr_array->csv->lastName=='1') checked=checked @endif>
                    <label for="csvlastName">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_birthdate">birthdate:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_birthdate csv_unique_select" name="csv_birthdate" id="csv_birthdate">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->birthdate) && $pm_array->birthdate == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_birthdate_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvbirthdate" class="csvRequired changeallfieldforce" name="csv[birthdate]" type="checkbox" value="{{ isset($pr_array->csv->birthdate) ? $pr_array->csv->birthdate:'0' }}" @if(isset($pr_array->csv->birthdate) && $pr_array->csv->birthdate=='1') checked=checked @endif>
                    <label for="csvbirthdate">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_age">age:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_age csv_unique_select" name="csv_age" id="csv_age">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->age) && $pm_array->age == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_age_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvage" class="csvRequired changeallfieldforce" name="csv[age]" type="checkbox" value="{{ isset($pr_array->csv->age) ? $pr_array->csv->age:'0' }}" @if(isset($pr_array->csv->age) && $pr_array->csv->age=='1') checked=checked @endif>
                    <label for="csvage">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_ageRange">ageRange:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_ageRange csv_unique_select" name="csv_ageRange" id="csv_ageRange">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->ageRange) && $pm_array->ageRange == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_ageRange_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvageRange" class="csvRequired changeallfieldforce" name="csv[ageRange]" type="checkbox" value="{{ isset($pr_array->csv->ageRange) ? $pr_array->csv->ageRange:'0' }}" @if(isset($pr_array->csv->ageRange) && $pr_array->csv->ageRange=='1') checked=checked @endif>
                    <label for="csvageRange">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_gender">gender:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_gender csv_unique_select" name="csv_gender" id="csv_gender">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->gender) && $pm_array->gender == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_gender_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvgender" class="csvRequired changeallfieldforce" name="csv[gender]" type="checkbox" value="{{ isset($pr_array->csv->gender) ? $pr_array->csv->gender:'0' }}" @if(isset($pr_array->csv->gender) && $pr_array->csv->gender=='1') checked=checked @endif>
                    <label for="csvgender">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_address1">address1:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_address1 csv_unique_select" name="csv_address1" id="csv_address1">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->address1) && $pm_array->address1 == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_address1_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvaddress1" class="csvRequired changeallfieldforce" name="csv[address1]" type="checkbox" value="{{ isset($pr_array->csv->address1) ? $pr_array->csv->address1:'0' }}" @if(isset($pr_array->csv->address1) && $pr_array->csv->address1=='1') checked=checked @endif>
                    <label for="csvaddress1">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_address2">address2:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_address2 csv_unique_select" name="csv_address2" id="csv_address2">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->address2) && $pm_array->address2 == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_address2_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvaddress2" class="csvRequired changeallfieldforce" name="csv[address2]" type="checkbox" value="{{ isset($pr_array->csv->address2) ? $pr_array->csv->address2:'0' }}" @if(isset($pr_array->csv->address2) && $pr_array->csv->address2=='1') checked=checked @endif>
                    <label for="csvaddress2">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_city">city:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_city csv_unique_select" name="csv_city" id="csv_city">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->city) && $pm_array->city == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_city_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvcity" class="csvRequired changeallfieldforce" name="csv[city]" type="checkbox" value="{{ isset($pr_array->csv->city) ? $pr_array->csv->city:'0' }}" @if(isset($pr_array->csv->city) && $pr_array->csv->city=='1') checked=checked @endif>
                    <label for="csvcity">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_state">state:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_state csv_unique_select" name="csv_state" id="csv_state">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->state) && $pm_array->state == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_state_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvstate" class="csvRequired changeallfieldforce" name="csv[state]" type="checkbox" value="{{ isset($pr_array->csv->state) ? $pr_array->csv->state:'0' }}" @if(isset($pr_array->csv->state) && $pr_array->csv->state=='1') checked=checked @endif>
                    <label for="csvstate">Required</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_postcode">postcode:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_postcode csv_unique_select" name="csv_postcode" id="csv_postcode">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->postcode) && $pm_array->postcode == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                 <span class="error csv_common_error" id="csv_postcode_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvpostcode" class="csvRequired changeallfieldforce" name="csv[postcode]" type="checkbox" value="{{ isset($pr_array->csv->postcode) ? $pr_array->csv->postcode:'0' }}" @if(isset($pr_array->csv->postcode) && $pr_array->csv->postcode=='1') checked=checked @endif>
                    <label for="csvpostcode">Required</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="col-sm-8">
                  <label for="csv_countryCode">countryCode:</label>
                  <select class="form-control1 changeallfieldforce ng-invalid ng-invalid-required csv_countryCode csv_unique_select" name="csv_countryCode" id="csv_countryCode">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}" @if(isset($pm_array->countryCode) && $pm_array->countryCode == $i) selected=selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error csv_common_error" id="csv_countryCode_error" style="display:none"></span>
                </div>
                <div class="col-sm-4">
                  <label class="control-label">&nbsp;</label>
                  <div class="checkboxb-csv checkboxb checkbox-primary">
                    <input id="csvcountryCode" class="csvRequired changeallfieldforce" name="csv[countryCode]" type="checkbox" value="{{ isset($pr_array->csv->countryCode) ? $pr_array->csv->countryCode:'0' }}" @if(isset($pr_array->csv->countryCode) && $pr_array->csv->countryCode=='1') checked=checked @endif>
                    <label for="csvcountryCode">Required</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <button type="submit" id="update" disabled="disabled" class="btn btn-primary">Save Changes</button>
            </div>
          </div>
        </form>
      </div>
       <script type="text/javascript">
          jQuery(function() {
            $.validator.addMethod("regex",function(value, element, regexp) {
              if (regexp.constructor != RegExp)
                  regexp = new RegExp(regexp);
              else if (regexp.global)
                  regexp.lastIndex = 0;
              return this.optional(element) || regexp.test(value);
            },"Please enter a valid input.");

            $.validator.addMethod("notEqualTo", function(value, element, param) {
                  var notEqual = true;
                  value = $.trim(value);
                  for (i = 0; i < param.length; i++) {
                      if (value == $.trim($(param[i]).val())) { notEqual = false; }
                  }
                  return this.optional(element) || notEqual;
            },"Please enter a diferent value.");

            $.validator.addMethod("geterthenendage", function(value, element, param) {
                if(parseInt(value) > parseInt($(param).val())){
                  return  this.optional(element) || false;
                }else{
                  return  this.optional(element) || true;
                }
            },"Starting age must be geter then ending age.");

            $.validator.addMethod("lessthenage", function(value, element, param) {
                if(parseInt(value) < parseInt($(param).val())){
                  return  this.optional(element) || false;
                }else{
                  return  this.optional(element) || true;
                }
            },"Ending age must be less then starting age.");

            $.validator.addMethod("notEqualToGroup", function (value, element, options) {
                // get all the elements passed here with the same class
                var elems = $(element).parents('form').find(options[0]);
                // the value of the current element
                var valueToCompare = value;
                // count
                var matchesFound = 0;
                // loop each element and compare its value with the current value
                // and increase the count every time we find one
                jQuery.each(elems, function () {
                    thisVal = $(this).val();
                    if(thisVal!=''){
                      if (thisVal == valueToCompare) {
                          matchesFound++;
                      }
                    }
                });
                // count should be either 0 or 1 max
                if (this.optional(element) || matchesFound <= 1) {
                    return true;
                } else {
                }
            }, $.validator.format("Please enter a Unique Value."));

            jQuery("form[name='campaignUpdate']").validate({
              rules: {
                name: {
                  required: true,
                  regex: /^[a-zA-Z0-9 ]*$/,
                  minlength: 2,
                  maxlength: 255,
                },
                client_name:{
                  required: true,
                },
                cpmethod: "required",
                dncr_required: "required",
                start_age:{
                  required: function(el){
                    return $(el).closest('form').find('#age_criteria').is(':checked') == true;
                  },
                  geterthenendage: '#end_age',
                  notEqualToGroup: ['.unique-areRange']
                },
                end_age:{
                  required: function(el){
                    return $(el).closest('form').find('#age_criteria').is(':checked') == true;
                  },
                  lessthenage: '#start_age',
                  notEqualToGroup: ['.unique-areRange']
                },
                state_list:{
                  required: function(el){
                    return $(el).closest('form').find('#state_criteria').is(':checked') == true;
                  }
                },
                postcode_list:{
                  required:function(el){
                    return $(el).closest('form').find('#postcode_criteria').is(':checked') == true;
                  },
                  regex: /^[a-zA-Z0-9,]*$/,
                },
                type:{
                  required:"#type:visible"
                },
                endpoint:{ 
                  required:"#endpoint:visible",
                  url: true, 
                },
                email:{ 
                  required:function(el){
                    return $(el).closest('form').find('#apiemail').is(':checked') == true;
                  },
                  //require_from_group: [1, ".api-valid-group"],
                  notEqualToGroup: ['.unique-api'] 
                },
                phone:{                   
                  required:function(el){
                    return $(el).closest('form').find('#apiphone').is(':checked') == true;
                  },
                  //require_from_group: [1, ".api-valid-group"],
                  notEqualToGroup: ['.unique-api'] 
                },  
                title:{
                  required:function(el){
                    return $(el).closest('form').find('#apititle').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                firstName:{
                  required:function(el){
                    return $(el).closest('form').find('#apifirstName').is(':checked') == true;
                  },
                  notEqualTo: ['#lastName'],
                  notEqualToGroup: ['.unique-api']
                },
                lastName:{
                  required:function(el){
                    return $(el).closest('form').find('#apilastName').is(':checked') == true;
                  },
                  notEqualTo: ['#firstName'],
                  notEqualToGroup: ['.unique-api']
                },
                birthdate:{
                  required:function(el){
                    return $(el).closest('form').find('#apibirthdate').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                age:{                  
                  required:function(el){
                    return $(el).closest('form').find('#apiage').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                ageRange:{
                  required:function(el){
                    return $(el).closest('form').find('#apiageRange').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                gender:{
                  required:function(el){
                    return $(el).closest('form').find('#apigender').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                address1:{                  
                  required:function(el){
                    return $(el).closest('form').find('#apiaddress1').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                address2:{
                  required:function(el){
                    return $(el).closest('form').find('#apiaddress2').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                city:{                  
                  required:function(el){
                    return $(el).closest('form').find('#apicity').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                state:{
                  required:function(el){
                    return $(el).closest('form').find('#apistate').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                postcode:{                  
                  required:function(el){
                    return $(el).closest('form').find('#apipostcode').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                countryCode:{
                  required:function(el){
                    return $(el).closest('form').find('#apicountryCode').is(':checked') == true;
                  },
                  notEqualToGroup: ['.unique-api']
                },
                server_type:{
                  required:"#server_type:visible"
                },
                csv_server:{
                  required:"#csv_server:visible",
                  regex: /^(([a-zA-Z0-9]{1,})+\.+([a-zA-Z0-9]{1,}))*$/
                },
                directory:{
                  required:"#directory:visible"
                },
                csv_user:{
                  required:"#csv_user:visible"
                },
                csv_password:{
                  required:"#csv_password:visible"
                },
                csv_email:{
                  required:function(el){
                    return $(el).closest('form').find('#csvemail').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_phone:{
                  required:function(el){
                    return $(el).closest('form').find('#csvphone').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_title:{
                  required:function(el){
                    return $(el).closest('form').find('#csvtitle').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_firstName:{
                  required:function(el){
                    return $(el).closest('form').find('#csvfirstName').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_lastName:{
                  required:function(el){
                    return $(el).closest('form').find('#csvlastName').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_birthdate:{
                  required:function(el){
                    return $(el).closest('form').find('#csvbirthdate').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_age:{
                  required:function(el){
                    return $(el).closest('form').find('#csvage').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_ageRange:{
                  required:function(el){
                    return $(el).closest('form').find('#csvageRange').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_gender:{
                  required:function(el){
                    return $(el).closest('form').find('#csvgender').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_address1:{
                  required:function(el){
                    return $(el).closest('form').find('#csvaddress1').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_address2:{
                  required:function(el){
                    return $(el).closest('form').find('#csvaddress2').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_city:{
                  required:function(el){
                    return $(el).closest('form').find('#csvcity').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_state:{
                  required:function(el){
                    return $(el).closest('form').find('#csvstate').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_postcode :{
                  required:function(el){
                    return $(el).closest('form').find('#csvpostcode').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
                csv_countryCode :{
                  required:function(el){
                    return $(el).closest('form').find('#csvcountryCode').is(':checked') == true;
                  },
                  notEqualToGroup: ['.csv_unique_select']
                },
              },
              messages: {
                name: {
                  required: "Please enter a Campaign Name.",
                  regex: "Please enter a valid input"
                },
                client_name: {
                  required:"Please select a Client Name",
                },
                cpmethod: {
                  required: "Please select a method.",
                },
                dncr_required:{required: "Please choose one."},
                start_age:{
                  required: "Please select starting Age",
                  geterthenendage: 'Min age must be less then max age'
                },
                end_age:{
                  required: "Please select ending Age",
                  lessthenage: 'Max age must be geter then min age.'
                },
                state_list:"Please enter a state",
                postcode_list:{
                  required :"Please enter a postcode",
                  regex :"Please enter a valid input",
                },
                type:{
                  required: "Please select a type.",
                },
                endpoint:{
                 required:"Please enter an endpoint.", 
                 url:"Please enter a valid URL(Ex:http(s)://chars.chars)"
                },
                email:{
                  require_from_group:"Please enter email field." 
                },
                phone:{
                  require_from_group:"Please enter phone field." 
                },
                firstName:{
                  notEqualTo:"first name and last name shouldn't be same."
                },
                lastName:{
                  notEqualTo:"first name and last name shouldn't be same."
                },
                server_type:{
                  required: "Please select a server type.",
                },
                csv_server:{
                  required: "Please enter a server.",
                  regex:"Please enter a valid server address(Ex:chars.chars)"
                },
                directory:{
                 required:"Please enter a directory." 
                },  
                csv_user:{
                 required:"Please enter a user." 
                },
                csv_password:{
                  required: "Please enter a password.",
                },
                csv_email:{
                 require_from_group:"Please select email." 
                },
                csv_phone:{
                  require_from_group: "Please select phone.",
                },
              }, 
              onfocusout: function(element) {
                this.element(element);
              },
              submitHandler: function(form) {
                $.ajax({
                  url: form.action,
                  type: form.method,
                  data: $(form).serialize(),
                  dataType: "json",
                  success: function(response) {
                    if(response==1){
                      jQuery('html').animate({ scrollTop: 0 }, 300);
                      window.location.href = "{{ action('CampaignController@index') }}";
                    } else{
                      $('#msg').text('Some problem occurred, campaign not created.').show().delay('3000').hide();
                    }
                  },
                  error: function(jqXHR, textStatus, errorThrown){
                    alert('some problem occurred, please try again.');
                  }
                });
              }
            });
          });

          jQuery(document).on('change','#cpmethod',function(){
            var val = $(this).val();
            if(val == 'API') {
              $('.csv').hide();
              $('.api').show();
            } else if(val == 'CSV') {
              $('.api').hide();
              $('.csv').show();
            } else {
              $('.api').hide();
              $('.csv').hide();
            }
          });
          
          jQuery(document).on('change','#age_criteria',function(){
            if($(this).is(":checked")) {
              var checked = $(this).val();
              $('#age_range').show();
            } else {
              $('#age_range').hide();
            }
          });

          jQuery(document).on('change','#state_criteria',function(){
            if($(this).is(":checked")) {
              $('#state_list_hidden').show();
            } else {
              $('#state_list_hidden').hide()
            }
          });
          jQuery(document).on('change','#postcode_criteria',function(){
            if($(this).is(":checked")) {
              $('#postcode_list_hidden').show()
            } else {
              $('#postcode_list_hidden').hide()
            }
          });
          jQuery(document).on('click','#active',function(){
            if(jQuery(this).is(':checked')==true){
              jQuery(this).attr('checked','checked');
              jQuery(this).val('1');
            } else {
              jQuery(this).removeAttr('checked');
              jQuery(this).val('0');
            }
          });
        /******** Enable/Disable button ******/
        jQuery(document).ready(function(){
          $('.changeallfieldforce').keyup(function(){
            $('#update').prop('disabled', false);
          });
          $('.changeallfieldforce').change(function(){
            $('#update').prop('disabled', false);
          });
        });
        /******** END ******/
        jQuery(document).on('click','.csvRequired',function(){
            if(jQuery(this).is(':checked')==true){
              jQuery(this).attr('checked','checked');
              jQuery(this).val('1');
            } else {
              jQuery(this).removeAttr('checked');
              jQuery(this).val('0');
            }
          });
          jQuery(document).on('click','.apiRequired',function(){
            if(jQuery(this).is(':checked')==true){
              jQuery(this).attr('checked','checked');
              jQuery(this).val('1');
            } else {
              jQuery(this).removeAttr('checked');
              jQuery(this).val('0');
            }
          });
    </script>
    </div>   
  @else
    <div class="xs">
      <div  class="alert alert-danger" >You are not allowed to access this page</div>
    </div>
  @endif
@endsection