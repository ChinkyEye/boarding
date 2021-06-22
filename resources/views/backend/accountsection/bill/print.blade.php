@extends('backend.main.app')
@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
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
  <div class="container-fluid">
    <button class="btn btn-info btn-sm rounded-0" onclick="PrintDiv('printTable')"><i class="fas fa-print"></i> Print</button>
    <div class="row" id="printTable">
      <div class="col-12">
        <div class="invoice p-3 mb-3">
          <div class="row">
            <div class="col-12">
              <h4>
                <small class="float-right">Date: {{date("Y-m-d", strtotime($bill_total_student->created_at_np))}}</small>
              </h4>
            </div>
          </div>
          <div class="row invoice-info">
            <div class="col-sm-12 text-center mb-2">
              <div><small>{{ Auth::user()->getSchool->address }}</small></div>
              <h3 class="m-0">{{ Auth::user()->getSchool->school_name }}</h3>
              <div><small><b>Phone:</b> {{ Auth::user()->getSchool->phone_no }}</small> | <small><b>Email:</b> {{ Auth::user()->getSchool->email }}</small></div>
              {{-- @foreach($settings as $key=>$setting) 
              <address>
                <strong>{{$setting->school_name}}</strong><br>
                {{$setting->address}}<br>
                Phone: (804) 123-5432{{$setting->phone_no}}<br>
                Email: {{$setting->email}}
              </address>
              @endforeach --}}
            </div>
            <div class="col-sm-6 invoice-col">
              To
              <address>
                <strong>{{$datas->name}} {{$datas->middle_name}} {{$datas->last_name}} ({{$datas->getUserStudent->student_code}})</strong><br>
                Phone/Email: {{$datas->getUserStudent->phone_no}}/{{$datas->email}}<br>
                Roll no: {{$datas->getUserStudent->roll_no}}
              </address>
            </div>
            <div class="col-sm-6 text-right">
              <b>Invoice Number: {{$bill_id}}</b><br>
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-12 table-responsive">
              <table class="table table-striped table-sm">
                <tr>
                  <th width="10px">SN</th>
                  <th width="100px">Description</th>
                  <th width="100px" class="text-right">Price</th>
                </tr>
                @forelse($bill_total_student->getStudentBillList()->get() as $key=>$bills) 
                <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$bills->getTopic->topic}}</td>
                  <td class="text-right">{{$bills->amount}}</td>
                </tr>
                @empty
                <tr>
                  <td>1</td>
                  <td>Paid</td>
                  <td class="text-right">{{$bill_total_student->nettotal}}</td>
                </tr>
                @endforelse
                <tr class="text-right">
                  <th colspan="2">Subtotal:</th>
                  <td>Rs:{{$bill_total_student->total}}</td>
                </tr>
                <tr class="text-right">
                  <th colspan="2">Discount (Rs.)</th>
                  <td>Rs.{{$bill_total_student->discount}}</td>
                </tr>
                <tr class="text-right">
                  <th colspan="2">Fine:</th>
                  <td>Rs:{{$bill_total_student->fine}}</td>
                </tr>
                <tr class="text-right">
                  <th colspan="2">Total:</th>
                  <td>Rs:{{$bill_total_student->nettotal}}</td>
                </tr>
                <tr class="text-right">
                  <th colspan="2">Words:</th>
                  <td class="text-capitalize">{{Terbilang::make($bill_total_student->nettotal)}} Only</td>
                </tr>
              </table>
          </div>
        </div>
        
        <div class="row no-print">
          <div class="col-12">
            <span>Bill Printed By : {{$bill_total_student->getUser->name}}</span>
            <span class="float-right">Bill Date: {{$bill_total_student->created_at_np}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
@endsection
