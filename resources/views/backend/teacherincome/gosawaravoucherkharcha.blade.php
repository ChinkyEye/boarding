@extends('backend.main.app')
@push('style')
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
@endpush
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
<section class="content" >
  <button class="btn btn-xs btn-info rounded-0 mt-2" onclick="PrintDiv('printTable')">Print me</button>
  <div class="card" id="printTable">
    <div class="card-header">
      <h2 class="text-center">.........बिद्यालयको नाम </h2>
      <h4 class="text-center">गोश्रारा भौचर - खर्च </h4>
       
    <div class="card-body">
      <div class="container mb-4 mt-5 ">
        <div class="row">
          <div class="col-md-8">
            <p>बजेट उप शीर्षक नं ......</p>
            <p>बिद्यालय कोड  नं  {{$settings->school_code}}</p>
          </div>
          <div class="col-md-4">
            <p class="text-left">मिती : 
             <input type="text" class="border-0 bg-transparent" id="miti" autocomplete="off" placeholder="YYYY-MM-DD"></p>
            <p class="text-left">गो.भौ.नं......................</p>
            <p class="text-left"> बिधुतिय कारोबर नं</p>
          </div>
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th width="10" rowspan="2">क्र स </th>
                    <th width="100" rowspan="2">खर्च शीर्षक नम्बर </th>
                    <th width="100" rowspan="2">खर्च शीर्षकको नाम </th>
                    <th width="100" rowspan="2">कारोबारको ब्यहोरा </th>
                    <th width="100" rowspan="2">खाता पाना नं</th>
                    <th width="100" colspan="4">स्रोतको</th>
                    <th width="100" rowspan="2">डेबिट  </th>
                    <th width="100" rowspan="2">क्रेडिट</th>
                  </tr>
                  <tr>
                    <th>तह</th>
                    <th>स्रोत व्यहोर्ने संस्था</th>
                    <th>प्रकार</th>
                    <th>भुक्तानी बिधी </th>
                  </tr>
                </thead>              
                <tr class="text-center">
                  <td>१</td>
                  <td>२</td>
                  <td>३</td>
                  <td>४</td>
                  <td>५</td>
                  <td>६</td>
                  <td>७</td>
                  <td>८</td>
                  <td>९</td>
                  <td>१०</td>
                  <td>११</td>
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
                  <td></td>
                  <td></td>
                </tr>
                
                <tr class="text-right">
                  <td colspan="9" class="text-bold">जम्मा </td>
                  <td></td>
                  <td></td>
                </tr>
              </table>
              <hr>
              <p class="text-left">जम्मा रकम अक्षरमा ............................................. |</p>
            </div>
            <div class="col-md-12">
              <p class="text-bold"><u>करोबारको संक्षिप्त ब्यहोरा </u></p>
              <input class="effect-none form-control" type="text" placeholder="( .............................................................................................................................................................................................................................................. )">
            </div>
            <hr>
         
            
          </div>
          <div class="col-md-8">
            <p class="text-bold">(भुक्तनी प्रायोजनका लागि)</p>
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th width="10">क्र स</th>
                    <th width="100">भुक्तनी पाउने को नाम </th>
                    <th width="100">बैंक खाता नं/ भार्पाइ नं </th>
                    <th width="100">पान नं</th>
                    <th width="100">भुक्तनी रकम  </th>
                  </tr>
                </thead>              
                <tr class="text-center">
                  <td>१</td>
                  <td>२</td>
                  <td>३</td>
                  <td>४</td>
                  <td>५</td>
                </tr>
                
                @foreach($teacher_incomes as $key=>$teacher)
                <tr class="text-center">
                  <td>{{$key +1}}</td>
                  <td>{{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
                  <td>{{$teacher->get_teacher_bank_info_count ?$teacher->getTeacherBankInfo->bank_name : '' }}</td>
                  <td>{{$teacher->getTeacher->pan_id}}</td>
                  <td>{{$teacher->net_salary}}</td>
                </tr>
                @endforeach
                
                <tr class="text-right">
                  <td colspan="3" class="text-bold">जम्मा </td>
                  <td></td>
                  <td></td>
                </tr>
              </table>
              {{-- <p class="text-right">dev</p> --}}
            </div>
            
          </div>
          <div class="col-md-4">
            <p class="text-center text-bold">संलगन कागजात संख्या -</p>
            {{-- <p class="text-center">तह - स्थानीया </p> --}}
            {{-- <p class="text-center">संस्था - काठमाडौं महानगरपालिका </p> --}}
            {{-- <p class="text-center">प्रकार - नगद </p> --}}
          </div>
          <div class="col-md-4">
            <p class="text-left">तयार गर्ने :.................... </p>
            <p class="text-left">मिती :.................... </p>
            <p class="text-left">दर्जा :....................</p>
          </div>
          <div class="col-md-4">
            <p class="text-left">पेश गर्ने :.................... </p>
            <p class="text-left">मिती :.................... </p>
            <p class="text-left">दर्जा :....................</p>
          </div>
          <div class="col-md-4">
            <p class="text-left">स्विकृत गर्ने :.................... </p>
            <p class="text-left">मिती :.................... </p>
            <p class="text-left">दर्जा :....................</p>
          </div>

        </div>
        <hr>
        <hr>
        <div>
        <h2>दष्रटब्य : </h2>
        <p>१. क्रम संख्या उल्लेख गर्ने |</p>
        <p>२. खर्च शिर्षक नम्बर उल्लेख गर्ने |</p>
        <p>३. खर्च शिर्षकको नाम उल्लेख गर्ने | </p>
        <p>४. खर्च कारोबारको संक्षिप्त ब्यहोरा खुलाउने |</p>
        <p>५. खर्च शिर्षकको सम्बंधित खाताको पाना नम्बर उल्लेख गर्ने |</p>
        <p>६. खर्च श्रोत कुन तहबाट बजेट प्राप्त भएको हो सो खुलाउने |</p>
        <p>७. खर्च को श्रोत ब्यहोर्ने निकाय/संस्थाको नाम खुलाउने |</p>
        </div>

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
  $('#miti').val(currentDate);
  // $('#kosh_katti_mahina').val(currentMonth);
  $('#miti').nepaliDatePicker({
    ndpMonth: true,
    disableAfter: currentDate,
    onChange: function(event) {
      var date = $('#filter_date').val();
      $('#excDate').val(date);
    }
    });
  });
</script>
@endpush