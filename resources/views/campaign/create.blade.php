@extends('layouts.app')
@section('title','Add/Campaign')
@section('content')
  @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
    <div class="xs">
      <h3>Create Campaign</h3>
      <div class="well1 white">
        <div id="msg"></div>
        <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" id="campaignCreate" name="campaignCreate" method="post" action="{{ action('CampaignController@store') }}">
          {{ csrf_field() }}
          <fieldset>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label">Campaign Name</label>
                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label">Client Name</label>
                <select class="form-control1 ng-invalid ng-invalid-required ng-touched" name="client_name" id="client_name">
                  <option value="">---Please Select Client---</option>
                  @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label">Method</label>
                <select class="form-control1 ng-invalid ng-invalid-required ng-touched" name="cpmethod" id="cpmethod">
                  <option value="">---Please Select Method---</option>
                  <option value="API">API</option>
                  <option value="CSV">CSV</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <div class="checkboxb checkbox-primary">
                  <input id="age_criteria" name="age_criteria" class="age_criteria"  type="checkbox">
                  <label for="age_criteria">Age Criteria</label>
                </div>
              </div>
            </div>
            <div id="age_range" style="display:none">
              <h4>Age Range</h4>
              <div class="form-group">
                <div class="col-sm-6">
                  <select class="form-control1 unique-areRange" name="start_age" id="start_age">
                    <option value="">---Please Select Min Age---</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <select class="form-control1 unique-areRange" name="end_age" id="end_age">
                  <option value="">---Please Select Max Age---</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <div class="checkboxb checkbox-primary">
                  <input id="state_criteria" name="state_criteria" class="state_criteria"  type="checkbox">
                  <label for="state_criteria">
                    State Criteria
                  </label>
                </div>
              </div>
            </div>
            <div id="state_list_hidden" style="display:none">
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="text" name="state_list" class="state_list form-control1" id="state_list">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <div class="checkboxb checkbox-primary">
                  <input id="postcode_criteria" name="postcode_criteria" class="postcode_criteria"  type="checkbox">
                  <label for="postcode_criteria">
                    Postcode Criteria
                  </label>
                </div>
              </div>
            </div>
            <div id="postcode_list_hidden" style="display:none">
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="postcode_list">Postcode List:</label>
                  <textarea name="postcode_list" class="postcode_list form-control1" rows="5" id="postcode_list"></textarea>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <label>DNCR Required</label>
                <div class="radio radio-info radio-inline" style="padding-top:0px;">
                  <input type="radio" id="dncr_required1" value="0" name="dncr_required" >
                  <label for="dncr_required1">Yes </label>
                </div>
                <div class="radio radio-inline radio-info" style="padding-top:0px;">
                  <input type="radio" id="dncr_required2" value="1" name="dncr_required">
                  <label for="dncr_required2"> No </label>
                </div>
                <label for="dncr_required" class="error" style="display:none;">Please choose one.</label>
              </div>
              <div class="col-sm-6">
                <label class="control-label">&nbsp;</label>
                <div class="checkboxb checkbox-primary">
                  <input id="active" name="active" type="checkbox" checked="checked">
                  <label for="active">
                    Active
                  </label>
                </div>
              </div>
            </div>
            <div class="api" style="display:none">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="type">Type:</label>
                  <select class="form-control1" id="type" name="type" >
                    <option value="">--Please select type--</option>
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="JSON POST">JSON POST</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label class="control-label">Endpoint</label>
                  <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="endpoint" name="endpoint" class="endpoint" />
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">User</label>
                  <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="user" name="user" class="user" />
                </div>    
                <div class="col-sm-6">
                  <label class="control-label">Password</label>
                  <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="password" name="password" class="password" />
                </div>
              </div>    
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">Port</label>
                  <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="port" name="port" class="port" />
                </div>
              </div>     
              <h3>Standard Parameter Mapping Fields</h3>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="email">email:</label>
                    <input type="text" class="form-control1 api-valid-group unique-api" id="email" name="email">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apiemail" class="apiRequired" name="api[email]" type="checkbox" value="0">
                      <label for="apiemail">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="phone">phone:</label>
                    <input type="text" class="form-control1 phone api-valid-group unique-api" id="phone" name="phone">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apiphone" class="apiRequired" name="api[phone]" type="checkbox" value="0">
                      <label for="apiphone">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="title">title:</label>
                    <input type="text" class="form-control1 title unique-api" id="title" name="title">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apititle" class="apiRequired" name="api[title]" type="checkbox" value="0">
                      <label for="apititle">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="firstName">firstName:</label>
                    <input type="text" class="form-control1 firstName unique-api" id="firstName" name="firstName">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apifirstName" class="apiRequired" name="api[firstName]" type="checkbox" value="0">
                      <label for="apifirstName">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="lastName">lastName:</label>
                    <input type="text" class="form-control1 lastName unique-api" id="lastName" name="lastName">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apilastName" class="apiRequired" name="api[lastName]" type="checkbox" value="0">
                      <label for="apilastName">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="birthdate">birthdate:</label>
                    <input type="text" class="form-control1 birthdate unique-api" id="birthdate" name="birthdate">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apibirthdate" class="apiRequired" name="api[birthdate]" type="checkbox" value="0">
                      <label for="apibirthdate">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="age">age:</label>
                    <input type="text" class="form-control1 age unique-api" id="age" name="age">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apiage" class="apiRequired" name="api[age]" type="checkbox" value="0">
                      <label for="apiage">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="ageRange">ageRange:</label>
                    <input type="text" class="form-control1 ageRange unique-api" id="ageRange" name="ageRange">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apiageRange" class="apiRequired" name="api[ageRange]" type="checkbox" value="0">
                      <label for="apiageRange">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="gender">gender:</label>
                    <input type="text" class="form-control1 gender unique-api" id="gender" name="gender">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apigender" class="apiRequired" name="api[gender]" type="checkbox" value="0">
                      <label for="apigender">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="address1">address1:</label>
                    <input type="text" class="form-control1 address1 unique-api" id="address1" name="address1">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apiaddress1" class="apiRequired" name="api[address1]" type="checkbox" value="0">
                      <label for="apiaddress1">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="address2">address2:</label>
                    <input type="text" class="form-control1 address2 unique-api" id="address2" name="address2">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apiaddress2" class="apiRequired" name="api[address2]" type="checkbox" value="0">
                      <label for="apiaddress2">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="city">city:</label>
                    <input type="text" class="form-control1 city unique-api" id="city" name="city">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apicity" class="apiRequired" name="api[city]" type="checkbox" value="0">
                      <label for="apicity">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="state">state:</label>
                    <input type="text" class="form-control1 state unique-api" id="state" name="state">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apistate" class="apiRequired" name="api[state]" type="checkbox" value="0">
                      <label for="apistate">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="postcode">postcode:</label>
                    <input type="text" class="form-control1 postcode unique-api" id="postcode" name="postcode">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apipostcode" class="apiRequired" name="api[postcode]" type="checkbox" value="0">
                      <label for="apipostcode">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="countryCode">countryCode:</label>
                    <input type="text" class="form-control1 countryCode unique-api" id="countryCode" name="countryCode">
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-api checkboxb checkbox-primary">
                      <input id="apicountryCode" class="apiRequired" name="api[countryCode]" type="checkbox" value="0">
                      <label for="apicountryCode">Required</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="csv" style="display:none">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="server_type">Server Type:</label>
                  <select class="form-control1" id="server_type" name="server_type">
                    <option value="">--Please select server type--</option>
                    <option value="ftp">FTP</option>
                    <option value="sftp">SFTP</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label class="control-label">Server</label>
                  <input type="text" class="form-control1" id="csv_server" name="csv_server" class="csv_server" />
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">Directory</label>
                  <input type="text" class="form-control1" id="directory" name="directory" class="directory" />
                </div>   
                <div class="col-sm-6">
                  <label class="control-label">User</label>
                  <input type="text" class="form-control1" id="csv_user" name="csv_user" class="csv_user" />
                </div>
              </div> 
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">Password</label>
                  <input type="text" class="form-control1" id="csv_password" name="csv_password" class="csv_password" />
                </div>
              </div>    
              <h3>Column-Mapping Fields</h3>

              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_email">email:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_email csv-required-group unique" name="csv_email" id="csv_email">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvemail" class="csvRequired" name="csv[email]" type="checkbox" value="0">
                      <label for="csvemail">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_phone">phone:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_phone csv-required-group unique" name="csv_phone" id="csv_phone">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvphone" class="csvRequired" name="[phone]" type="checkbox" value="0">
                      <label for="csvphone">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_title">title:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_title unique" name="csv_title" id="csv_title">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvtitle" class="csvRequired" name="csv[title]" type="checkbox" value="0">
                      <label for="csvtitle">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_firstName">firstName:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_firstName unique" name="csv_firstName" id="csv_firstName">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvfirstName" class="csvRequired" name="csv[firstName]" type="checkbox" value="0">
                      <label for="csvfirstName">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_lastName">lastName:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_lastName unique" name="csv_lastName" id="csv_lastName">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvlastName" class="csvRequired" name="csv[lastName]" type="checkbox" value="0">
                      <label for="csvlastName">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_birthdate">birthdate:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_birthdate unique" name="csv_birthdate" id="csv_birthdate">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvbirthdate" class="csvRequired" name="csv[birthdate]" type="checkbox" value="0">
                      <label for="csvbirthdate">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_age">age:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_age unique" name="csv_age" id="csv_age">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvage" class="csvRequired" name="csv[age]" type="checkbox" value="0">
                      <label for="csvage">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_ageRange">ageRange:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_ageRange unique" name="csv_ageRange" id="csv_ageRange">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvageRange" class="csvRequired" name="csv[ageRange]" type="checkbox" value="0">
                      <label for="csvageRange">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_gender">gender:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_gender unique" name="csv_gender" id="csv_gender">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvgender" class="csvRequired" name="csv[gender]" type="checkbox" value="0">
                      <label for="csvgender">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_address1">address1:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_address1 unique" name="csv_address1" id="csv_address1">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvaddress1" class="csvRequired" name="csv[address1]" type="checkbox" value="0">
                      <label for="csvaddress1">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_address2">address2:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_address2 unique" name="csv_address2" id="csv_address2">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvaddress2" class="csvRequired" name="csv[address2]" type="checkbox" value="0">
                      <label for="csvaddress2">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_city">city:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_city unique" name="csv_city" id="csv_city">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvcity" class="csvRequired" name="csv[city]" type="checkbox" value="0">
                      <label for="csvcity">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_state">state:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_state unique" name="csv_state" id="csv_state">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvstate" class="csvRequired" name="csv[state]" type="checkbox" value="0">
                      <label for="csvstate">Required</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_postcode">postcode:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_postcode unique" name="csv_postcode" id="csv_postcode">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvpostcode" class="csvRequired" name="csv[postcode]" type="checkbox" value="0">
                      <label for="csvpostcode">Required</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <div class="col-sm-8">
                    <label for="csv_countryCode">countryCode:</label>
                    <select class="form-control1 ng-invalid ng-invalid-required csv_countryCode unique" name="csv_countryCode" id="csv_countryCode">
                      <option value="">Please Select</option>
                      @for($i=0;$i<100;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="checkboxb-csv checkboxb checkbox-primary">
                      <input id="csvcountryCode" class="csvRequired" name="csv[countryCode]" type="checkbox" value="0">
                      <label for="csvcountryCode">Required</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Save New Campaign</button>
              <button type="reset" class="btn btn-default">Reset</button>
            </div>
          </fieldset>
        </form>
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
                    if (thisVal == valueToCompare) {
                        matchesFound++;
                    }
                });
                // count should be either 0 or 1 max
                if (this.optional(element) || matchesFound <= 1) {
                    //elems.removeClass('error');
                    return true;
                } else {
                    //elems.addClass('error');
                }
            }, $.validator.format("Please enter a Unique Value."));
            jQuery("form[name='campaignCreate']").validate({
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
                  notEqualToGroup: ['.unique-areRange']
                },
                end_age:{
                  required: function(el){
                    return $(el).closest('form').find('#age_criteria').is(':checked') == true;
                  },
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
                email:{ require_from_group: [1, ".api-valid-group"],notEqualToGroup: ['.unique-api'] },
                phone:{ require_from_group: [1, ".api-valid-group"],notEqualToGroup: ['.unique-api'] },  
                title:{ notEqualToGroup: ['.unique-api'] },
                firstName:{ notEqualTo: ['#lastName'],notEqualToGroup: ['.unique-api'] },
                lastName:{ notEqualTo: ['#firstName'],notEqualToGroup: ['.unique-api'] },
                birthdate:{ notEqualToGroup: ['.unique-api'], },
                age:{ notEqualToGroup: ['.unique-api'] },
                ageRange:{ notEqualToGroup: ['.unique-api'] },
                gender:{ notEqualToGroup: ['.unique-api'] },
                address1:{ notEqualToGroup: ['.unique-api'] },
                address2:{ notEqualToGroup: ['.unique-api'] },
                city:{ notEqualToGroup: ['.unique-api'] },
                state:{ notEqualToGroup: ['.unique-api'] },
                postcode:{ notEqualToGroup: ['.unique-api'] },
                countryCode:{ notEqualToGroup: ['.unique-api'] },
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
                  require_from_group: [1, ".csv-required-group"],
                  notEqualToGroup: ['.unique']
                },
                csv_phone:{
                  require_from_group: [1, ".csv-required-group"],
                  notEqualToGroup: ['.unique']
                },
                csv_title:{ notEqualToGroup: ['.unique'] },
                csv_firstName:{ notEqualToGroup: ['.unique'] },
                csv_lastName:{ notEqualToGroup: ['.unique'] },
                csv_birthdate:{ notEqualToGroup: ['.unique'] },
                csv_age:{ notEqualToGroup: ['.unique'] },
                csv_ageRange:{ notEqualToGroup: ['.unique'] },
                csv_gender:{ notEqualToGroup: ['.unique'] },
                csv_address1:{ notEqualToGroup: ['.unique'] },
                csv_address2:{ notEqualToGroup: ['.unique'] },
                csv_city:{ notEqualToGroup: ['.unique'] },
                csv_state:{ notEqualToGroup: ['.unique'] },
                csv_postcode :{ notEqualToGroup: ['.unique'] },
                csv_countryCode :{ notEqualToGroup: ['.unique'] },
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
                start_age:"Please select Min Age",
                end_age:"Please select Max Age",
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
    </div>   
  @else
    <div class="xs">
      <div  class="alert alert-danger" >You are not allowed to access this page</div>
    </div>
  @endif
@endsection