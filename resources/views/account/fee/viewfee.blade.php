@extends('account.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('account.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">
          {{$student_info->getStudentUser->name}}
          {{$student_info->getStudentUser->middle_name}}
          {{$student_info->getStudentUser->last_name}} - {{$student_info->phone_no}}
          ({{$student_info->getShift->name}} | {{$student_info->getClass->name}} | {{$student_info->getSection->name}})
        </h1>
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
  <div class="card">
    <div class="card-header">
      <span class="font-weight-bolder">Billing Record</span>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover m-0">
        <thead class="bg-dark">
          <tr class="text-center">
            <th width="10">SN</th>
            <th class="text-left">Bill for</th>
            <th>Invoice No.</th>
            <th width="150">Bill Amount</th>
            <th width="200">Date</th>
          </tr>
        </thead>              
        @foreach($bills as $key=>$bill)             
        <tr class="text-center">
          <td>{{$key+1}}</td>
          <td class="text-left">{{$bill->getTopic->topic}}</td>
          <td><a href="{{route('account.bill.print',['bill_id' => $bill->invoice_id])}}" target="_blank">{{$bill->invoice_id}}</a></td>
          <td>Rs. {{$bill->amount}}</td>
          <td>{{$bill->created_at_np}}</td>
        </tr>
        @endforeach
        <tfoot>
          <tr class="font-weight-bold text-center">
            <td colspan="3" class="text-right">Total:</td>
            <td>Rs. {{$bills->sum('amount')}}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="font-weight-bolder">Fee Received</span>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover m-0">
        <thead class="bg-dark">
          <tr class="text-center">
            <th width="10">SN</th>
            <th width="100">Bill</th>
            <th width="100">Received</th>
            <th width="100">Remained</th>
            <th width="150">Date</th>
            <th width="150">Status</th>
          </tr>
        </thead>              
        @foreach($fees as $key=>$fee)             
        <tr class="text-center bg-light-{{$fee->is_active == '1' ? 'success' : 'danger'}}" data-toggle="tooltip" data-placement="top" title="{{ $fee->is_active == '1' ? 'This data is published':' This data is not published'}}">
          <td>{{$key+1}}</td>
          <td><a href="{{route('account.bill.print',['bill_id' => $fee->invoice_id])}}" target="_blank">{{$fee->invoice_id}}</a></td>
          <td>Rs. {{$fee->amount_received}}</td>
          <td>Rs. {{$fee->amount_remained}}</td>
          <td>{{$fee->created_at_np}}</td>
          <td>
            <a href="{{ route('account.fee.active',$fee->id) }}" class="deletebill-confirm" data-toggle="tooltip" data-placement="top" title="{{ $fee->is_active == '1' ? 'Click to Delete' : 'Click to activate' }}" >
              <i class="fa {{ $fee->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
            </a>
          </td>
        </tr>
        @endforeach
        <tfoot>
          <tr class="font-weight-bold text-center">
            <td colspan="2" class="text-right">Total:</td>
            <td class="border-left-0">Rs. {{$fees->sum('amount_received')}}</td>
            <td class="text-danger">Rs. {{$bills->sum('amount') - $fees->sum('amount_received')}}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="font-weight-bolder"> Deleted Bill</span>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover m-0">
        <thead class="bg-dark">
          <tr class="text-center">
            <th width="10">SN</th>
            <th width="100">Bill</th>
            <th width="100">Received</th>
            <th width="100">Remained</th>
            <th width="150">Date</th>
            <th width="150">Status</th>
          </tr>
        </thead>              
        @foreach($inactivefees as $key=>$inactivefee)             
        <tr class="text-center bg-light-{{$inactivefee->is_active == '1' ? 'success' : 'danger'}}" data-toggle="tooltip" data-placement="top" title="{{ $inactivefee->is_active == '1' ? 'This data is published':' This data is not published'}}">
          <td>{{$key+1}}</td>
          <td><a href="{{route('admin.bill.print',['bill_id' => $inactivefee->invoice_id])}}" target="_blank">{{$inactivefee->invoice_id}}</a></td>
          <td>Rs. {{$inactivefee->amount_received}}</td>
          <td>Rs. {{$inactivefee->amount_remained}}</td>
          <td>{{$inactivefee->created_at_np}}</td>
          <td>
              <i class="fa {{ $inactivefee->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</section>
@endsection
