@extends('account.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('account.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{$student_info->getStudentUser->name}}
          {{$student_info->getStudentUser->middle_name}} {{$student_info->getStudentUser->last_name}} - {{$student_info->phone_no}} | {{$student_info->getClass->name}} | {{$student_info->getShift->name}} | {{$student_info->getSection->name}}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('account.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{$student_info->getStudentUser->name}}
          {{$student_info->getStudentUser->middle_name}} {{$student_info->getStudentUser->last_name}}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md">
        <div class="card card-info">
          <form role="form" method="POST" action="{{route('account.fee.store')}}" class="validate" id="validate">
            <input type="hidden" name="student_id" value="{{ $student_id }}">
            <div class="card-body">
              @csrf
              <div class="row">
                <div class="form-group col-md">
                  <label for="discount" class="control-label">Discount</label>
                  <input type="text"  class="form-control @error('discount') is-invalid @enderror max" id="discount" placeholder="Enter discount" name="discount" autocomplete="off" value="0">
                  @error('discount')
                  <span class="text-danger font-italic" role="alert">
                   <strong>{{ $message }}</strong>
                 </span>
                 @enderror
                </div>
                <div class="form-group col-md">
                  <label for="fine" class="control-label">Fine</label>
                  <input type="text"  class="form-control" id="fine" placeholder="Enter fine" name="fine" autocomplete="off" value="0">
                  @error('fine')
                  <span class="text-danger font-italic" role="alert">
                   <strong>{{ $message }}</strong>
                 </span>
                 @enderror
                </div>
                <div class="form-group col-md">
                  <label for="amount_received" class="control-label">Amount Received</label>
                  <input type="text"  class="form-control" id="amount_received" placeholder="Enter amount received" name="amount_received" autocomplete="off" autofocus>
                  @error('amount_received')
                  <span class="text-danger font-italic" role="alert">
                   <strong>{{ $message }}</strong>
                 </span>
                 @enderror
                </div>
                <div class="form-group col-md">
                  <label for="amount_received" class="control-label">Due</label>
                  <input type="text"  class="form-control" id="totalwithfine" readonly placeholder="Net value" value="{{$amount_remain_total}}" name="amount_remained">
                </div>
              </div>
            </div>
            <div class="card-footer justify-content-between">
              <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Save Fee">Save</button>
            </div>
          </form>
        </div>
        <div class="card card-info">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Fine</th>
                    <th>Discount</th>
                    <th>Amount Received</th>
                    <th>Remained Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($invoice_paid_bill_all as $key=>$feepaid) 
                  <tr>
                    <td>{{date('d-m-Y', strtotime($feepaid->created_at_np ))}}</td>
                    <th>{{ $feepaid->fine }}</th>
                    <th>{{ $feepaid->discount }}</th>
                    <th>{{ $feepaid->amount_received }}</th>
                    <td>Rs.{{$feepaid->amount_remained }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Invoice No.</th>
                    <th class="text-right">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($invoice_bill_all as $key=>$bills)
                  <tr style="background-color: {{$bills->bill_type == '2' ? '#cde0ba' : '#eccdcd'}}">
                    <th style="width:50%"><a href="{{route('account.bill.print',['bill_id' => $bills->invoice_id])}}" target="_blank">{{ $bills->invoice_id }}</a></th>
                    <td class="text-right">Rs. {{$bills->nettotal }}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Total:</th>
                    <td class="text-right">Rs.{{ $amount_remain_total}}</td>
                  </tr>
                  <tr>
                    <th>In Words:</th>
                    <td class="text-right text-capitalize">Rs. {{ $amount_words}} only</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript">
  $(document).ready(function() {
       $(':input[type="submit"]').prop('disabled', true);
   });
  $('#discount').keyup(function(event){
    Pace.start();
    event.preventDefault();
    debugger;
    if($(this).val() != '') {
     var total_fee = {{$amount_remain_total}};
     var discount = $("#discount").val();
     var nettotal = total_fee - parseInt(discount);
     Pace.stop();
     $('#totalwithfine').val(nettotal);
       $(':input[type="submit"]').prop('disabled', false);
    }
      
  });
  $('#fine').keyup(function(event){
    Pace.start();
    event.preventDefault();
    if($(this).val() != '') {
      var total_fee = {{$amount_remain_total}};
      var discount = $("#discount").val();
      var fine = $("#fine").val();
      var nettotal = total_fee - parseInt(discount) + parseInt(fine);
      Pace.stop();
      $('#totalwithfine').val(nettotal);
      $(':input[type="submit"]').prop('disabled', false);
    }

  });
  $('#amount_received').keyup(function(event){
    Pace.start();
    event.preventDefault();
    // debugger;
    if($(this).val() != '') {
      var amount_received = $("#amount_received").val();
      var discount = $("#discount").val();
      var fine = $("#fine").val();
      var total_fee = {{$amount_remain_total}};
      var nettotal = total_fee - amount_received - parseInt(discount) + parseInt(fine);
      Pace.stop();
      $('#totalwithfine').val(nettotal);
      if(amount_received > {{$amount_remain_total}}){
         toastr.error(" Sorry, Amount is greater than bill!");
        $(':input[type="submit"]').prop('disabled', true);
      }
      else{
       $(':input[type="submit"]').prop('disabled', false);

      }
    }
  });
</script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      amount_received: {
        required: true,
        number:true
      },
      discount: {
        required: true,
        number:true
      },
      fine: {
        required: true,
        number:true
      }
    },
    messages: {
      discount: {
        required: "Please Enter Your Discount",
        number:"Please enter numbers Only"
      },
      fine: {
        required: "Please Enter Your Amount",
        number:"Please enter numbers Only"
      },
      amount_received: {
        required: "Please Enter Your Amount",
        number:"Please enter numbers Only"
      }
    },
    highlight: function(element) {
     $(element).css('background', '#ffdddd');
     $(element).css('border-color', 'red');
    },
    unhighlight: function(element) {
     $(element).css('background', '#ffffff');
     $(element).css('border-color', 'green');
    }
  });
});
</script>
@endpush