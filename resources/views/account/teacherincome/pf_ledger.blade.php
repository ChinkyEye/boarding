@extends('account.main.app')
@push('style')
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
@endpush
@section('content')
<?php $page = str_replace('account.','',Route::currentRouteName()); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('account.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <button class="btn btn-xs btn-info rounded-0 mt-2" onclick="PrintDiv('printTable')">Print me</button>
  <div class="card" id="printTable">
    <div class="card-body">
      <h2 class="text-center"> कर्मचारी संचय कोष</h2>
      <h3 class="text-center"> कोषकट्टी फाटबारी</h3>
      <div class="container">
         @foreach($teachers as $key=>$teacher)
        <div class="row">
          <div class="col-md-6">
            <ul class="list-group">
              <li class="list-group-item">कार्यालयको नाम :- {{$settings->school_name}}</li>
              <li class="list-group-item">कार्यालयको ठेगाना :- {{$settings->address}}</li>
              <li class="list-group-item">कार्यालयको कोड नं :- {{$settings->school_code}}</li>
              <li class="list-group-item">रकम दाखिला गरेको बैंकको नाम :-  {{$teacher->get_teacher_bank_info_count ?$teacher->getTeacherBankInfo->bank_name : '' }}</li>
              <li class="list-group-item">कोष कट्टी महिना :- 
                {{-- <input type="text" class="border-0 bg-transparent" id="kosh_katti_mahina" autocomplete="off" placeholder="YYYY-MM-DD"> --}}

                {{$teacher->month}}
              </li>
              <li class="list-group-item">रकम दाखिला गरेको मिती :-
                <input type="text" class="border-0 bg-transparent" id="kosh_katti_miti" autocomplete="off" placeholder="YYYY-MM-DD">
              </li>
              <li class="list-group-item">जम्मा दाखिला रकम रु :- {{$teacher->amount}}</li>
              <li class="list-group-item">अक्षरेपी रु :- </li>
            </ul>
          </div>
          <div class="col-md-6">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="4" class="text-center">कोष प्रयोजन को लागि मात्र </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th width="125">Batch No.</th>
                  <td colspan="4"></td>
                </tr>
                <tr>
                  <th>Folio No.</th>
                  <td colspan="4"></td>
                </tr>
                <tr>
                  <th>V.P. Date</th>
                  <td colspan="4"></td>
                </tr>
                <tr>
                  <th>Bank Code</th>
                  <td colspan="4"></td>
                </tr>
                <tr>
                  <th>From Month</th>
                  <td></td>
                  <td width="100" class="text-bold">Up to M</td>
                  <td></td>
                </tr>
                <tr>
                  <th>Amount</th>
                  <td colspan="4"></td>
                </tr>
                <tr>
                  <th>Coded by</th>
                  <td colspan="4"></td>
                </tr>
                <tr>
                  <th>Entry by</th>
                  <td colspan="4"></td>
                </tr>
                <tr>
                  <th>Released by</th>
                  <td colspan="4"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th width="10">सि न.</th>
                    <th width="100">व्यक्तिगत नम्बर</th>
                    <th width="100">दर्जा </th>
                    <th width="100">कोष परिचयपत्र नम्बर</th>
                    <th width="100">नाम,थर </th>
                    <th width="100">जम्मा कोष कट्टी </th>
                    <th width="100">अनिवार्य  कोष कट्टी </th>
                    <th width="100"> कोष कट्टीमा थप  </th>
                    <th width="100">कैफियत</th>
                  </tr>
                </thead>              
                <tr class="text-center">
                  <td>{{$key +1}}</td>
                  <td>{{$teacher->getTeacher->phone}}</td>
                  <td></td>
                  <td>{{$teacher->getTeacher->cinvestment_id}}</td>
                  <td> {{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
                  <td>{{$teacher->total_pf}}</td>
                  <td>{{$teacher->pro_f1}}</td>
                  <td>{{$teacher->pro_f2}}</td>
                  <td></td>
                </tr>
                <tr class="text-center">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="text-center">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="text-right">
                  <td colspan="5">यस पानाको जम्मा कुल जम्मा </td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                
                <tr class="text-right">
                  <td colspan="5"> कुल जम्मा </td>
                  <td>{{$teacher->total_pf}}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </table>
            </div>
            
          </div>
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">कुल फाटबारी पाना संख्या:</label>
              <div class="col-sm-9">
                <input class="effect-none" type="text" placeholder="_">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">मध्यको  फाटबारी पाना नम्बर:</label>
              <div class="col-sm-9">
                <input class="effect-none" type="text" placeholder="_">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group row">
              <label class="col-md-4 col-form-label">तयार गर्ने:</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group row">
              <label class="col-md-4 col-form-label">चेक गर्ने :</label>
              
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group row">
              <label class="col-md-4 col-form-label">कार्यालय प्रमुख :</label>
              
            </div>
          </div>

        </div>
         @endforeach
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  var currentMonth = NepaliFunctions.GetCurrentBsMonth();
  $('#kosh_katti_miti').val(currentDate);
  // $('#kosh_katti_mahina').val(currentMonth);
  $('#kosh_katti_miti').nepaliDatePicker({
    ndpMonth: true,
    disableAfter: currentDate,
    onChange: function(event) {
      var date = $('#filter_date').val();
      $('#excDate').val(date);
    }
    });
  });
</script>
{{-- <script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#kosh_katti_mahina').val(currentDate);
  $('#excDate').val(currentDate);
  $('#filter_date').nepaliDatePicker({
    ndpMonth: true,
    });
  });
</script> --}}
@endpush