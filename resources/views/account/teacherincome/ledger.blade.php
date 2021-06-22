@extends('account.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('account.','',Route::currentRouteName()), ".")); ?>
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
  <div class="card">
    <div class="card-header">
    	<p class="card-title">Monthly Salary Detail</p>
    	<div class="card-tools">
    		<button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
    		<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
    		<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
    		<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
    	</div>
    </div>
    <div class="card-body">
    	<div class="row col-md-6 pb-3">
          <div class="col">
          	<select class="form-control" name="month" id="month">
          		<option value="">Select Month to generate ledger </option>
          		<option value="1">Baisakh</option>
          		<option value="2">Jestha</option>
          		<option value="3">Asar</option>
          		<option value="4">Srawan </option>
          		<option value="5">Bhadra</option>
          		<option value="6">Aaswin</option>
          		<option value="7">Kartik</option>
          		<option value="8">Mangsir</option>
          		<option value="9">Paush</option>
          		<option value="10">Magh</option>
          		<option value="11">Falgun</option>
          		<option value="12">Chaitra</option>
          	</select>
          </div>
        </div>
        <div class="table-responsive" id="replaceTable">
        <table class="table table-bordered table-hover position-relative w-100 m-0" id="">
          <thead class="bg-dark">
            <tr class="text-center">
            <th width="10">SN</th>
            <th class ="text-left">Month</th>
            <th class ="text-left" width="110">Teacher</th>
            <th class ="text-left">Salary</th>
            <th class ="text-left">Grade</th>
            <th class ="text-left">Mahangi Vatta</th>
            <th class ="text-left">Durgam Vatta</th>
            <th class ="text-left">Citizen Investment Deduction</th>
            <th class ="text-left">Loan Deduction</th>
            <th class ="text-left">Cloth Amount</th>
            <th class ="text-left">Remarks</th>
            {{-- <th class ="text-left">Created By</th> --}}
            <th class ="text-left" width="160">Action</th>
          </tr>
          </thead>
          <tbody id="replace_table">

          </tbody>            
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript">
  $("body").on("change","#month", function(event){
    Pace.start();
    var month_id = $("#month").val(),
        token = $('meta[name="csrf-token"]').attr('content');
        // debugger;
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('account.getLedgerInfoList')}}",
      data:{
        _token: token,
        month_id:month_id
      },
      success: function(response){
        console.log(response);
        $('#replace_table').html(response);
        // document.getElementById('month').value = '';
      },
      error: function(event){
        console.log(this);
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $(document).ready(function() { 
    $("#month").val('');
  });
  // window.onload = function(){
  //   $("#month").val('');
  // };
</script>
@endpush