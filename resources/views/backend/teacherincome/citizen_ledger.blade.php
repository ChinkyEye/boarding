@extends('backend.main.app')
@section('content')
<?php $page = str_replace('admin.','',Route::currentRouteName()); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <p class="card-title">Salary Detail</p>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">

      <div class="container mb-4 mt-5 ">
        <div class="row">
          <div class="col-sm-12">
            <form>
              <div class="form-group row upper-case">
                <label class="col-sm-4"><h2>श्री </h2></label>
                <div class="col-sm-4">
                  {{-- <input class="effect-none" type="text" value="{{$teacher->school->schoolName}}"> --}}
                </div>
                 <label class="col-sm-4"><h3>बिद्यालय</h3></label>
              </div>
            </form>
            <h2>मोरङ्</h2>
          </div>
          <div class="col-12">
            <div class="row">
              <div class="col-md-8">
                <form>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">पत्र संख्या :</label>
                    <div class="col-sm-10">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">चलानी नम्बर :</label>
                    <div class="col-sm-10">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-4">
                <div class="row">
                  <div class="form-group row miti">
                    <label class="col  col-form-label">मिति:</label>
                    <div class="col">
                     <input class="effect-none" type="text" placeholder="0000/00/00">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 Max-sub">
            <h2>बिषय: नागरिक लगानी कोषको फाटबारी पठाएको बारे |</h2>
            <h3>श्री नागरीक लगानी कोष</h3>
            <h3>नयाबानेशवर काठमाडौँ</h3>
            <div class="row">
              <div class="col-sm-12 mt-5">
                <form>
                  <div class="form-group row upper-case">
                    <label class="col-sm-3">यस बिद्यालय कार्यरत निम्ती शिक्षक्हरुको</label>
                    <div class="col-sm-2">
                      <input class="effect-none" type="text" placeholder="२०७६/00/00">
                    </div>
                    <label class="col-sm-5">महिनामा नागरिक लगानी कोष बापत कट्टा भएको रकम मिति</label>
                    <div class="col-sm-2">
                      <input class="effect-none" type="text" placeholder="२०७६/00/00">
                    </div>
                  </div>
                  <div class="form-group row upper-case">
                    <label class="col-sm-1">मा</label>
                    <div class="col-sm-5">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                    <label class="col-sm-6">बैंकको ना.ल.कोषको खाता नं. मा जम्मा भएको भौचर यसै साथ् संलगन गरी  </label>
                  </div>
                  <div class="form-group row upper-case">
                    <label class="col">पठाएको ब्यहोरा अनुरोध छ</label>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
          <div class="col mt-2">
            <div class="row">
              <table class="table table-sm" border="1">
                <thead>
                  <tr>
                    <th rowspan="2">सि न.</th>
                    <th rowspan="2">पद</th>
                    <th rowspan="2">कर्मचारीको नाम</th>
                    <th rowspan="2">ना. ल. प. प. नं.</th>
                    <th rowspan="2">कट्टी रकम</th>
                    <th rowspan="2">जम्मा</th>
                    <th rowspan="2">कैफियत</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($teachers)>0)
                  @foreach($teachers as $key=>$account)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$account->getTeacher->designation}}</td>
                    <td>{{$account->getTeacherUser->name}} {{$account->getTeacherUser->middle_name}} {{$account->getTeacherUser->last_name}}</td>
                    <td>{{$account->getTeacher->cinvestment_id}}</td>
                    <td>{{$account->citizen_investment_deduction}}</td>
                    <td>{{$account->citizen_investment_deduction}}</td>
                    <td></td>
                  </tr>
                  @endforeach 
                       @else
                          <tr>
                            <td colspan="8">No P.F. details available</td>
                          </tr>                            
                       @endif 
                  <tr>
                      <td></td>
                      <td></td>
                      <td class="text-center">जम्मा</td>
                      <td></td>
                      <td>{{$account->citizen_investment_deduction}}</td>
                      <td>{{$account->citizen_investment_deduction}}</td>
                      <td></td>
                  </tr>
                </tbody>
              </table>
              <div class="col-sm-12 footer">
                <div class="row mt-5">
                  <div class="col-sm-6">
                    <h2>तयार गर्ने:</h2>
                  </div>
                  <div class="col-sm-6">
                    <h2>सदर  गर्ने:</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        
    </div>
  </div>
</section>
@endsection