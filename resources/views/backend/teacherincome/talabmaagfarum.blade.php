@extends('backend.main.app')
@push('style')
<style type="text/css" media="print">
  @page { 
    /*size: auto;    auto is the initial value */
    /*margin: 0;   this affects the margin in the printer settings */
    /*border: 1px solid red;   set a border for all printed pages */
    size: landscape;
  }
</style>
@endpush
@section('content')
<?php $page = str_replace('admin.','',Route::currentRouteName()); ?>
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
      <div class="col-md">
        <button class="btn btn-xs btn-info rounded-0 mt-2" onclick="PrintDiv('printTable')">Print me</button>
      </div>
    </div>
  </div>
</section>
<section class="content" id="printTable">
  <div class="card">
    <h5 class="col-md-12 pt-3"><span class="float-right">फाराम नं: ९</span></h5>
  	<h5 class="text-center pt-3"><strong>शिक्षक तलब माग फाराम</strong></h5>
  	<h5 class="text-center pb-3">{{" ".$year_date." "}}<strong>साल</strong> {{' '.$month." "}}<strong>महिना</strong></h5>
  	<h5 class="col-md-12 pb-3">बिद्यालयको नाम: {{$settings->school_name}}({{$settings->school_code}})</h4>
  		<div class="col-md-12 table-responsive">
  			<table class="table table-bordered table-sm" >
          {{-- @foreach($staff_has_banks as $key =>$banks) --}}
  				<thead>
  					<tr class="text-center">
  						<th width="" colspan="7"><span class="float-left">ठेगाना:- {{$settings->address}}</span></th>
  						<th width="" colspan="8"><span class="float-left">वार्षिक स्वीकृत रु.................</span> </th>
  						<th rowspan="5" class="main-rotate-90"><span >अनिवार्य भर्नुपर्ने</span></th>
  						<th></th>
  						<th colspan="2"></th>
  						<th></th>
  						<th></th>
  					</tr>
  					<tr>
  						<th width="" colspan="7">रकम जम्मा हुने बैंक:- @foreach($staff_has_banks as $key =>$banks) {{$banks->bank_name}} @endforeach</th>
  						<th width="" colspan="8">हाल सम्म निकासा भएको रु.........</th>
  						<th></th>
  						<th colspan="2"></th>
  						<th></th>
  						<th></th>
  					</tr>
  					<tr>
  						<th width="" colspan="7">खाता न:- @foreach($staff_has_banks as $key =>$banks) {{$banks->account_no}} @endforeach</th>
  						<th width="" colspan="8">हाल सम्म निकासा माग गरिएको रु.........</th>
  						<th></th>
  						<th colspan="2"></th>
  						<th></th>
  						<th></th>
  					</tr>
  					<tr>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th width="" colspan="8">यो माग फाराम सम्मको कुल निकासा रु.........</th>
  						<th></th>
  						<th colspan="2"></th>
  						<th></th>
  						<th></th>
  					</tr>
  					<tr>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th></th>
  						<th width="" colspan="8">निकासा हुन बाँकी रु.........</th>
  						<th></th>
  						<th colspan="2"></th>
  						<th></th>
  						<th></th>
  					</tr>
  					<tr class="rotate main-th-nowrap">
  						<th class="main-rotate-90"><span>सिनं</span></th>
  						<th class="main-rotate-90"><span>नाम</span></th>
  						<th class="main-rotate-90"><span>पद</span></th>
  						<th class="main-rotate-90"><span>तह</span></th>
  						<th class="main-rotate-90"><span>श्रेणी</span></th>
  						<th class="main-rotate-90"><span>किसिम <br>(स्थायी/अस्थायी)<br>(राहत/नियम/अन्य)</span></th>
  						<th class="main-rotate-90"><span>शुरु तलब स्केल</span></th>
  						<th class="main-rotate-90"><span>ग्रेड</span></th>
  						<th class="main-rotate-90"><span>महंगी भत्ता</span></th>
  						<th class="main-rotate-90"><span>स्थायीय भत्ता</span></th>
  						<th class="main-rotate-90"><span>प्रद्यानाध्यापक भत्ता</span></th>
  						<th class="main-rotate-90"><span>चाडपर्व खर्च</span></th>
  						<th class="main-rotate-90"><span>पोशक खर्च</span></th>
  						<th class="main-rotate-90"><span>योगदनमा आधारित<br>पेन्सन/उपदन</span></th>
  						<th class="main-rotate-90"><span>जम्मा</span></th>
  						<th class="main-rotate-90"><span>क स कोष थप</span></th>
  						<th class="main-rotate-90"><span>विना थप</span></th>
  						<th class="main-rotate-90"><span>योगदनमा आधारित<br>पेन्सन/उपदन थप</span></th>
  						<th class="main-rotate-90"><span>अन्य</span></th>
  						<th class="main-rotate-90"><span>कूल जम्मा</span></th>
  						<th class="main-rotate-90"><span>कैफियत</span></th>
  					</tr>
  				</thead>
  				<tbody>
            @foreach($teacherincomes as $key => $income)
            @php
            $total1= $income->grade + $income->mahangi_vatta + $income->permanent_allowance + $income->pradyanadhyapak_bhatta + $income->chadparva_kharcha + $income->cloth_amount + $income->pension;
            $total2 = $income->pro_f2 + $income->insurance + $income->pension_added;
            @endphp 
  					<tr class="rotate">
  						<td>{{$key+1}}</td>
  						<td>{{$income->getTeacherUser->name}} {{$income->getTeacherUser->middle_name}} {{$income->getTeacherUser->last_name}}</td>
  						<td>{{$income->getTeacher->designation}}</td>
  						<td>{{$income->getTeacher->t_designation }}</td>
  						<td>{{$income->getTeacher->uppertype }}</td>
  						<td>{{number_format($income->durgam_vatta)}}</td>
  						<td>{{number_format($income->amount)}}</td>
  						<td>{{number_format($income->grade)}}</td>
  						<td>{{number_format($income->mahangi_vatta)}}</td>
  						<td>{{number_format($income->permanent_allowance)}}</td>
  						<td>{{number_format($income->pradyanadhyapak_bhattā)}}</td>
  						<td>{{number_format($income->chadparva_kharcha)}}</td>
  						<td>{{number_format($income->cloth_amount)}}</td>
  						<td>{{number_format($income->pension)}}</td>
  						<td>{{number_format($total1)}}</td>
  						<td>{{number_format($income->pro_f2)}}</td>
  						<td>{{number_format($income->insurance)}}</td>
  						<td>{{number_format($income->pension_added)}}</td>
  						<td></td>
  						<td>{{number_format($total2)}}</td>
  						<td>{{$income->remark}}</td>
  					</tr>
            <tr>
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
            @endforeach
  				</tbody>
          {{-- @endforeach --}}
  			</table>
  		</div>
      <div class="row col-md-12">
        <div class="col-md-4">
          <div class="form-group row">
            <label class="col-md-4 col-form-label">विद्यालयको छप:</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="col-md-4 col-form-label">तयार गर्नेको नाम:</label><br>
            <label class="col-md-4 col-form-label">सही:</label><br>
            <label class="col-md-4 col-form-label">मिति:</label><br>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="col-md-5 col-form-label">प्रद्यानाध्यापक नाम:</label><br>
            <label class="col-md-4 col-form-label">सही:</label><br>
            <label class="col-md-4 col-form-label">मिति:</label><br>
          </div>
        </div>
      </div>
  </div>
</section>
@endsection