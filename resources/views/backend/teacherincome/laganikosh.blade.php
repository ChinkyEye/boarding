@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<?php
$page= str_replace('admin.','',Route::currentRouteName());
 ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <button class="btn btn-xs btn-info rounded-0 mt-2" onclick="PrintDiv('printTable')">Print me</button>
  <div class="card" id="printTable">
    <h4 class="text-center py-3">नागरिक लगानी कोष सावधिक जीवन बिना कोष कट्टी फाँटवारी</h4>
    <div class="col-md-12 mt-2">
      <table class="table table-bordered">
        <tbody>
          <tr>
            <td width="50%">कार्यालयको नाम: <span class="float-right">{{$school_info->school_name}}</span></td>
            <td>कार्यालयको फोन:<span class="float-right">{{$school_info->phone_no}}</span></td>
          </tr>
          <tr>
            <td>कार्यालयको छाप:</td>
            <td>कोष कट्टी साल र महिना: <input type="text" class="border-0 float-right" style="text-align: right" id="kosh_katti_date" autocomplete="off" placeholder="YYYY-MM-DD"></td>
          </tr>
          <tr>
            <td>कार्यालयको कोड: <span class="float-right">{{$school_info->school_code}}</span></td>
            <td>बैंक दाखिला मिति: <input type="text" class="border-0 float-right" style="text-align: right" id="deposit_date" autocomplete="off" placeholder="YYYY-MM-DD"></td>
          </tr>
          <tr>
            <td class="border-0" rowspan="2">
              <p><strong>नागरिक लगानी कोष</strong><br>
                पुतलीसडक, काठमाडौं <br>
                ४२२८७५९ ४२४०६१५ फ्याक्स न. ४२४०६४५
              </p>
            </td>
            <td>बैंक दाखिला रकम:<span class="float-right" id="deposit_date"></span></td>
          </tr>
          <tr>
            <td>बैंकको नाम र ठेगाना:  @foreach($staff_has_banks as $key =>$banks)<span class="float-right">{{$banks->bank_name}},{{$banks->bank_address}}</span> @endforeach</td>
          </tr>
        </tbody>
      </table>
      <h4 class="text-center py-3">यस कार्यालयको निम्न कर्मचारीहरुको फाँटवारी र बैंक दाखिला भौचर समावेश गरी आवश्यक पठाइएको छ  </h4>
    </div>
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr class="text-center">
              <th width="10" rowspan="2">पद</th>
              <th width="100" rowspan="2">नाम , थर </th>
              <th width="100" rowspan="2">बीमा परिचयपत्र नं.</th>
              <th width="100" colspan="3">बीमा वापतको ना.ल. कोष कट्टी रकम</th>
              <th width="100" rowspan="2">कोड</th>
              <th width="100" rowspan="2">सिट रोल नं.</th>
              <th width="100" rowspan="2">कैफियत</th>
            </tr>
            <tr>
              <th>मासिक तलब बाट</th>
              <th>नेपाल सरकारको तर्फबाट थप</th>
              <th>जम्मा कट्टी</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach($teacherincomes as $key => $income)
            @php
            $total1= $income->insurance + $income->pro_f1;
            $total2= $income->insurance + $income->pro_f1+ $income->inusrance + $income->pro_f2 + $income->mahangi_vatta + $income->durgam_vatta;
            @endphp             
            <tr class="text-center">
              <td>{{$income->getTeacher->designation}}</td>
              <td>{{$income->getTeacherUser->name}} {{$income->getTeacherUser->middle_name}} {{$income->getTeacherUser->last_name}}</td>
              <td>{{$income->getTeacher->insurance_id}}</td>
              <td>{{$total1}}</td>
              <td>{{$income->inusrance + $income->pro_f2 + $income->mahangi_vatta + $income->durgam_vatta}}</td>
              <td>{{$total2}} <span class="badge badge-info float-sm-right">{{$month}}</span></td>
              <td>{{$income->getTeacher->teacher_code}}</td>
              <td></td>
              <td>{{$income->remark}}</td>
            </tr>
            @endforeach
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
              <td>जम्मा</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group row">
            <label class="col-md-4 col-form-label">तयार गर्ने:</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group row">
            <label class="col-md-4 col-form-label">लेखापाल  :</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group row">
            <label class="col-md-4 col-form-label">अधिकृत  :</label>
          </div>
        </div>
      </div>
      <hr style="border: 1px solid">
      <h5><b>प्रत्येक कर्मचारीको परिचय पत्र नं र कार्यालयको कोड नं प्रथमपटक कोषले प्रदान गर्नेछ | त्यसपछी कार्यालयले भर्नु पर्नेछ  र सरुवा भई आएका कर्मचारीको सम्बन्धमा कैफियत जनाउनुहोला | अन्यथा हिसाब फरक पर्न सक्नेछ </b></h5>
      
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#kosh_katti_date').val(currentDate);
  $('#deposit_date').val(currentDate);
  $('#deposit_date').nepaliDatePicker({
    ndpYear: true,
    ndpMonth: true,
    ndpYearCount: 10,
    disableAfter: currentDate,
    });
  $('#kosh_katti_date').nepaliDatePicker({
    ndpYear: true,
    ndpMonth: true,
    ndpYearCount: 10,
    disableAfter: currentDate,
    });
  });
</script>
@endpush